<?php
session_start();
require_once __DIR__ . '/../includes/cart_functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = (int) ($_POST['product_id'] ?? 0);
    $quantity   = (int) ($_POST['quantity']   ?? 1);
    $redirect   = $_POST['redirect'] ?? '/cleare/pages/shop.php';

    if ($product_id > 0) {
        cart_add($product_id, $quantity);
    }

    header('Location: ' . $redirect);
    exit;
}

header('Location: /cleare/pages/shop.php');
exit;