<?php
session_start();
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/auth.php';
requireAdmin();

$success = '';

// Ndrysho statusin
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $id     = (int) $_POST['order_id'];
    $status = $_POST['status'];
    $allowed = ['pending','processing','shipped','delivered','cancelled'];
    if (in_array($status, $allowed)) {
        $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?")
            ->execute([$status, $id]);
        $success = 'Order status updated.';
    }
}

// Merr porositë
$orders = $pdo->query("
    SELECT o.*, u.name AS user_name
    FROM orders o
    LEFT JOIN users u ON o.user_id = u.id
    ORDER BY o.created_at DESC
")->fetchAll();

$page_title = "Orders — Admin";
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
        <h1 class="admin-page-title">Orders</h1>
    </div>

    <?php if ($success): ?>
        <div class="admin-alert admin-alert--success"><?php echo $success; ?></div>
    <?php endif; ?>

    <div class="admin-card">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Update</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $o): ?>
                <tr>
                    <td>CLR-<?php echo str_pad($o['id'], 5, '0', STR_PAD_LEFT); ?></td>
                    <td>
                        <?php echo htmlspecialchars($o['shipping_name']); ?>
                        <?php if ($o['user_name']): ?>
                            <br><small style="color:var(--ink-soft);">
                                <?php echo htmlspecialchars($o['user_name']); ?>
                            </small>
                        <?php endif; ?>
                    </td>
                    <td><?php echo number_format($o['total'], 2); ?> L</td>
                    <td><?php echo ucfirst($o['payment_method']); ?></td>
                    <td><?php echo date('d M Y', strtotime($o['created_at'])); ?></td>
                    <td>
                        <span class="order-status order-status--<?php echo $o['status']; ?>">
                            <?php echo ucfirst($o['status']); ?>
                        </span>
                    </td>
                    <td>
                        <form method="POST" action="orders.php"
                              style="display:flex;gap:6px;align-items:center;">
                            <input type="hidden" name="order_id"
                                   value="<?php echo $o['id']; ?>">
                            <input type="hidden" name="update_status" value="1">
                            <select name="status" class="form-input"
                                    style="padding:6px 10px;font-size:12px;width:auto;">
                                <?php foreach (['pending','processing','shipped','delivered','cancelled'] as $s): ?>
                                <option value="<?php echo $s; ?>"
                                    <?php echo $o['status'] === $s ? 'selected' : ''; ?>>
                                    <?php echo ucfirst($s); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="admin-btn-edit">Save</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

</body>
</html>