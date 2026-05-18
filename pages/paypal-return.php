<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/paypal.php';

$order_id        = (int) ($_GET['order_id']        ?? 0);
$paypal_order_id = $_GET['token']                  ?? '';

if (!$order_id || !$paypal_order_id) {
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

// Capture pagesa te PayPal
//$success = paypal_capture_order($paypal_order_id);

// DEBUG — fshi pas testimit
$token = paypal_get_access_token();
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL            => PAYPAL_BASE_URL . '/v2/checkout/orders/' . $paypal_order_id . '/capture',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token,
    ],
    CURLOPT_POSTFIELDS     => '{}',
    CURLOPT_SSL_VERIFYPEER => false,
]);
$response = curl_exec($ch);
curl_close($ch);

file_put_contents(__DIR__ . '/../paypal_debug.txt', $response);
$data    = json_decode($response, true);
$success = ($data['status'] ?? '') === 'COMPLETED';

if ($success) {
    $pdo->prepare("
        UPDATE orders SET payment_status = 'paid', status = 'processing'
        WHERE id = ?
    ")->execute([$order_id]);
}

header('Location: order-confirm.php?order_id=' . $order_id);
exit;