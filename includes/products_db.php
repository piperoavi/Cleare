<?php
/* ============================================================
   products_db.php — Clearè
   ============================================================ */

$host    = 'localhost';
$db      = 'Cleare';
$user    = 'root';
$pass    = '';
$charset = 'utf8mb4';

$dsn     = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}


/* ============================================================
   getProducts()
   ============================================================ */
function getProducts(
    string $category   = '',
    string $search     = '',
    string $sort       = '',
    string $size       = '',
    string $popularity = ''
): array {

    global $pdo;

    $sql = "
        SELECT
            p.*,
            COALESCE(SUM(oi.quantity), 0) AS total_sold
        FROM products p
        LEFT JOIN order_items oi ON oi.product_id = p.id
    ";

    $conditions = [];
    $params     = [];

    if ($category !== '') {
        $conditions[] = "p.type = :type";
        $params[':type'] = $category;
    }

    if ($search !== '') {
        $conditions[] = "(p.name LIKE :search OR p.description LIKE :search2)";
        $params[':search']  = '%' . $search . '%';
        $params[':search2'] = '%' . $search . '%';
    }

    if ($size !== '') {
        switch ($size) {
            case '5-30':
                $conditions[] = "CAST(p.size AS UNSIGNED) BETWEEN 5 AND 30";
                break;
            case '30-50':
                $conditions[] = "CAST(p.size AS UNSIGNED) BETWEEN 31 AND 50";
                break;
            case '50-100':
                $conditions[] = "CAST(p.size AS UNSIGNED) BETWEEN 51 AND 100";
                break;
            case '100+':
                $conditions[] = "CAST(p.size AS UNSIGNED) > 100";
                break;
        }
    }

    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    $sql .= " GROUP BY p.id";

    if ($popularity === 'most_bought') {
        $sql .= " ORDER BY total_sold DESC, p.name ASC";
    } elseif ($sort === 'price_high_low') {
        $sql .= " ORDER BY p.price DESC";
    } elseif ($sort === 'price_low_high') {
        $sql .= " ORDER BY p.price ASC";
    } else {
        $sql .= " ORDER BY p.id ASC";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll();
}


/* ============================================================
   getProductById()
   ============================================================ */
function getProductById(int $id): array|false
{
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    return $stmt->fetch();
}


/* ============================================================
   getFeaturedProducts()
   ============================================================ */
function getFeaturedProducts(int $limit = 4): array
{
    global $pdo;

    $sql = "
        SELECT
            p.*,
            COALESCE(SUM(oi.quantity), 0) AS total_sold
        FROM products p
        LEFT JOIN order_items oi ON oi.product_id = p.id
        GROUP BY p.id
        ORDER BY total_sold DESC, p.id DESC
        LIMIT :limit
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll();
}