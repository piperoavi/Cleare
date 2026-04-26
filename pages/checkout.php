<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/cart_functions.php';
require_once __DIR__ . '/../includes/auth.php';

$page_title = "Checkout — Clearè";

// Nëse cart-i është bosh ridrejto
$cart_items = cart_get_items($pdo);
if (empty($cart_items)) {
    header('Location: cart.php');
    exit;
}

$subtotal        = cart_subtotal($cart_items);
$discount        = $_SESSION['coupon_discount'] ?? 0;
$coupon_id       = $_SESSION['coupon_id']       ?? null;
$total           = $subtotal - $discount;
$errors          = [];
$success         = false;

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

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
                ")-> execute([$item['quantity'], $item['id']]);
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

            // 5. Pastro cart dhe kuponin
            cart_clear();
            unset(
                $_SESSION['coupon_code'],
                $_SESSION['coupon_id'],
                $_SESSION['coupon_discount']
            );

            // 6. Ridrejto te konfirmimi
            header('Location: order-confirm.php?order_id=' . $order_id);
            exit;

        } catch (Exception $e) {
            $pdo->rollBack();
            $errors[] = 'An error occurred. Please try again.';
        }
    }
}

$page_title = "Checkout — Clearè";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
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
                            <div class="payment-sub">Pay when your order arrives</div>
                        </div>
                    </div>
                </label>

                <label class="payment-option">
                    <input type="radio" name="payment_method" value="paypal"
                           <?php echo (($_POST['payment_method'] ?? '') === 'paypal') ? 'checked' : ''; ?>>
                    <div class="payment-card">
                        <span class="payment-icon">🅿️</span>
                        <div>
                            <div class="payment-name">PayPal</div>
                            <div class="payment-sub">Demo — no real transaction</div>
                        </div>
                    </div>
                </label>

                <label class="payment-option">
                    <input type="radio" name="payment_method" value="stripe"
                           <?php echo (($_POST['payment_method'] ?? '') === 'stripe') ? 'checked' : ''; ?>>
                    <div class="payment-card">
                        <span class="payment-icon">💳</span>
                        <div>
                            <div class="payment-name">Credit / Debit Card</div>
                            <div class="payment-sub">Demo — no real transaction</div>
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
                    <?php echo number_format($item['subtotal'], 2); ?> L
                </div>
            </div>
            <?php endforeach; ?>

            <div class="summary-divider"></div>

            <div class="summary-row">
                <span>Subtotal</span>
                <span><?php echo number_format($subtotal, 2); ?> L</span>
            </div>

            <?php if ($discount > 0): ?>
            <div class="summary-row" style="color:#27AE60;">
                <span>Discount</span>
                <span>−<?php echo number_format($discount, 2); ?> L</span>
            </div>
            <?php endif; ?>

            <div class="summary-row">
                <span>Shipping</span>
                <span class="summary-free">Free</span>
            </div>

            <div class="summary-divider"></div>

            <div class="summary-row summary-total">
                <span>Total</span>
                <span><?php echo number_format($total, 2); ?> L</span>
            </div>

        </div>

    </div>

</section>

<?php include('../includes/footer.php'); ?>
<?php include(__DIR__ . '/../includes/nav-js.php'); ?>

</body>
</html>