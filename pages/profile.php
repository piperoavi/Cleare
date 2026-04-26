<?php
session_start();
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
require_once __DIR__ . '/../includes/db.php';

$user_id = $_SESSION['user_id'];
$success_message = '';
$errors = [];

// Merr të dhënat e userit nga DB
$stmt = $pdo->prepare("
    SELECT u.*, r.name AS role_name 
    FROM users u 
    JOIN roles r ON u.role_id = r.id 
    WHERE u.id = ?
");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Merr historikun e porosive
$stmt = $pdo->prepare("
    SELECT o.*, 
           COUNT(oi.id) AS item_count
    FROM orders o
    LEFT JOIN order_items oi ON o.id = oi.order_id
    WHERE o.user_id = ?
    GROUP BY o.id
    ORDER BY o.created_at DESC
");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();

// Merr pikët totale
$stmt = $pdo->prepare("
    SELECT COALESCE(SUM(points), 0) AS total_points 
    FROM points_log 
    WHERE user_id = ?
");
$stmt->execute([$user_id]);
$points_data = $stmt->fetch();

// Merr kuponët e përdorur
$stmt = $pdo->prepare("
    SELECT c.code, c.discount_value, c.type, cu.used_at
    FROM coupon_usage cu
    JOIN coupons c ON cu.coupon_id = c.id
    WHERE cu.user_id = ?
    ORDER BY cu.used_at DESC
");
$stmt->execute([$user_id]);
$used_coupons = $stmt->fetchAll();

// UPDATE i të dhënave personale
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name    = trim($_POST['name']    ?? '');
    $phone   = trim($_POST['phone']   ?? '');
    $address = trim($_POST['address'] ?? '');
    $city    = trim($_POST['city']    ?? '');

    if ($name === '') {
        $errors[] = 'Full name is required.';
    }

    if (empty($errors)) {
        $gender = trim($_POST['gender'] ?? '');
        $gender = in_array($gender, ['male', 'female']) ? $gender : null;        
        $birth_date = trim($_POST['birth_date'] ?? '');
        $birth_date = $birth_date !== '' ? $birth_date : null;

        $stmt = $pdo->prepare("
            UPDATE users SET name = ?, phone = ?, address = ?, city = ?, gender = ?, birth_date = ?
            WHERE id = ?
        ");
        $stmt->execute([$name, $phone, $address, $city, $gender, $birth_date, $user_id]);

        $user['gender']     = $gender;
        $user['birth_date'] = $birth_date;
        $_SESSION['user_name'] = $name;
        $success_message = 'Profile updated successfully.';
        // Rifresko të dhënat
        $user['name']    = $name;
        $user['phone']   = $phone;
        $user['address'] = $address;
        $user['city']    = $city;
    }
}

$page_title = "My Profile — Clearè";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include(__DIR__ . '/../includes/nav.php'); ?>

<section class="profile-page">
    <div class="profile-container">

        <!-- SIDEBAR -->
        <aside class="profile-sidebar">
            <div class="profile-avatar">
                <span><?php echo strtoupper(substr($user['name'], 0, 1)); ?></span>
            </div>
            <h3 class="profile-name"><?php echo htmlspecialchars($user['name']); ?></h3>
            <p class="profile-role"><?php echo ucfirst($user['role_name']); ?></p>
            <div class="profile-points-badge">
                ⭐ <?php echo $user['points']; ?> points
            </div>
            <nav class="profile-nav">
                <a href="#personal" class="profile-nav-link active">Personal Info</a>
                <a href="#orders"   class="profile-nav-link">My Orders</a>
                <a href="#coupons"  class="profile-nav-link">Coupons Used</a>
                <a href="#points"   class="profile-nav-link">Points History</a>
            </nav>
            <a href="/cleare/pages/logout.php" class="btn-logout">Log Out</a>
        </aside>

        <!-- MAIN CONTENT -->
        <div class="profile-content">

            <!-- PERSONAL INFO -->
            <section id="personal" class="profile-section">
                <h2 class="profile-section-title">Personal Information</h2>

                <?php if ($success_message): ?>
                    <div class="auth-errors" style="background:rgba(111,174,142,0.1); border-color:rgba(111,174,142,0.4);">
                        <p style="color:#6FAE8E;">✓ <?php echo $success_message; ?></p>
                    </div>
                <?php endif; ?>

                <?php if (!empty($errors)): ?>
                    <div class="auth-errors">
                        <?php foreach ($errors as $error): ?>
                            <p>⚠️ <?php echo htmlspecialchars($error); ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form action="profile.php" method="POST" class="auth-form">
                    <input type="hidden" name="update_profile" value="1">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-input"
                                   value="<?php echo htmlspecialchars($user['name']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-input"
                                   value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                            <small style="color:var(--ink-soft);font-size:11px;">Email cannot be changed.</small>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-input"
                                   value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">City</label>
                            <input type="text" name="city" class="form-input"
                                   value="<?php echo htmlspecialchars($user['city'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" class="form-input"
                               value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>">
                    </div>
                    <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-input">
                            <option value="">— Select —</option>
                            <option value="female" <?php echo ($user['gender'] ?? '') === 'female' ? 'selected' : ''; ?>>Female</option>
                            <option value="male"   <?php echo ($user['gender'] ?? '') === 'male'   ? 'selected' : ''; ?>>Male</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="birth_date" class="form-input"
                            value="<?php echo htmlspecialchars($user['birth_date'] ?? ''); ?>">
                    </div>
                </div>
                    <button type="submit" class="btn-primary">Save Changes</button>
                </form>
            </section>

            <!-- ORDERS -->
            <section id="orders" class="profile-section">
                <h2 class="profile-section-title">My Orders</h2>
                <?php if (empty($orders)): ?>
                    <p class="profile-empty">You haven't placed any orders yet.</p>
                <?php else: ?>
                    <div class="orders-list">
                        <?php foreach ($orders as $order): ?>
                        <div class="order-card">
                            <div class="order-card-header">
                                <span class="order-id">Order #CLR-<?php echo str_pad($order['id'], 5, '0', STR_PAD_LEFT); ?></span>
                                <span class="order-status order-status--<?php echo $order['status']; ?>">
                                    <?php echo ucfirst($order['status']); ?>
                                </span>
                            </div>
                            <div class="order-card-body">
                                <span><?php echo $order['item_count']; ?> item(s)</span>
                                <span><?php echo number_format($order['total'], 2); ?> L</span>
                                <span><?php echo date('d M Y', strtotime($order['created_at'])); ?></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>

            <!-- COUPONS -->
            <section id="coupons" class="profile-section">
                <h2 class="profile-section-title">Coupons Used</h2>
                <?php if (empty($used_coupons)): ?>
                    <p class="profile-empty">You haven't used any coupons yet.</p>
                <?php else: ?>
                    <div class="coupons-list">
                        <?php foreach ($used_coupons as $coupon): ?>
                        <div class="coupon-used-card">
                            <span class="coupon-code"><?php echo $coupon['code']; ?></span>
                            <span class="coupon-discount">
                                <?php echo $coupon['type'] === 'percent'
                                    ? $coupon['discount_value'] . '% off'
                                    : $coupon['discount_value'] . ' L off'; ?>
                            </span>
                            <span class="coupon-date">
                                <?php echo date('d M Y', strtotime($coupon['used_at'])); ?>
                            </span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>

            <!-- POINTS -->
            <section id="points" class="profile-section">
                <h2 class="profile-section-title">Points History</h2>
                <?php
                $stmt = $pdo->prepare("
                    SELECT * FROM points_log 
                    WHERE user_id = ? 
                    ORDER BY created_at DESC
                ");
                $stmt->execute([$user_id]);
                $points_history = $stmt->fetchAll();
                ?>
                <div class="points-total">
                    Total: <strong><?php echo $user['points']; ?> points</strong>
                </div>
                <?php if (empty($points_history)): ?>
                    <p class="profile-empty">No points activity yet.</p>
                <?php else: ?>
                    <div class="points-list">
                        <?php foreach ($points_history as $log): ?>
                        <div class="points-row">
                            <span><?php echo htmlspecialchars($log['reason']); ?></span>
                            <span class="points-amount <?php echo $log['points'] > 0 ? 'points-plus' : 'points-minus'; ?>">
                                <?php echo $log['points'] > 0 ? '+' : ''; ?><?php echo $log['points']; ?>
                            </span>
                            <span class="points-date">
                                <?php echo date('d M Y', strtotime($log['created_at'])); ?>
                            </span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>

        </div>
    </div>
</section>

<?php include('../includes/footer.php'); ?>
<?php include(__DIR__ . '/../includes/nav-js.php'); ?>

</body>
</html>