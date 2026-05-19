<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/cart_functions.php';
require_once __DIR__ . '/../includes/auth.php';

$page_title = "Checkout  Clearè";

// Nëse cart-i është bosh ridrejto
$all_items = cart_get_items($pdo);
if (empty($all_items)) {
    header('Location: cart.php');
    exit;
}

// Filtro sipas selected_items
if (!empty($_POST['selected_items'])) {
    $selected_ids = array_map('intval', $_POST['selected_items']);
    $_SESSION['checkout_selected'] = $selected_ids;
}

if (!empty($_SESSION['checkout_selected'])) {
    $selected_ids = $_SESSION['checkout_selected'];
    $cart_items   = array_values(array_filter(
        $all_items,
        fn($item) => in_array((int)$item['id'], $selected_ids)
    ));
} else {
    $cart_items = $all_items;
}

if (empty($cart_items)) {
    header('Location: cart.php');
    exit;
}

$subtotal  = cart_subtotal($cart_items);
$discount  = $_SESSION['coupon_discount'] ?? 0;
$coupon_id = $_SESSION['coupon_id']       ?? null;
$total     = $subtotal - $discount;
$errors    = [];

// Pre-fill nga sesioni nëse është i loguar
$prefill = [];
if (isLoggedIn()) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $u = $stmt->fetch();
    $prefill = [
        'name'    => $u['name']    ?? '',
        'email'   => $u['email']   ?? '',
        'phone'   => $u['phone']   ?? '',
        'address' => $u['address'] ?? '',
        'city'    => $u['city']    ?? '',
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['shipping_name'])) {

    $name    = trim($_POST['shipping_name']    ?? '');
    $email   = trim($_POST['shipping_email']   ?? '');
    $phone   = trim($_POST['shipping_phone']   ?? '');
    $address = trim($_POST['shipping_address'] ?? '');
    $city    = trim($_POST['shipping_city']    ?? '');
    $payment = $_POST['payment_method']        ?? 'cash';

    // Validim
    if ($name    === '') $errors[] = 'Full name is required.';
    if ($email   === '' || !filter_var($email, FILTER_VALIDATE_EMAIL))
                         $errors[] = 'A valid email is required.';
    if ($phone   === '') $errors[] = 'Phone number is required.';
    if ($address === '') $errors[] = 'Shipping address is required.';
    if ($city    === '') $errors[] = 'City is required.';
    if (!in_array($payment, ['cash', 'paypal', 'stripe']))
                         $errors[] = 'Please select a payment method.';

    if (empty($errors)) {
        try {
            $pdo->beginTransaction();

            // 1. Krijo porosinë
            $user_id = isLoggedIn() ? $_SESSION['user_id'] : null;

            $stmt = $pdo->prepare("
                INSERT INTO orders
                    (user_id, coupon_id, subtotal, discount, total,
                     status, payment_method, payment_status,
                     shipping_name, shipping_email, shipping_address,
                     shipping_city, shipping_phone)
                VALUES (?, ?, ?, ?, ?, 'pending', ?, 'unpaid', ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $user_id, $coupon_id, $subtotal, $discount, $total,
                $payment, $name, $email, $address, $city, $phone
            ]);

            $order_id = $pdo->lastInsertId();

            // 2. Shto order_items
            $stmt2 = $pdo->prepare("
                INSERT INTO order_items
                    (order_id, product_id, product_name, price, quantity)
                VALUES (?, ?, ?, ?, ?)
            ");
            foreach ($cart_items as $item) {
                $stmt2->execute([
                    $order_id,
                    $item['id'],
                    $item['name'],
                    $item['price'],
                    $item['quantity'],
                ]);

                // Zvogëlo stokun
                $pdo->prepare("
                    UPDATE products SET stock = stock - ? WHERE id = ?
                ")->execute([$item['quantity'], $item['id']]);
            }

            // 3. Regjistro coupon_usage nëse ka kupon
            if ($coupon_id && $user_id) {
                $pdo->prepare("
                    INSERT IGNORE INTO coupon_usage (user_id, coupon_id)
                    VALUES (?, ?)
                ")->execute([$user_id, $coupon_id]);
            }

            // 4. Shto pikë loyalty (1 pikë për çdo 10L)
            if ($user_id) {
                $points = (int) floor($total / 10);
                if ($points > 0) {
                    $pdo->prepare("
                        INSERT INTO points_log (user_id, points, reason)
                        VALUES (?, ?, ?)
                    ")->execute([
                        $user_id, $points,
                        'Purchase - Order #' . $order_id
                    ]);
                    $pdo->prepare("
                        UPDATE users SET points = points + ? WHERE id = ?
                    ")->execute([$points, $user_id]);
                }
            }

            $pdo->commit();

            // 5. Pastro cart dhe session
if (!empty($_SESSION['checkout_selected'])) {
    foreach ($_SESSION['checkout_selected'] as $id) {
        cart_remove((int)$id);
    }
} else {
    cart_clear();
}            unset(
                $_SESSION['coupon_code'],
                $_SESSION['coupon_id'],
                $_SESSION['coupon_discount'],
                $_SESSION['checkout_selected']
            );

            // 6. Ridrejto sipas metodës së pagesës
            if ($payment === 'paypal') {
                require_once __DIR__ . '/../includes/paypal.php';
                $total_eur    = max(round($total / 110, 2), 0.01);
                $approval_url = paypal_create_order($total_eur, $order_id);

                if ($approval_url) {
                    header('Location: ' . $approval_url);
                    exit;
                } else {
                    $pdo->prepare("DELETE FROM orders WHERE id = ? AND payment_status = 'unpaid'")
                        ->execute([$order_id]);
                    $errors[] = 'PayPal is unavailable. Please choose another payment method.';
                }

            } elseif ($payment === 'stripe') {
                require_once __DIR__ . '/../includes/stripe.php';
                $total_eur    = max(round($total / 110, 2), 0.50);
                $checkout_url = stripe_create_session($total_eur, $order_id);

                if ($checkout_url) {
                    header('Location: ' . $checkout_url);
                    exit;
                } else {
                    $pdo->prepare("DELETE FROM orders WHERE id = ? AND payment_status = 'unpaid'")
                        ->execute([$order_id]);
                    $errors[] = 'Stripe is unavailable. Please choose another payment method.';
                }

            } else {
                header('Location: order-confirm.php?order_id=' . $order_id);
                exit;
            }

        } catch (Exception $e) {
            $pdo->rollBack();
            $errors[] = 'An error occurred. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="icon" type="image/svg+xml" href="/Cleare/assets/images/favicon.svg">
<link rel="icon" type="image/png" sizes="32x32" href="/Cleare/assets/images/favicon-32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/Cleare/assets/images/favicon-16.png">
<link rel="apple-touch-icon" sizes="180x180" href="/Cleare/assets/images/favicon-180.png">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include(__DIR__ . '/../includes/nav.php'); ?>

<section class="checkout-page">

    <div class="section-header">
        <div class="section-eyebrow">Almost there</div>
        <h1 class="section-title">Check<em>out</em></h1>
    </div>

    <!-- Progress steps -->
    <div class="checkout-steps">
        <div class="step active">
            <div class="step-dot">1</div>
            <span class="step-label">Cart</span>
        </div>
        <div class="step-line"></div>
        <div class="step active">
            <div class="step-dot">2</div>
            <span class="step-label">Details</span>
        </div>
        <div class="step-line"></div>
        <div class="step">
            <div class="step-dot">3</div>
            <span class="step-label">Confirm</span>
        </div>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="auth-errors" style="max-width:700px; margin:24px 0;">
            <?php foreach ($errors as $e): ?>
                <p>⚠️ <?php echo htmlspecialchars($e); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="checkout-layout">

        <!-- ── Left: formulari ── -->
        <form class="checkout-form" method="POST" action="checkout.php">

            <!-- Kalon selected items nga session -->
            <?php if (!empty($_SESSION['checkout_selected'])): ?>
                <?php foreach ($_SESSION['checkout_selected'] as $sid): ?>
                    <input type="hidden" name="selected_items[]" value="<?php echo (int)$sid; ?>">
                <?php endforeach; ?>
            <?php endif; ?>

            <!-- Adresa e dorëzimit -->
            <div class="checkout-section-title">Shipping Information</div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="shipping_name" class="form-input"
                           value="<?php echo htmlspecialchars($_POST['shipping_name'] ?? $prefill['name'] ?? ''); ?>"
                           required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="shipping_email" class="form-input"
                           value="<?php echo htmlspecialchars($_POST['shipping_email'] ?? $prefill['email'] ?? ''); ?>"
                           required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <input type="text" name="shipping_phone" class="form-input"
                           value="<?php echo htmlspecialchars($_POST['shipping_phone'] ?? $prefill['phone'] ?? ''); ?>"
                           required>
                </div>
                <div class="form-group">
                    <label class="form-label">City</label>
                    <input type="text" name="shipping_city" class="form-input"
                           value="<?php echo htmlspecialchars($_POST['shipping_city'] ?? $prefill['city'] ?? ''); ?>"
                           required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Address</label>
                <input type="text" name="shipping_address" class="form-input"
                       value="<?php echo htmlspecialchars($_POST['shipping_address'] ?? $prefill['address'] ?? ''); ?>"
                       required>
            </div>

            <!-- Metoda e pagesës -->
            <div class="checkout-section-title" style="margin-top:32px;">
                Payment Method
            </div>

            <div class="payment-options">

    <label class="payment-option">
        <input type="radio" name="payment_method" value="cash"
               <?php echo (!isset($_POST['payment_method']) || $_POST['payment_method'] === 'cash') ? 'checked' : ''; ?>>
        <div class="payment-card">
            <span class="payment-icon">💵</span>
            <div>
                <div class="payment-name">Cash on Delivery</div>
            </div>
        </div>
    </label>

    <label class="payment-option">
        <input type="radio" name="payment_method" value="paypal"
               <?php echo (($_POST['payment_method'] ?? '') === 'paypal') ? 'checked' : ''; ?>>
        <div class="payment-card">
            <span class="payment-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" height="28">
                    <path fill="#003087" d="M7.076 21.337H2.47a.641.641 0 0 1-.633-.74L4.944.901C5.026.382 5.474 0 5.998 0h7.46c2.57 0 4.578.543 5.69 1.81 1.01 1.15 1.304 2.42 1.012 4.287-.023.143-.047.288-.077.437-.983 5.05-4.349 6.797-8.647 6.797h-2.19c-.524 0-.968.382-1.05.9l-1.12 7.106zm14.146-14.42a3.35 3.35 0 0 0-.607-.541c-.013.076-.026.175-.041.254-.93 4.778-4.005 7.201-9.138 7.201h-2.19a.563.563 0 0 0-.556.479l-1.187 7.527h-.506l-.24 1.516a.56.56 0 0 0 .554.647h3.882c.46 0 .85-.334.922-.788.06-.26.76-4.852.816-5.09a.932.932 0 0 1 .923-.788h.58c3.76 0 6.705-1.528 7.565-5.946.36-1.847.174-3.388-.777-4.471z"/>
                </svg>
            </span>
            <div>
                <div class="payment-name">PayPal</div>
            </div>
        </div>
    </label>

    <label class="payment-option">
        <input type="radio" name="payment_method" value="stripe"
               <?php echo (($_POST['payment_method'] ?? '') === 'stripe') ? 'checked' : ''; ?>>
        <div class="payment-card">
            <span class="payment-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" height="28">
                    <path fill="#635BFF" d="M13.976 9.15c-2.172-.806-3.356-1.426-3.356-2.409 0-.831.683-1.305 1.901-1.305 2.227 0 4.515.858 6.09 1.631l.89-5.494C18.252.975 15.697 0 12.165 0 9.667 0 7.589.654 6.104 1.872 4.56 3.147 3.757 4.992 3.757 7.218c0 4.039 2.467 5.76 6.476 7.219 2.585.92 3.445 1.574 3.445 2.583 0 .98-.84 1.545-2.354 1.545-1.875 0-4.965-.921-6.99-2.109l-.9 5.555C5.175 22.99 8.385 24 11.714 24c2.641 0 4.843-.624 6.328-1.813 1.664-1.305 2.525-3.236 2.525-5.732 0-4.128-2.524-5.851-6.594-7.305h.003z"/>
                </svg>
            </span>
            <div>
                <div class="payment-name">Credit / Debit Card</div>
            </div>
        </div>
    </label>

</div>

            <p class="checkout-note">
                🔒 This is a demo checkout. No real payments are processed.
            </p>

            <button type="submit" class="btn-primary btn-full" style="margin-top:24px;">
                Place Order →
            </button>

        </form>

        <!-- ── Right: order summary ── -->
        <div class="cart-summary">

            <h2 class="summary-title">Your Order</h2>

            <?php foreach ($cart_items as $item): ?>
            <div class="checkout-item-row">
                <div class="checkout-item-img">
                    <img src="../assets/images/<?php echo htmlspecialchars($item['image']); ?>"
                         alt="<?php echo htmlspecialchars($item['name']); ?>">
                </div>
                <div class="checkout-item-info">
                    <div class="checkout-item-name">
                        <?php echo htmlspecialchars($item['name']); ?>
                    </div>
                    <div class="checkout-item-qty">
                        Qty: <?php echo $item['quantity']; ?>
                    </div>
                </div>
                <div class="checkout-item-price">
                    €<?php echo number_format($item['subtotal'], 2); ?> 
                </div>
            </div>
            <?php endforeach; ?>

            <div class="summary-divider"></div>

            <div class="summary-row">
                <span>Subtotal</span>
                <span>€<?php echo number_format($subtotal, 2); ?></span>
            </div>

            <?php if ($discount > 0): ?>
            <div class="summary-row" style="color:#27AE60;">
                <span>Discount</span>
                <span>−€<?php echo number_format($discount, 2); ?></span>
            </div>
            <?php endif; ?>

            <div class="summary-row">
                <span>Shipping</span>
                <span class="summary-free">Free</span>
            </div>

            <div class="summary-divider"></div>

            <div class="summary-row summary-total">
                <span>Total</span>
                <span>€<?php echo number_format($total, 2); ?></span>
            </div>

        </div>

    </div>

</section>

<?php include('../includes/footer.php'); ?>
<?php include(__DIR__ . '/../includes/nav-js.php'); ?>

</body>
</html>