<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

$order_id = (int) ($_GET['order_id'] ?? 0);

// Fshij porosinë e pa-paguar nëse user-i anuloi
if ($order_id) {
    $pdo->prepare("DELETE FROM orders WHERE id = ? AND payment_status = 'unpaid'")
        ->execute([$order_id]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Cancelled — Clearè</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include(__DIR__ . '/../includes/nav.php'); ?>

<section class="checkout-page" style="text-align:center; padding: 80px 20px;">
    <div class="section-eyebrow">Payment Cancelled</div>
    <h1 class="section-title">Order <em>not placed</em></h1>
    <p style="color:var(--ink-soft); margin: 16px 0 32px;">
        Your payment was cancelled. Your cart is still intact.
    </p>
    <a href="checkout.php" class="btn-primary">Try Again →</a>
</section>

<?php include('../includes/footer.php'); ?>
</body>
</html>