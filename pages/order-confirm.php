<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

$page_title = "Order Confirmed — Clearè";

// Kontrollo nëse ka order_id
$order_id = (int) ($_GET['order_id'] ?? 0);

if ($order_id === 0) {
    header('Location: shop.php');
    exit;
}

// Merr porosinë nga DB
$stmt = $pdo->prepare("
    SELECT o.*, 
           c.code AS coupon_code
    FROM orders o
    LEFT JOIN coupons c ON o.coupon_id = c.id
    WHERE o.id = ?
");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    header('Location: shop.php');
    exit;
}

// Merr produktet e porosisë
$stmt2 = $pdo->prepare("
    SELECT * FROM order_items WHERE order_id = ?
");
$stmt2->execute([$order_id]);
$items = $stmt2->fetchAll();

// Numri i formatuar i porosisë
$order_number = 'CLR-' . str_pad($order_id, 5, '0', STR_PAD_LEFT);
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

<section class="confirm-page">

    <!-- Hero konfirmimi -->
    <div class="confirm-hero">
        <div class="confirm-icon">✓</div>
        <h1 class="confirm-title">Order Confirmed!</h1>
        <p class="confirm-sub">
            Thank you for your purchase. Your order has been placed successfully.
        </p>
        <span class="confirm-order-number">
            Order <?php echo $order_number; ?>
        </span>
    </div>

    <div class="confirm-layout">

        <!-- ── Majtas: detajet ── -->
        <div class="confirm-details">

            <!-- Produktet -->
            <div class="confirm-card">
                <h3 class="confirm-card-title">Items Ordered</h3>
                <?php foreach ($items as $item): ?>
                <div class="confirm-item-row">
                    <span>
                        <?php echo htmlspecialchars($item['product_name']); ?>
                        <span class="confirm-item-qty">× <?php echo $item['quantity']; ?></span>
                    </span>
                    <span class="confirm-item-price">
                        <?php echo number_format($item['subtotal'], 2); ?> L
                    </span>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Adresa e dorëzimit -->
            <div class="confirm-card">
                <h3 class="confirm-card-title">Shipping Details</h3>
                <div class="confirm-info-row">
                    <span class="confirm-info-label">Name</span>
                    <span><?php echo htmlspecialchars($order['shipping_name']); ?></span>
                </div>
                <div class="confirm-info-row">
                    <span class="confirm-info-label">Email</span>
                    <span><?php echo htmlspecialchars($order['shipping_email']); ?></span>
                </div>
                <div class="confirm-info-row">
                    <span class="confirm-info-label">Phone</span>
                    <span><?php echo htmlspecialchars($order['shipping_phone']); ?></span>
                </div>
                <div class="confirm-info-row">
                    <span class="confirm-info-label">Address</span>
                    <span>
                        <?php echo htmlspecialchars($order['shipping_address']); ?>,
                        <?php echo htmlspecialchars($order['shipping_city']); ?>
                    </span>
                </div>
                <div class="confirm-info-row">
                    <span class="confirm-info-label">Payment</span>
                    <span><?php echo ucfirst($order['payment_method']); ?></span>
                </div>
            </div>

        </div>

        <!-- ── Djathtas: totali ── -->
        <div class="cart-summary">

            <h2 class="summary-title">Order Summary</h2>

            <div class="summary-row">
                <span>Subtotal</span>
                <span><?php echo number_format($order['subtotal'], 2); ?> L</span>
            </div>

            <?php if ($order['discount'] > 0): ?>
            <div class="summary-row" style="color:#27AE60;">
                <span>
                    Discount
                    <?php if ($order['coupon_code']): ?>
                        (<?php echo htmlspecialchars($order['coupon_code']); ?>)
                    <?php endif; ?>
                </span>
                <span>−<?php echo number_format($order['discount'], 2); ?> L</span>
            </div>
            <?php endif; ?>

            <div class="summary-row">
                <span>Shipping</span>
                <span class="summary-free">Free</span>
            </div>

            <div class="summary-divider"></div>

            <div class="summary-row summary-total">
                <span>Total Paid</span>
                <span><?php echo number_format($order['total'], 2); ?> L</span>
            </div>

            <!-- Statusi i porosisë -->
            <div style="margin-top:20px; padding:14px 20px;
                        background:rgba(91,175,201,0.08);
                        border-radius:12px; text-align:center;">
                <p style="font-size:12px; color:var(--ink-soft);
                           letter-spacing:0.1em; text-transform:uppercase;
                           margin-bottom:6px;">Order Status</p>
                <span class="order-status order-status--<?php echo $order['status']; ?>">
                    <?php echo ucfirst($order['status']); ?>
                </span>
            </div>

            <div class="confirm-actions">
                <?php if (isLoggedIn()): ?>
                <a href="/cleare/pages/profile.php#orders" class="btn-primary btn-full"
                   style="margin-top:20px;">
                    View My Orders
                </a>
                <?php endif; ?>
                <a href="/cleare/pages/shop.php" class="btn-outline btn-full"
                   style="margin-top:12px; justify-content:center;">
                    Continue Shopping
                </a>
            </div>

        </div>

    </div>

</section>

<?php include('../includes/footer.php'); ?>
<?php include(__DIR__ . '/../includes/nav-js.php'); ?>

</body>
</html>