<?php
session_start();
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/auth.php';
requireAdmin();

$success = '';
$errors  = [];

// Toggle active/inactive
if (isset($_GET['toggle'])) {
    $id   = (int) $_GET['toggle'];
    $pdo->prepare("
        UPDATE coupons SET active = 1 - active WHERE id = ?
    ")->execute([$id]);
    header('Location: coupons.php');
    exit;
}

// ADD kupon të ri
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code           = strtoupper(trim($_POST['code']           ?? ''));
    $type           = trim($_POST['type']                      ?? 'percent');
    $applies_to     = trim($_POST['applies_to']               ?? 'all');
    $discount_value = (float) ($_POST['discount_value']        ?? 0);
    $expires_at     = trim($_POST['expires_at']               ?? '');

    if ($code === '')          $errors[] = 'Code is required.';
    if ($discount_value <= 0)  $errors[] = 'Discount value must be greater than 0.';

    if (empty($errors)) {
        try {
            $pdo->prepare("
                INSERT INTO coupons
                    (code, type, applies_to, discount_value, active, expires_at)
                VALUES (?, ?, ?, ?, 1, ?)
            ")->execute([
                $code, $type, $applies_to, $discount_value,
                $expires_at !== '' ? $expires_at : null
            ]);
            $success = 'Coupon added successfully.';
        } catch (Exception $e) {
            $errors[] = 'Coupon code already exists.';
        }
    }
}

$coupons = $pdo->query("
    SELECT c.*, 
           COUNT(cu.id) AS usage_count
    FROM coupons c
    LEFT JOIN coupon_usage cu ON c.id = cu.coupon_id
    GROUP BY c.id
    ORDER BY c.id DESC
")->fetchAll();

$page_title = "Coupons — Admin";
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
        <h1 class="admin-page-title">Coupons</h1>
    </div>

    <?php if ($success): ?>
        <div class="admin-alert admin-alert--success"><?php echo $success; ?></div>
    <?php endif; ?>
    <?php if (!empty($errors)): ?>
        <div class="admin-alert admin-alert--error">
            <?php foreach ($errors as $e): ?>
                <p>⚠️ <?php echo htmlspecialchars($e); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Forma shtimi -->
    <div class="admin-card" style="margin-bottom:32px;">
        <h2 class="admin-card-title">Add New Coupon</h2>
        <form method="POST" action="coupons.php">
            <div class="admin-form-grid">
                <div class="form-group">
                    <label class="form-label">Code</label>
                    <input type="text" name="code" class="form-input"
                           placeholder="SUMMER30" style="text-transform:uppercase;">
                </div>
                <div class="form-group">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-input">
                        <option value="percent">Percent (%)</option>
                        <option value="fixed">Fixed (L)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Applies To</label>
                    <select name="applies_to" class="form-input">
                        <option value="all">All products</option>
                        <option value="skincare">Skincare</option>
                        <option value="makeup">Makeup</option>
                        <option value="hair">Hair</option>
                        <option value="body">Body</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Discount Value</label>
                    <input type="number" name="discount_value" step="0.01"
                           class="form-input" placeholder="10">
                </div>
                <div class="form-group">
                    <label class="form-label">Expires At</label>
                    <input type="date" name="expires_at" class="form-input">
                </div>
            </div>
            <button type="submit" class="btn-primary"
                    style="margin-top:20px;padding:12px 28px;">
                Add Coupon
            </button>
        </form>
    </div>

    <!-- Lista -->
    <div class="admin-card">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Type</th>
                    <th>Applies To</th>
                    <th>Discount</th>
                    <th>Expires</th>
                    <th>Used</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($coupons as $c): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($c['code']); ?></strong></td>
                    <td><?php echo ucfirst($c['type']); ?></td>
                    <td><?php echo ucfirst($c['applies_to']); ?></td>
                    <td>
                        <?php echo $c['type'] === 'percent'
                            ? $c['discount_value'] . '%'
                            : number_format($c['discount_value'], 2) . ' L'; ?>
                    </td>
                    <td>
                        <?php echo $c['expires_at']
                            ? date('d M Y', strtotime($c['expires_at']))
                            : '—'; ?>
                    </td>
                    <td><?php echo $c['usage_count']; ?> time(s)</td>
                    <td>
                        <span class="order-status"
                              style="background:<?php echo $c['active'] ? 'rgba(46,204,113,0.1)' : 'rgba(231,76,60,0.1)'; ?>;
                                     color:<?php echo $c['active'] ? '#27AE60' : '#E74C3C'; ?>;">
                            <?php echo $c['active'] ? 'Active' : 'Inactive'; ?>
                        </span>
                    </td>
                    <td>
                        <a href="?toggle=<?php echo $c['id']; ?>"
                           class="<?php echo $c['active'] ? 'admin-btn-delete' : 'admin-btn-edit'; ?>">
                            <?php echo $c['active'] ? 'Deactivate' : 'Activate'; ?>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

</body>
</html>