<?php
session_start();
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/auth.php';
requireAdmin();

// Statistika
$total_orders   = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$total_products = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$total_users    = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_revenue  = $pdo->query("SELECT COALESCE(SUM(total),0) FROM orders WHERE payment_status='paid'")->fetchColumn();

// Porositë e fundit
$recent_orders = $pdo->query("
    SELECT o.id, o.shipping_name, o.total, o.status, o.created_at
    FROM orders o
    ORDER BY o.created_at DESC
    LIMIT 5
")->fetchAll();

// Produktet me stok të ulët
$low_stock = $pdo->query("
    SELECT id, name, stock FROM products
    WHERE stock <= 5
    ORDER BY stock ASC
    LIMIT 5
")->fetchAll();

$page_title = "Admin Dashboard — Clearè";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body class="admin-body">

<?php include __DIR__ . '/partials/sidebar.php'; ?>

<main class="admin-main">
    <div class="admin-topbar">
        <h1 class="admin-page-title">Dashboard</h1>
        <a href="/cleare/pages/logout.php" class="btn-logout">Log Out</a>
    </div>

    <!-- Stats -->
    <div class="admin-stats">
        <div class="stat-card">
            <div class="stat-icon">📦</div>
            <div class="stat-value"><?php echo $total_orders; ?></div>
            <div class="stat-label">Total Orders</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🧴</div>
            <div class="stat-value"><?php echo $total_products; ?></div>
            <div class="stat-label">Products</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-value"><?php echo $total_users; ?></div>
            <div class="stat-label">Customers</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">💰</div>
            <div class="stat-value"><?php echo number_format($total_revenue, 2); ?> L</div>
            <div class="stat-label">Revenue</div>
        </div>
    </div>

    <div class="admin-grid">

        <!-- Porositë e fundit -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h2>Recent Orders</h2>
                <a href="orders.php" class="admin-link">View all →</a>
            </div>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_orders as $o): ?>
                    <tr>
                        <td>CLR-<?php echo str_pad($o['id'], 5, '0', STR_PAD_LEFT); ?></td>
                        <td><?php echo htmlspecialchars($o['shipping_name']); ?></td>
                        <td><?php echo number_format($o['total'], 2); ?> L</td>
                        <td>
                            <span class="order-status order-status--<?php echo $o['status']; ?>">
                                <?php echo ucfirst($o['status']); ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Stok i ulët -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h2>Low Stock Alert</h2>
                <a href="products.php" class="admin-link">View all →</a>
            </div>
            <?php if (empty($low_stock)): ?>
                <p class="admin-empty">All products are well stocked.</p>
            <?php else: ?>
            <table class="admin-table">
                <thead>
                    <tr><th>Product</th><th>Stock</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($low_stock as $p): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($p['name']); ?></td>
                        <td>
                            <span style="color:<?php echo $p['stock'] == 0 ? '#E74C3C' : '#F39C12'; ?>; font-weight:500;">
                                <?php echo $p['stock']; ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>

    </div>
</main>

</body>
</html>