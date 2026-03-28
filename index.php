<?php
// index.php — Clearè Homepage
$page_title = "Clearè — Your skin, simplified.";
?>
<!DOCTYPE html>
<html lang="sq">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $page_title; ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- NAV -->
<nav>
  <a href="index.php" class="nav-logo">Clear<span>è</span></a>
  <ul class="nav-links">
    <li><a href="pages/shop.php">Shop</a></li>
    <li><a href="pages/shop.php?cat=skincare">Skincare</a></li>
    <li><a href="pages/shop.php?cat=makeup">Makeup</a></li>
    <li><a href="#">Për ne</a></li>
  </ul>
  <div class="nav-actions">
    <a href="pages/login.php" class="nav-icon" title="Llogaria">👤</a>
    <a href="pages/cart.php" class="nav-icon" title="Shporta">
      🛒
      <span class="cart-badge">0</span>
    </a>
    <button class="hamburger" id="hamburger" aria-label="Menu" aria-expanded="false">
      <span></span>
      <span></span>
      <span></span>
    </button>
  </div>
</nav>

<!-- MOBILE MENU -->
<div class="mobile-menu" id="mobileMenu">
  <ul>
    <li><a href="pages/shop.php" onclick="closeMenu()">Shop</a></li>
    <li><a href="pages/shop.php?cat=skincare" onclick="closeMenu()">Skincare</a></li>
    <li><a href="pages/shop.php?cat=makeup" onclick="closeMenu()">Makeup</a></li>
    <li><a href="#" onclick="closeMenu()">Për ne</a></li>
  </ul>
</div>
<div class="mobile-overlay" id="mobileOverlay" onclick="closeMenu()"></div>

<script>
  const hamburger = document.getElementById('hamburger');
  const mobileMenu = document.getElementById('mobileMenu');
  const mobileOverlay = document.getElementById('mobileOverlay');

  hamburger.addEventListener('click', () => {
    const isOpen = mobileMenu.classList.toggle('open');
    mobileOverlay.classList.toggle('open');
    hamburger.classList.toggle('open');
    hamburger.setAttribute('aria-expanded', isOpen);
  });

  function closeMenu() {
    mobileMenu.classList.remove('open');
    mobileOverlay.classList.remove('open');
    hamburger.classList.remove('open');
    hamburger.setAttribute('aria-expanded', false);
  }
</script>

<!-- HERO -->
<section class="hero">
  <div class="blob blob-1"></div>
  <div class="blob blob-2"></div>
  <div class="blob blob-3"></div>

  <div class="hero-content">
    <div class="hero-eyebrow">Skincare Premium · Shqipëri</div>
    <h1 class="hero-title">
      Lëkura jote,<br>
      e <em>thjeshtuar.</em>
    </h1>
    <p class="hero-desc">
      Produkte të formuluara me përbërës të pastër dhe natyralë,
      të zgjedhur me kujdes për çdo lloj lëkure.
    </p>
    <div class="hero-buttons">
      <a href="pages/shop.php" class="btn-primary">
        Shiko Produktet →
      </a>
      <a href="pages/shop.php?cat=skincare" class="btn-outline">
        Skincare
      </a>
    </div>
  </div>

  <div class="hero-visual">
    <div class="hero-product-card">
      <div class="float-badge float-badge-1">
        <div class="float-dot"></div>
        100% Natyral
      </div>
      <span class="product-emoji">💧</span>
      <div class="product-label">Bestseller · Serum</div>
      <div class="product-name-card">Vitamin C Brightening Serum</div>
      <div class="product-price-card">3,200 L</div>
      <a href="pages/product.php?id=1" class="btn-card">Shto në shportë</a>
      <div class="float-badge float-badge-2">
        ⭐ 4.9 · 128 review
      </div>
    </div>
  </div>
</section>

<!-- TRUST BAR -->
<div class="trust-bar">
  <div class="trust-item"><span>🚚</span> Dërgesa falas mbi 3,000 L</div>
  <div class="trust-sep"></div>
  <div class="trust-item"><span>🌿</span> Përbërës natyralë</div>
  <div class="trust-sep"></div>
  <div class="trust-item"><span>↩️</span> Kthim i lehtë 30 ditë</div>
  <div class="trust-sep"></div>
  <div class="trust-item"><span>🔒</span> Pagesë e sigurtë</div>
</div>

<!-- CATEGORIES -->
<div class="section-wrap">
  <div class="section-header">
    <div class="section-eyebrow">Koleksioni</div>
    <h2 class="section-title">Zbulo <em>Kategorinë</em></h2>
  </div>

  <div class="categories-grid">
    <a href="pages/shop.php?cat=skincare" class="cat-card">
      <span class="cat-icon">💧</span>
      <div class="cat-name">Kujdes Fytyre</div>
      <div class="cat-desc">Serum, moisturizer, toner, maska — rutina e plotë për lëkurën tënde.</div>
      <div class="cat-arrow">Shiko të gjitha →</div>
    </a>
    <a href="pages/shop.php?cat=body" class="cat-card">
      <span class="cat-icon">🧴</span>
      <div class="cat-name">Kujdes Trupi</div>
      <div class="cat-desc">Body lotion, scrub, hand cream, oil — lëkurë e butë nga koka te këmbët.</div>
      <div class="cat-arrow">Shiko të gjitha →</div>
    </a>
    <a href="pages/shop.php?cat=spf" class="cat-card">
      <span class="cat-icon">☀️</span>
      <div class="cat-name">Mbrojtje Diell</div>
      <div class="cat-desc">SPF 30, SPF 50, After Sun — mbrojtja kryesore çdo ditë të vitit.</div>
      <div class="cat-arrow">Shiko të gjitha →</div>
    </a>
  </div>
