<?php
$page_title = "Shop — Clearè";

require_once __DIR__ . '/../includes/products_db.php';

$selected_category = $_GET['cat']    ?? '';
$search_query      = trim($_GET['search'] ?? '');

$filtered_products = getProducts($selected_category, $search_query);
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
     SEKSIONI KRYESOR I SHOP-IT
     ============================================================ -->
<section class="shop-page">

    <!-- Titulli i faqes -->
    <div class="shop-header">
  <div class="section-eyebrow">Shop</div>
  <h1 class="section-title">
    <?php if ($search_query !== ''): ?>
      Results for <em>"<?php echo htmlspecialchars($search_query); ?>"</em>
    <?php else: ?>
      The Clearè <em>Collection</em>
    <?php endif; ?>
  </h1>
  <p class="shop-subtitle">
    <?php if ($search_query !== ''): ?>
      <?php echo count($filtered_products); ?> product(s) found
      — <a href="shop.php" style="color:var(--sky-deep);">Clear search</a>
    <?php else: ?>
      Find your perfect skincare and beauty routine.
    <?php endif; ?>
  </p>
</div>

    <!-- Filtrat e kategorive -->
    <div class="shop-filters">
    <a href="shop.php"
       class="filter-btn <?php echo $selected_category === '' ? 'active' : ''; ?>">
        All
    </a>
    <a href="shop.php?cat=skincare"
       class="filter-btn <?php echo $selected_category === 'skincare' ? 'active' : ''; ?>">
        Skincare
    </a>
    <a href="shop.php?cat=makeup"
       class="filter-btn <?php echo $selected_category === 'makeup' ? 'active' : ''; ?>">
        Makeup
    </a>
    <a href="shop.php?cat=hair"
       class="filter-btn <?php echo $selected_category === 'hair' ? 'active' : ''; ?>">
        Hair
    </a>
    <a href="shop.php?cat=body"
       class="filter-btn <?php echo $selected_category === 'body' ? 'active' : ''; ?>">
        Body
    </a>
</div>

    <!-- Grila e produkteve -->
    <div class="products-grid shop-products-grid">

        <?php if (count($filtered_products) > 0): ?>

           <?php foreach ($filtered_products as $product): ?>
    <a href="product.php?id=<?php echo $product['id']; ?>" class="product-card">
        <div class="product-img product-img-real">
            <img src="../assets/images/<?php echo htmlspecialchars($product['image']); ?>"
                 alt="<?php echo htmlspecialchars($product['name']); ?>">
        </div>
        <div class="product-body">
            <div class="product-cat-tag">
                <?php echo ucfirst($product['type']); ?>
            </div>
            <div class="product-name">
                <?php echo htmlspecialchars($product['name']); ?>
            </div>
            <div class="product-footer">
                <span class="product-price">
                    <?php echo number_format($product['price'], 2); ?> L
                </span>
                <button class="btn-add" onclick="event.preventDefault()">+</button>
            </div>
        </div>
    </a>
<?php endforeach; ?>

        <?php else: ?>

            <!-- Mesazhi nëse nuk ka produkte në këtë kategori -->
            <p>Nuk u gjetën produkte në këtë kategori.</p>

        <?php endif; ?>

    </div><!-- .products-grid -->

</section><!-- .shop-page -->


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
