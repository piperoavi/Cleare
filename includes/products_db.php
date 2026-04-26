<?php

require_once __DIR__ . '/db.php';

function getProducts($category = '', $search = '') {
    global $pdo;

    $sql    = "SELECT * FROM products WHERE 1=1";
    $params = [];

    if ($category !== '') {
        $sql      .= " AND type = ?";
        $params[]  = $category;
    }

    if ($search !== '') {
        $sql      .= " AND (name LIKE ? OR description LIKE ?)";
        $params[]  = "%$search%";
        $params[]  = "%$search%";
    }

    $sql .= " ORDER BY id ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function getProductById($id) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    return $stmt->fetch();
}
function getFeaturedProducts($limit = 4) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM products ORDER BY id DESC LIMIT ?");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}