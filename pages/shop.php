<?php
$page_title = "Shop — Clearè";

require_once __DIR__ . '/../includes/products_db.php';

$selected_category = $_GET['cat']    ?? '';
$search_query      = trim($_GET['search'] ?? '');

$selected_sort       = $_GET['sort'] ?? '';
$selected_size       = $_GET['size'] ?? '';
$selected_popularity = $_GET['popularity'] ?? '';

$filtered_products = getProducts($selected_category, $search_query, $selected_sort, $selected_size, $selected_popularity);
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include(__DIR__ . '/../includes/nav.php'); ?>

<section class="shop-page">

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

    <!-- ============================================================
         FILTERS
         Category buttons + sort/size/popularity dropdowns in ONE row
         ============================================================ -->
    <div class="shop-filters">

        <!-- Category pills -->
        <a href="shop.php"
           class="filter-btn <?php echo $selected_category === '' ? 'active' : ''; ?>">All</a>
        <a href="shop.php?cat=skincare"
           class="filter-btn <?php echo $selected_category === 'skincare' ? 'active' : ''; ?>">Skincare</a>
        <a href="shop.php?cat=makeup"
           class="filter-btn <?php echo $selected_category === 'makeup' ? 'active' : ''; ?>">Makeup</a>
        <a href="shop.php?cat=hair"
           class="filter-btn <?php echo $selected_category === 'hair' ? 'active' : ''; ?>">Hair</a>
        <a href="shop.php?cat=body"
           class="filter-btn <?php echo $selected_category === 'body' ? 'active' : ''; ?>">Body</a>

        <!-- Separator -->
        <div class="filter-sep"></div>

        <!-- Dropdown form -->
        <form method="GET" action="shop.php" class="shop-dropdowns-form">

            <input type="hidden" name="cat"    value="<?php echo htmlspecialchars($selected_category); ?>">
            <?php if ($search_query !== ''): ?>
            <input type="hidden" name="search" value="<?php echo htmlspecialchars($search_query); ?>">
            <?php endif; ?>
            <input type="hidden" name="sort"       id="sortInput"       value="<?php echo htmlspecialchars($selected_sort); ?>">
            <input type="hidden" name="size"       id="sizeInput"       value="<?php echo htmlspecialchars($selected_size); ?>">
            <input type="hidden" name="popularity" id="popularityInput" value="<?php echo htmlspecialchars($selected_popularity); ?>">

            <!-- Sort -->
            <div class="custom-select" id="csSort">
                <button type="button" class="custom-select-btn" aria-haspopup="listbox">
                    <span class="cs-label">
                        <?php
                            if ($selected_sort === 'price_high_low') echo 'High → Low';
                            elseif ($selected_sort === 'price_low_high') echo 'Low → High';
                            else echo 'Sort by price';
                        ?>
                    </span>
                    <svg class="cs-chevron" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 4L6 8L10 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="custom-options" role="listbox">
                    <div class="custom-option <?php echo $selected_sort === 'price_high_low' ? 'selected' : ''; ?>"
                         data-target="sortInput" data-value="price_high_low" role="option">
                        <span class="co-check">✓</span> High → Low
                    </div>
                    <div class="custom-option <?php echo $selected_sort === 'price_low_high' ? 'selected' : ''; ?>"
                         data-target="sortInput" data-value="price_low_high" role="option">
                        <span class="co-check">✓</span> Low → High
                    </div>
                </div>
            </div>

            <!-- Size -->
            <div class="custom-select" id="csSize">
                <button type="button" class="custom-select-btn" aria-haspopup="listbox">
                    <span class="cs-label">
                        <?php
                            if ($selected_size === '5-30')   echo '5ml – 30ml';
                            elseif ($selected_size === '30-50')  echo '30ml – 50ml';
                            elseif ($selected_size === '50-100') echo '50ml – 100ml';
                            elseif ($selected_size === '100+')   echo '100ml+';
                            else echo 'Size';
                        ?>
                    </span>
                    <svg class="cs-chevron" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 4L6 8L10 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="custom-options" role="listbox">
                    <div class="custom-option <?php echo $selected_size === '5-30'   ? 'selected' : ''; ?>"
                         data-target="sizeInput" data-value="5-30"   role="option"><span class="co-check">✓</span> 5ml – 30ml</div>
                    <div class="custom-option <?php echo $selected_size === '30-50'  ? 'selected' : ''; ?>"
                         data-target="sizeInput" data-value="30-50"  role="option"><span class="co-check">✓</span> 30ml – 50ml</div>
                    <div class="custom-option <?php echo $selected_size === '50-100' ? 'selected' : ''; ?>"
                         data-target="sizeInput" data-value="50-100" role="option"><span class="co-check">✓</span> 50ml – 100ml</div>
                    <div class="custom-option <?php echo $selected_size === '100+'   ? 'selected' : ''; ?>"
                         data-target="sizeInput" data-value="100+"   role="option"><span class="co-check">✓</span> 100ml+</div>
                </div>
            </div>

            <!-- Popularity -->
            <div class="custom-select" id="csPopularity">
                <button type="button" class="custom-select-btn" aria-haspopup="listbox">
                    <span class="cs-label">
                        <?php echo $selected_popularity === 'most_bought' ? 'Most bought' : 'Popularity'; ?>
                    </span>
                    <svg class="cs-chevron" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 4L6 8L10 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="custom-options" role="listbox">
                    <div class="custom-option <?php echo $selected_popularity === 'most_bought' ? 'selected' : ''; ?>"
                         data-target="popularityInput" data-value="most_bought" role="option">
                        <span class="co-check">✓</span> Most bought
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <button type="submit" class="shop-filter-apply">Apply</button>

            <a href="shop.php<?php echo $selected_category ? '?cat='.urlencode($selected_category) : ''; ?>"
               class="filter-btn filter-btn--reset">Reset</a>

        </form>
    </div>

    <!-- Products grid -->
    <div class="products-grid shop-products-grid">

        <?php if (count($filtered_products) > 0): ?>

            <?php foreach ($filtered_products as $product): ?>
                <a href="product.php?id=<?php echo $product['id']; ?>" class="product-card">
                    <div class="product-img product-img-real">
                        <img src="../assets/images/<?php echo htmlspecialchars($product['image']); ?>"
                             alt="<?php echo htmlspecialchars($product['name']); ?>">
                    </div>
                    <div class="product-body">
                        <div class="product-cat-tag"><?php echo ucfirst($product['type']); ?></div>
                        <div class="product-name"><?php echo htmlspecialchars($product['name']); ?></div>
                        <div class="product-footer">
                            <span class="product-price"><?php echo number_format($product['price'], 2); ?> L</span>
                            <form action="/cleare/actions/add-to-cart.php" method="POST" style="margin:0">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <input type="hidden" name="quantity"   value="1">
                                <input type="hidden" name="redirect"   value="/cleare/pages/shop.php<?php echo isset($_GET['cat']) ? '?cat='.$_GET['cat'] : ''; ?>">
                                <button type="submit" class="btn-add">+</button>
                            </form>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>

        <?php else: ?>
            <p class="shop-no-results">No products found in this category.</p>
        <?php endif; ?>

    </div>

</section>

<?php include('../includes/footer.php'); ?>
<?php include(__DIR__ . '/../includes/nav-js.php'); ?>

<script>
/* ── Custom select dropdowns ── */
(function () {
    const selects = document.querySelectorAll('.custom-select');

    selects.forEach(function (cs) {
        const btn  = cs.querySelector('.custom-select-btn');
        const opts = cs.querySelector('.custom-options');

        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            const isOpen = cs.classList.contains('open');
            // Close all
            selects.forEach(s => s.classList.remove('open'));
            if (!isOpen) cs.classList.add('open');
        });

        cs.querySelectorAll('.custom-option').forEach(function (opt) {
            opt.addEventListener('click', function () {
                const inputId = this.dataset.target;
                const value   = this.dataset.value;
                const label   = this.textContent.trim();

                document.getElementById(inputId).value = value;
                btn.querySelector('.cs-label').textContent = label;

                // Mark selected
                cs.querySelectorAll('.custom-option').forEach(o => o.classList.remove('selected'));
                this.classList.add('selected');

                cs.classList.remove('open');
            });
        });
    });

    document.addEventListener('click', function () {
        selects.forEach(s => s.classList.remove('open'));
    });
})();
</script>

</body>
</html>