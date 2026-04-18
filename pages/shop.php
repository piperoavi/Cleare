<?php
/**
 * shop.php — Faqja e dyqanit
 * 
 * Shfaq të gjithë produktet ose vetëm ata të një kategorie,
 * bazuar në parametrin ?cat= në URL.
 * 
 * Shembuj:
 *   shop.php          → të gjitha produktet
 *   shop.php?cat=skincare → vetëm Skincare
 *   shop.php?cat=spf      → vetëm SPF
 */

$page_title = "Shop - Clearè";

// Ngarkojmë listën e produkteve nga fajlli i dedikuar
include("../includes/products.php");

// Lexojmë kategorinë nga URL (nëse nuk ka, vlera default është string bosh)
$selected_category = $_GET['cat'] ?? '';
$search_query      = trim($_GET['search'] ?? '');

$filtered_products = [];

foreach ($products as $product) {
    $matches_category = $selected_category === '' || $product['category'] === $selected_category;
    $matches_search   = $search_query === '' ||
                        stripos($product['name'], $search_query) !== false ||
                        stripos($product['description'], $search_query) !== false;

    if ($matches_category && $matches_search) {
        $filtered_products[] = $product;
    }
}
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

                <!-- Çdo kartë është link drejt faqes së produktit -->
                <a href="product.php?id=<?php echo $product['id']; ?>" class="product-card">

                    <!-- Imazhi i produktit -->
                    <div class="product-img product-img-real">
                        <img
                            src="../assets/images/<?php echo $product['image']; ?>"
                            alt="<?php echo $product['name']; ?>"
                        >
                    </div>

                    <!-- Informacioni i produktit -->
                    <div class="product-body">
                        <div class="product-cat-tag"><?php echo $product['category_label']; ?></div>
                        <div class="product-name"><?php echo $product['name']; ?></div>

                        <div class="product-footer">
                            <span class="product-price"><?php echo $product['price']; ?></span>

                            <!--
                                event.preventDefault() parandalon navigimin
                                kur klikohet butoni "+", pa kaluar te faqja e produktit.
                                Logjika e shportës implementohet me JS ose session.
                            -->
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
