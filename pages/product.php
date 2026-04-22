<?php
require_once __DIR__ . '/../includes/products_db.php';

$product_id      = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$selected_product = getProductById($product_id);

if (!$selected_product) {
    echo "<p>Product not found.</p>";
    exit;
}

$page_title = $selected_product['name'] . " — Clearè";
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>

    <!-- Fontet nga Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&display=swap" rel="stylesheet">

    <!-- Stilet kryesore të projektit -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<!-- ============================================================
     NAVIGIMI
     ============================================================ -->
<?php include(__DIR__ . '/../includes/nav.php'); ?>



<!-- ============================================================
     DETAJET E PRODUKTIT
     ============================================================ -->
<section class="product-page">

    <div class="product-details-card">

        <!-- Kolona e majtë: imazhi i produktit -->
        <div class="product-details-image">
            <img
                src="../assets/images/<?php echo $selected_product['image']; ?>"
                alt="<?php echo $selected_product['name']; ?>"
            >
        </div>

        <!-- Kolona e djathtë: informacioni dhe butonat -->
        <div class="product-details-info">

            <!-- Kategoria (p.sh. "SPF" ose "Skincare") -->
            <div class="section-eyebrow"><?php echo ucfirst($selected_product['type']); ?></div>

            <!-- Emri i produktit -->
            <h1 class="product-details-title"><?php echo $selected_product['name']; ?></h1>

            <!-- Çmimi -->
            <div class="product-details-price"><?php echo $selected_product['price']; ?></div>

            <!-- Përshkrimi -->
            <p class="product-details-desc"><?php echo $selected_product['description']; ?></p>

            <!-- Butonat e veprimit -->
            <div class="product-details-actions">
                <button class="btn-primary">Add to Cart</button>
                <a href="shop.php" class="btn-outline">Back to Shop</a>
            </div>

        </div><!-- .product-details-info -->

    </div><!-- .product-details-card -->

</section><!-- .product-page -->


<!-- ============================================================
     FOOTER
     ============================================================ -->
<?php include('../includes/footer.php'); ?>


<!-- ============================================================
     JAVASCRIPT — Menu mobile
     ============================================================ -->
<?php include(__DIR__ . '/../includes/nav-js.php'); ?>

</body>
</html>
