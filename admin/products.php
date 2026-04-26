<?php
session_start();
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/auth.php';
requireAdmin();

$success = '';
$errors  = [];

// DELETE
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $pdo->prepare("DELETE FROM products WHERE id = ?")->execute([$id]);
    $success = 'Product deleted successfully.';
}

// ADD / EDIT
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id          = (int) ($_POST['id'] ?? 0);
    $name        = trim($_POST['name']        ?? '');
    $category_id = (int) ($_POST['category_id'] ?? 0);
    $type        = trim($_POST['type']        ?? '');
    $price       = (float) ($_POST['price']   ?? 0);
    $stock       = (int) ($_POST['stock']     ?? 0);
    $size        = trim($_POST['size']        ?? '');
    $description = trim($_POST['description'] ?? '');
    $image       = trim($_POST['image']       ?? '');

    // Gjenero slug
    $slug = strtolower(preg_replace('/[^a-z0-9]+/', '-', $name));

    if ($name === '')        $errors[] = 'Name is required.';
    if ($price <= 0)         $errors[] = 'Price must be greater than 0.';
    if ($category_id === 0)  $errors[] = 'Category is required.';

    if (empty($errors)) {
        if ($id > 0) {
            // UPDATE
            $pdo->prepare("
                UPDATE products
                SET name=?, slug=?, category_id=?, type=?, price=?,
                    stock=?, size=?, description=?, image=?
                WHERE id=?
            ")->execute([$name, $slug, $category_id, $type, $price,
                         $stock, $size, $description, $image, $id]);
            $success = 'Product updated successfully.';
        } else {
            // INSERT
            $pdo->prepare("
                INSERT INTO products
                    (name, slug, category_id, type, price, stock, size, description, image)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ")->execute([$name, $slug, $category_id, $type, $price,
                         $stock, $size, $description, $image]);
            $success = 'Product added successfully.';
        }
    }
}

// Merr të dhënat
$products   = $pdo->query("
    SELECT p.*, c.name AS cat_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    ORDER BY p.id DESC
")->fetchAll();

$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

// Nëse editojmë
$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([(int) $_GET['edit']]);
    $edit = $stmt->fetch();
}

$page_title = "Products — Admin";
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
        <h1 class="admin-page-title">Products</h1>
        <a href="?add=1" class="btn-primary" style="padding:10px 24px;font-size:13px;">
            + Add Product
        </a>
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

    <!-- Forma Add/Edit -->
    <?php if (isset($_GET['add']) || $edit): ?>
    <div class="admin-card" style="margin-bottom:32px;">
        <h2 class="admin-card-title">
            <?php echo $edit ? 'Edit Product' : 'Add New Product'; ?>
        </h2>
        <form method="POST" action="products.php">
            <input type="hidden" name="id" value="<?php echo $edit['id'] ?? 0; ?>">
            <div class="admin-form-grid">
                <div class="form-group">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-input"
                           value="<?php echo htmlspecialchars($edit['name'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-input">
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"
                            <?php echo ($edit['category_id'] ?? 0) == $cat['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-input">
                        <?php foreach (['skincare','makeup','hair','body'] as $t): ?>
                        <option value="<?php echo $t; ?>"
                            <?php echo ($edit['type'] ?? '') === $t ? 'selected' : ''; ?>>
                            <?php echo ucfirst($t); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Price (L)</label>
                    <input type="number" name="price" step="0.01" class="form-input"
                           value="<?php echo $edit['price'] ?? ''; ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Stock</label>
                    <input type="number" name="stock" class="form-input"
                           value="<?php echo $edit['stock'] ?? 0; ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Size</label>
                    <input type="text" name="size" class="form-input"
                           value="<?php echo htmlspecialchars($edit['size'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Image filename</label>
                    <input type="text" name="image" class="form-input"
                           placeholder="prod-name.jpg"
                           value="<?php echo htmlspecialchars($edit['image'] ?? ''); ?>">
                </div>
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-input" rows="3"
                              style="resize:vertical;"><?php echo htmlspecialchars($edit['description'] ?? ''); ?></textarea>
                </div>
            </div>
            <div style="display:flex;gap:12px;margin-top:20px;">
                <button type="submit" class="btn-primary" style="padding:12px 28px;">
                    <?php echo $edit ? 'Update Product' : 'Add Product'; ?>
                </button>
                <a href="products.php" class="btn-outline" style="padding:12px 28px;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <!-- Lista e produkteve -->
    <div class="admin-card">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                <tr>
                    <td><?php echo $p['id']; ?></td>
                    <td><?php echo htmlspecialchars($p['name']); ?></td>
                    <td><?php echo htmlspecialchars($p['cat_name']); ?></td>
                    <td><?php echo number_format($p['price'], 2); ?> L</td>
                    <td>
                        <span style="color:<?php echo $p['stock'] == 0 ? '#E74C3C' : ($p['stock'] <= 5 ? '#F39C12' : '#27AE60'); ?>; font-weight:500;">
                            <?php echo $p['stock']; ?>
                        </span>
                    </td>
                    <td>
                        <a href="?edit=<?php echo $p['id']; ?>" class="admin-btn-edit">Edit</a>
                        <a href="?delete=<?php echo $p['id']; ?>"
                           class="admin-btn-delete"
                           onclick="return confirm('Delete this product?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

</body>
</html>