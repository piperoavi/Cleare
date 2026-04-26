<?php
/**
 * cart_functions.php
 * Funksionet e shportës — session-based për guest, DB për customer.
 */

function cart_init() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
}

function cart_add($product_id, $quantity = 1) {
    cart_init();
    $product_id = (int) $product_id;
    $quantity   = max(1, (int) $quantity);

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

function cart_remove($product_id) {
    cart_init();
    unset($_SESSION['cart'][(int) $product_id]);
}

function cart_update($product_id, $quantity) {
    cart_init();
    $quantity = (int) $quantity;
    if ($quantity <= 0) {
        cart_remove($product_id);
    } else {
        $_SESSION['cart'][(int) $product_id] = $quantity;
    }
}

function cart_count() {
    cart_init();
    return array_sum($_SESSION['cart']);
}

function cart_clear() {
    $_SESSION['cart'] = [];
}

function cart_get_items($pdo) {
    cart_init();
    if (empty($_SESSION['cart'])) return [];

    $ids          = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $stmt = $pdo->prepare("
        SELECT id, name, price, image, type, stock
        FROM products
        WHERE id IN ($placeholders)
    ");
    $stmt->execute($ids);
    $products = $stmt->fetchAll();

    $items = [];
    foreach ($products as $p) {
        $qty     = $_SESSION['cart'][$p['id']];
        $items[] = [
            'id'       => $p['id'],
            'name'     => $p['name'],
            'price'    => $p['price'],
            'image'    => $p['image'],
            'type'     => $p['type'],
            'stock'    => $p['stock'],
            'quantity' => $qty,
            'subtotal' => $p['price'] * $qty,
        ];
    }
    return $items;
}

function cart_subtotal($items) {
    return array_sum(array_column($items, 'subtotal'));
}