</div>

<!-- FEATURED PRODUCTS -->
<div class="products-section">
  <div class="section-header">
    <div class="section-eyebrow">Të preferuarat</div>
    <h2 class="section-title">Produkte <em>Bestseller</em></h2>
  </div>

  <div class="products-grid">

    <a href="pages/product.php?id=1" class="product-card">
      <div class="product-img">
        <span class="product-badge">Bestseller</span>
        💧
      </div>
      <div class="product-body">
        <div class="product-cat-tag">Skincare · Serum</div>
        <div class="product-name">Vitamin C Brightening Serum</div>
        <div class="product-footer">
          <span class="product-price">3,200 L</span>
          <button class="btn-add" onclick="event.preventDefault()">+</button>
        </div>
      </div>
    </a>

    <a href="pages/product.php?id=2" class="product-card">
      <div class="product-img">🌿</div>
      <div class="product-body">
        <div class="product-cat-tag">Skincare · Moisturizer</div>
        <div class="product-name">Hydra Glow Moisturizer</div>
        <div class="product-footer">
          <span class="product-price">2,800 L</span>
          <button class="btn-add" onclick="event.preventDefault()">+</button>
        </div>
      </div>
    </a>

    <a href="pages/product.php?id=3" class="product-card">
      <div class="product-img">
        <span class="product-badge">E re</span>
        ☀️
      </div>
      <div class="product-body">
        <div class="product-cat-tag">Skincare · SPF</div>
        <div class="product-name">Daily Shield SPF 50</div>
        <div class="product-footer">
          <span class="product-price">2,400 L</span>
          <button class="btn-add" onclick="event.preventDefault()">+</button>
        </div>
      </div>
    </a>

    <a href="pages/product.php?id=6" class="product-card">
      <div class="product-img">👁️</div>
      <div class="product-body">
        <div class="product-cat-tag">Skincare · Eye Cream</div>
        <div class="product-name">Revive Eye Cream</div>
        <div class="product-footer">
          <span class="product-price">3,500 L</span>
          <button class="btn-add" onclick="event.preventDefault()">+</button>
        </div>
      </div>
    </a>

  </div>
</div>

<!-- PROMO BANNER -->
<div class="banner">
  <div class="banner-content">
    <div class="banner-eyebrow">Ofertë Speciale</div>
    <h2 class="banner-title">
      Regjistrohu &amp; merr<br>
      <em>15% zbritje</em>
    </h2>
    <p class="banner-desc">
      Krijo llogarinë tënde sot dhe gëzo 15% zbritje
      në blerjen e parë me kodin NEWUSER.
    </p>
    <a href="pages/register.php" class="btn-light">Regjistrohu tani →</a>
  </div>
  <div class="banner-coupon">
    <div class="coupon-pct">15%</div>
    <div class="coupon-off">Zbritje · Blerja e parë</div>
    <div class="coupon-code-box" onclick="navigator.clipboard.writeText('NEWUSER')" title="Klik për të kopjuar">NEWUSER</div>
  </div>
</div>

<!-- FOOTER -->
<footer>
  <div class="footer-top">
    <div class="footer-brand">
      <div class="logo">Clear<span>è</span></div>
      <div class="footer-tagline">Your skin, simplified.</div>
    </div>
    <div class="footer-links">
      <h4>Shop</h4>
      <ul>
        <li><a href="pages/shop.php?cat=skincare">Skincare</a></li>
        <li><a href="pages/shop.php?cat=makeup">Makeup</a></li>
        <li><a href="pages/shop.php?cat=spf">SPF</a></li>
        <li><a href="pages/shop.php?cat=body">Kujdes Trupi</a></li>
      </ul>
    </div>
    <div class="footer-links">
      <h4>Llogaria</h4>
      <ul>
        <li><a href="pages/login.php">Hyr</a></li>
        <li><a href="pages/register.php">Regjistrohu</a></li>
        <li><a href="pages/cart.php">Shporta</a></li>
      </ul>
    </div>
    <div class="footer-links">
      <h4>Info</h4>
      <ul>
        <li><a href="#">Për ne</a></li>
        <li><a href="#">Politika e kthimit</a></li>
        <li><a href="#">Kontakt</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <span>&copy; <?php echo date('Y'); ?> Clearè · Projekt Akademik PHP</span>
    <span>Iva Pipero</span>
  </div>
</footer>

</body>
</html>