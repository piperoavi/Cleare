<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/stripe.php';

$order_id   = (int) ($_GET['order_id']   ?? 0);
$session_id = $_GET['session_id'] ?? '';

if (!$order_id || !$session_id) {
    header('Location: shop.php');
    exit;
}

// Verifiko që porosia ekziston dhe është unpaid
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND payment_status = 'unpaid'");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    header('Location: order-confirm.php?order_id=' . $order_id);
    exit;
}

// Verifiko pagesën te Stripe
$success = stripe_verify_session($session_id);

if ($success) {
    $pdo->prepare("
        UPDATE orders SET payment_status = 'paid', status = 'processing'
        WHERE id = ?
    ")->execute([$order_id]);
}

header('Location: order-confirm.php?order_id=' . $order_id);
exit;