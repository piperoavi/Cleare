<?php
$page_title = "Clearè — The clearest in sight.";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $page_title; ?></title>

  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- ============================================================
     NAVIGATION
     ============================================================ -->

<?php include(__DIR__ . '/includes/nav.php'); ?>
<!-- ============================================================
     HERO
     ============================================================ -->
<section class="hero">

  <!-- Decorative animated blobs in the background -->
  <div class="blob blob-1"></div>
  <div class="blob blob-2"></div>
  <div class="blob blob-3"></div>

  <!-- Left side: text content -->
  <div class="hero-content">
    <div class="hero-eyebrow">Clean Beauty</div>

    <h1 class="hero-title">
      The clearest,<br>
      <em>in sight.</em>
    </h1>

    <p class="hero-desc">
      Pure, carefully selected ingredients for every skin type.
      Simple routines. Real results.
    </p>

    <div class="hero-buttons">
      <a href="pages/shop.php" class="btn-primary">Shop Now</a>
      <a href="pages/shop.php?cat=skincare" class="btn-outline">Skincare →</a>
    </div>
  </div>

  <!-- Right side: floating product card -->
  <div class="hero-visual">
    <div class="hero-product-card">
      <span class="product-emoji">🧴</span>
      <div class="product-label">Bestseller</div>
      <div class="product-name-card">The Ordinary Serum</div>
      <div class="product-price-card">3,200 L</div>
      <a href="pages/product.php?id=5" class="btn-card">View Product</a>
    </div>

    <!-- Small floating badges -->
    <div class="float-badge float-badge-1">
      <div class="float-dot"></div>
      SPF Protection
    </div>
    <div class="float-badge float-badge-2">
      <div class="float-dot"></div>
      Cruelty Free
    </div>
  </div>

</section>


<!-- ============================================================
     TRUST BAR
     ============================================================ -->
<div class="trust-bar">
  <div class="trust-item"><span>🚚</span> Free shipping over 3,000 L</div>
  <div class="trust-sep"></div>
  <div class="trust-item"><span>✓</span> Dermatologist tested</div>
  <div class="trust-sep"></div>
  <div class="trust-item"><span>🌿</span> Clean ingredients</div>
  <div class="trust-sep"></div>
  <div class="trust-item"><span>↩</span> Easy returns</div>
</div>


<!-- ============================================================
     CATEGORIES
     ============================================================ -->
<div class="section-wrap">
  <div class="section-header">
    <div class="section-eyebrow">Browse</div>
    <h2 class="section-title">Shop by <em>Category</em></h2>
  </div>

  <div class="categories-grid">

  <a href="pages/shop.php?cat=skincare" class="cat-card">
    <span class="cat-icon">💧</span>
    <div class="cat-name">Skincare</div>
    <div class="cat-desc">Serums, moisturizers, cleansers and toners for your daily routine.</div>
    <div class="cat-arrow">Explore →</div>
  </a>

  <a href="pages/shop.php?cat=makeup" class="cat-card">
    <span class="cat-icon">✨</span>
    <div class="cat-name">Makeup</div>
    <div class="cat-desc">Foundation, lip gloss, eyeshadow and blush for every look.</div>
    <div class="cat-arrow">Explore →</div>
  </a>

  <a href="pages/shop.php?cat=hair" class="cat-card">
    <span class="cat-icon">🌿</span>
    <div class="cat-name">Hair</div>
    <div class="cat-desc">Treatments, masks, shampoos and serums for healthy hair.</div>
    <div class="cat-arrow">Explore →</div>
  </a>

  <a href="pages/shop.php?cat=body" class="cat-card">
    <span class="cat-icon">🧴</span>
    <div class="cat-name">Body</div>
    <div class="cat-desc">Body butters, washes, scrubs and lotions for soft, glowing skin.</div>
    <div class="cat-arrow">Explore →</div>
  </a>

</div>
</div>


<!-- ============================================================
     BESTSELLER PRODUCTS
     ============================================================ -->
<div class="products-section">
  <div class="section-header">
    <div class="section-eyebrow">Featured</div>
    <h2 class="section-title">Bestsellers</h2>
  </div>

  <div class="products-grid">

    <a href="pages/product.php?id=5" class="product-card">
      <div class="product-img product-img-real">
        <img src="assets/images/ordinary_serum.jpg" alt="The Ordinary Serum">
      </div>
      <div class="product-body">
        <div class="product-cat-tag">Skincare</div>
        <div class="product-name">The Ordinary Serum</div>
        <div class="product-footer">
          <span class="product-price">3,200 L</span>
          <button class="btn-add" onclick="event.preventDefault()">+</button>
        </div>
      </div>
    </a>

    <a href="pages/product.php?id=4" class="product-card">
      <div class="product-img product-img-real">
        <img src="assets/images/neutrogena_watergel.jpg" alt="Neutrogena Water Gel">
      </div>
      <div class="product-body">
        <div class="product-cat-tag">Skincare</div>
        <div class="product-name">Neutrogena Water Gel</div>
        <div class="product-footer">
          <span class="product-price">2,800 L</span>
          <button class="btn-add" onclick="event.preventDefault()">+</button>
        </div>
      </div>
    </a>

    <a href="pages/product.php?id=1" class="product-card">
      <div class="product-img product-img-real">
        <img src="assets/images/centella_sunscreen.jpg" alt="Centella Sunscreen">
      </div>
      <div class="product-body">
        <div class="product-cat-tag">SPF</div>
        <div class="product-name">Centella Sunscreen</div>
        <div class="product-footer">
          <span class="product-price">2,400 L</span>
          <button class="btn-add" onclick="event.preventDefault()">+</button>
        </div>
      </div>
    </a>

    <a href="pages/product.php?id=2" class="product-card">
      <div class="product-img product-img-real">
        <img src="assets/images/cerave_clenser.jpg" alt="CeraVe Cleanser">
      </div>
      <div class="product-body">
        <div class="product-cat-tag">Skincare</div>
        <div class="product-name">CeraVe Cleanser</div>
        <div class="product-footer">
          <span class="product-price">2,200 L</span>
          <button class="btn-add" onclick="event.preventDefault()">+</button>
        </div>
      </div>
    </a>

  </div>
</div>


<!-- ============================================================
     PROMO BANNER
     ============================================================ -->
<div class="banner">

  <div class="banner-content">
    <div class="banner-eyebrow">Limited Offer</div>
    <h2 class="banner-title">Get <em>10% off</em><br>your first order</h2>
    <p class="banner-desc">
      Use code CLEARE10 at checkout and start your skincare journey today.
    </p>
    <a href="pages/shop.php" class="btn-light">Shop Now</a>
  </div>

  <div class="banner-coupon">
    <div class="coupon-pct">10%</div>
    <div class="coupon-off">OFF</div>
    <div class="coupon-code-box" title="Click to copy">CLEARE10</div>
  </div>

</div>


<!-- ============================================================
     FOOTER
     ============================================================ -->
<?php include('includes/footer.php'); ?>


<!-- ============================================================
     JAVASCRIPT — Mobile menu & coupon copy
     ============================================================ -->
<?php include(__DIR__ . '/includes/nav-js.php'); ?>
<script>
  const couponBox = document.querySelector('.coupon-code-box');
  if (couponBox) {
    couponBox.addEventListener('click', () => {
      navigator.clipboard.writeText('CLEARE10');
      couponBox.textContent = 'Copied!';
      setTimeout(() => couponBox.textContent = 'CLEARE10', 2000);
    });
  }
</script>

</body>
</html>