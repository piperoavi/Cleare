<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/cart_functions.php';

$page_title = "Cart — Clearè";

// Merr produktet nga session
$cart_items = cart_get_items($pdo);
$subtotal   = cart_subtotal($cart_items);

// Discount nga kuponi
$discount     = 0;
$coupon_msg   = '';
$coupon_code  = $_SESSION['coupon_code']  ?? '';
$coupon_id    = $_SESSION['coupon_id']    ?? null;

if (!empty($coupon_code)) {
    $discount = $_SESSION['coupon_discount'] ?? 0;
}

$total = $subtotal - $discount;

// Veprimet e cart-it
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Hiq produkt
    if (isset($_POST['remove'])) {
        cart_remove((int) $_POST['remove']);
        header('Location: cart.php');
        exit;
    }

    // Azhurno sasi
    if (isset($_POST['update_qty'])) {
        cart_update((int) $_POST['product_id'], (int) $_POST['quantity']);
        header('Location: cart.php');
        exit;
    }

    // Apliko kupon
    if (isset($_POST['apply_coupon'])) {
        $code = strtoupper(trim($_POST['coupon_code'] ?? ''));

        // Kontrollo kuponin në DB
        $stmt = $pdo->prepare("
            SELECT * FROM coupons
            WHERE code = ? AND active = 1 AND expires_at >= CURDATE()
        ");
        $stmt->execute([$code]);
        $coupon = $stmt->fetch();

        if (!$coupon) {
            $coupon_msg = 'error:Invalid or expired coupon code.';
        } else {
            // NEWUSER vetëm për të regjistruarit
            if ($code === 'NEWUSER' && !isset($_SESSION['user_id'])) {
                $coupon_msg = 'error:This coupon is only available for registered customers. Please log in or create an account.';
                header('Location: cart.php?msg=' . urlencode($coupon_msg));
                exit;
            }

            // Kontrollo nëse NEWUSER është përdorur tashmë
            if ($code === 'NEWUSER' && isset($_SESSION['user_id'])) {
                $stmt2 = $pdo->prepare("
                    SELECT id FROM coupon_usage
                    WHERE user_id = ? AND coupon_id = ?
                ");
                $stmt2->execute([$_SESSION['user_id'], $coupon['id']]);
                if ($stmt2->fetch()) {
                    $coupon_msg = 'error:You have already used this coupon.';
                    header('Location: cart.php');
                    exit;
                }
            }

            // Kontrollo SKIN20 — duhet produkte skincare
            if ($coupon['applies_to'] === 'skincare') {
                $has_skincare = false;
                foreach ($cart_items as $item) {
                    if ($item['type'] === 'skincare') {
                        $has_skincare = true;
                        break;
                    }
                }
                if (!$has_skincare) {
                    $coupon_msg = 'error:This coupon applies only to Skincare products.';
                    header('Location: cart.php');
                    exit;
                }
            }

            // Llogarit discount-in
            if ($coupon['type'] === 'percent') {
                $base = $coupon['applies_to'] === 'all'
                    ? $subtotal
                    : array_sum(array_map(
                        fn($i) => $i['type'] === $coupon['applies_to'] ? $i['subtotal'] : 0,
                        $cart_items
                    ));
                $disc = round($base * $coupon['discount_value'] / 100, 2);
            } else {
                $disc = min($coupon['discount_value'], $subtotal);
            }

            $_SESSION['coupon_code']     = $code;
            $_SESSION['coupon_id']       = $coupon['id'];
            $_SESSION['coupon_discount'] = $disc;

            $coupon_msg = 'success:Coupon ' . $code . ' applied — ' .
                ($coupon['type'] === 'percent'
                    ? $coupon['discount_value'] . '% off!'
                    : number_format($disc, 2) . ' L off!');
        }

        header('Location: cart.php?msg=' . urlencode($coupon_msg));
        exit;
    }

    // Hiq kupon
    if (isset($_POST['remove_coupon'])) {
        unset($_SESSION['coupon_code'], $_SESSION['coupon_id'], $_SESSION['coupon_discount']);
        header('Location: cart.php');
        exit;
    }
}

// Mesazhi nga redirect
if (isset($_GET['msg'])) {
    $coupon_msg = urldecode($_GET['msg']);
}
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

<section class="cart-page">

  <div class="section-header">
    <div class="section-eyebrow">Your Selection</div>
    <h1 class="section-title">Shopping <em>Cart</em></h1>
  </div>

  <?php if (!empty($cart_items)): ?>

  <div class="cart-layout">

    <!-- ── Left: product list ── -->
    <div class="cart-items">

      <?php foreach ($cart_items as $item): ?>
      <div class="cart-row">

        <div class="cart-img">
          <img src="../assets/images/<?php echo htmlspecialchars($item['image']); ?>"
               alt="<?php echo htmlspecialchars($item['name']); ?>">
        </div>

        <div class="cart-info">
          <div class="cart-cat"><?php echo ucfirst($item['type']); ?></div>
          <div class="cart-name"><?php echo htmlspecialchars($item['name']); ?></div>
          <div class="cart-unit-price">
            <?php echo number_format($item['price'], 2); ?> L / unit
          </div>
        </div>

        <!-- Sasia me forma -->
        <form method="POST" action="cart.php" class="cart-qty-form">
          <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
          <input type="hidden" name="update_qty" value="1">
          <div class="cart-qty">
            <button type="submit" name="quantity"
                    value="<?php echo max(1, $item['quantity'] - 1); ?>"
                    class="qty-btn">−</button>
            <span class="qty-val"><?php echo $item['quantity']; ?></span>
            <button type="submit" name="quantity"
                    value="<?php echo $item['quantity'] + 1; ?>"
                    class="qty-btn">+</button>
          </div>
        </form>

        <div class="cart-line-total">
          <?php echo number_format($item['subtotal'], 2); ?> L
        </div>

        <!-- Hiq produktin -->
        <form method="POST" action="cart.php">
          <input type="hidden" name="remove" value="<?php echo $item['id']; ?>">
          <button type="submit" class="cart-remove" title="Remove">✕</button>
        </form>

      </div>
      <?php endforeach; ?>

    </div>

    <!-- ── Right: order summary ── -->
    <div class="cart-summary">

      <h2 class="summary-title">Order Summary</h2>

      <div class="summary-row">
        <span>Subtotal</span>
        <span><?php echo number_format($subtotal, 2); ?> L</span>
      </div>

      <?php if ($discount > 0): ?>
      <div class="summary-row" style="color:#27AE60;">
        <span>Discount (<?php echo htmlspecialchars($coupon_code); ?>)</span>
        <span>−<?php echo number_format($discount, 2); ?> L</span>
      </div>
      <?php endif; ?>

      <div class="summary-row">
        <span>Shipping</span>
        <span class="summary-free">Free</span>
      </div>

      <!-- Kupon -->
      <?php if (empty($coupon_code)): ?>
      <form method="POST" action="cart.php">
        <input type="hidden" name="apply_coupon" value="1">
        <div class="coupon-row">
          <input type="text" name="coupon_code" class="coupon-input"
                 placeholder="Coupon code">
          <button type="submit" class="coupon-apply">Apply</button>
        </div>
      </form>
      <?php else: ?>
      <div class="coupon-row" style="align-items:center;">
        <span style="font-size:13px;color:var(--sky-deep);font-weight:500;">
          ✓ <?php echo htmlspecialchars($coupon_code); ?> applied
        </span>
        <form method="POST" action="cart.php" style="margin:0">
          <input type="hidden" name="remove_coupon" value="1">
          <button type="submit" class="coupon-apply"
                  style="background:#E74C3C;">Remove</button>
        </form>
      </div>
      <?php endif; ?>

      <?php if (!empty($coupon_msg)): ?>
        <?php [$type, $msg] = explode(':', $coupon_msg, 2); ?>
        <p class="coupon-msg" style="color:<?php echo $type === 'success' ? '#27AE60' : '#E74C3C'; ?>">
          <?php echo htmlspecialchars($msg); ?>
        </p>
      <?php endif; ?>

      <div class="summary-divider"></div>

      <div class="summary-row summary-total">
        <span>Total</span>
        <span><?php echo number_format($total, 2); ?> L</span>
      </div>

      <a href="checkout.php" class="btn-primary btn-full">
        Proceed to Checkout
      </a>

      <a href="shop.php" class="cart-continue">← Continue Shopping</a>

    </div>

  </div>

  <?php else: ?>

  <div class="cart-empty">
    <div class="cart-empty-icon">🛒</div>
    <h2>Your cart is empty</h2>
    <p>Looks like you haven't added anything yet.</p>
    <a href="shop.php" class="btn-primary">Start Shopping</a>
  </div>

  <?php endif; ?>

</section>

<?php include('../includes/footer.php'); ?>
<?php include(__DIR__ . '/../includes/nav-js.php'); ?>

</body>
</html>