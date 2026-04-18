<?php
/**
 * product.php — Faqja e detajeve të produktit
 *
 * Merr ID-në e produktit nga URL (?id=N),
 * kërkon produktin në array dhe e shfaq.
 *
 * Shembull: product.php?id=3 → shfaq COSRX Tonic
 *
 * Nëse ID nuk ekziston ose nuk është dhënë,
 * faqja shfaq mesazh gabimi dhe ndalet.
 */

// Ngarkojmë array-in $products
include("../includes/products.php");

// Lexojmë ID nga URL dhe e konvertojmë në numër të plotë
// Nëse parametri mungon, default-i është 0
$product_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Kërkojmë produktin me ID-në e dhënë
$selected_product = null;

foreach ($products as $product) {
    if ($product['id'] === $product_id) {
        $selected_product = $product;
        break; // Gjejmë produktin, ndalim loop-in
    }
}

// Nëse nuk gjetëm asnjë produkt me këtë ID, dalim nga faqja
if (!$selected_product) {
    echo "<p>Produkti nuk u gjet.</p>";
    exit;
}

// Titulli dinamik i faqes, duke përdorur emrin e produktit
$page_title = $selected_product['name'] . " - Clearè";
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
<nav>
  <a href="../index.php" class="nav-logo">Clear<span>è</span></a>

  <ul class="nav-links">
    <li><a href="shop.php?cat=skincare">Skincare</a></li>
    <li><a href="shop.php?cat=makeup">Makeup</a></li>
    <li><a href="shop.php?cat=hair">Hair</a></li>
    <li><a href="shop.php?cat=body">Body</a></li>
  </ul>

  <div class="nav-actions">
    <a href="login.php" class="nav-icon" title="Account">👤</a>
    <a href="cart.php" class="nav-icon" title="Cart">
      🛒
      <span class="cart-badge">0</span>
    </a>
    <button class="hamburger" id="hamburgerBtn" aria-label="Open menu">
      <span></span><span></span><span></span>
    </button>
  </div>
</nav>

<!-- Menu mobile -->
<div class="mobile-overlay" id="mobileOverlay"></div>
<div class="mobile-menu" id="mobileMenu">
  <ul>
    <li><a href="shop.php?cat=skincare">Skincare</a></li>
    <li><a href="shop.php?cat=makeup">Makeup</a></li>
    <li><a href="shop.php?cat=hair">Hair</a></li>
    <li><a href="shop.php?cat=body">Body</a></li>
    <li><a href="login.php">Account</a></li>
    <li><a href="cart.php">Cart</a></li>
  </ul>
</div>


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
            <div class="section-eyebrow"><?php echo $selected_product['category_label']; ?></div>

            <!-- Emri i produktit -->
            <h1 class="product-details-title"><?php echo $selected_product['name']; ?></h1>

            <!-- Çmimi -->
            <div class="product-details-price"><?php echo $selected_product['price']; ?></div>

            <!-- Përshkrimi -->
            <p class="product-details-desc"><?php echo $selected_product['description']; ?></p>

            <!-- Butonat e veprimit -->
            <div class="product-details-actions">
                <button class="btn-primary">Shto në shportë</button>
                <a href="shop.php" class="btn-outline">Kthehu te Shop</a>
            </div>

        </div><!-- .product-details-info -->

    </div><!-- .product-details-card -->

</section><!-- .product-page -->


<!-- ============================================================
     FOOTER
     ============================================================ -->
<!-- ============================================================
     FOOTER — kopjo këtë në index.php, shop.php, product.php
     (zëvendëso <footer>...</footer> ekzistues)
     ============================================================ -->
<footer>
  <div class="footer-top">

    <!-- Brendi + Social -->
    <div class="footer-brand">
      <div class="logo">Clear<span>è</span></div>
      <div class="footer-tagline">Your skin, simplified.</div>

      <div class="footer-social">
        <a href="#" title="Instagram">📷</a>
        <a href="#" title="TikTok">🎵</a>
        <a href="#" title="Pinterest">📌</a>
      </div>
    </div>

    <!-- Shop links -->
    <div class="footer-links">
      <h4>Shop</h4>
      <ul>
        <li><a href="pages/shop.php">All Products</a></li>
        <li><a href="pages/shop.php?cat=skincare">Skincare</a></li>
        <li><a href="pages/shop.php?cat=spf">SPF</a></li>
      </ul>
    </div>

    <!-- Account links -->
    <div class="footer-links">
      <h4>Account</h4>
      <ul>
        <li><a href="pages/login.php">Login</a></li>
        <li><a href="pages/register.php">Register</a></li>
        <li><a href="pages/cart.php">Cart</a></li>
      </ul>
    </div>

    <!-- Info links -->
    <div class="footer-links">
      <h4>Info</h4>
      <ul>
        <li><a href="#">About Us</a></li>
        <li><a href="#">Contact</a></li>
        <li><a href="#">Privacy Policy</a></li>
      </ul>
    </div>

  </div><!-- .footer-top -->

  <!-- Trust bar -->
  <div class="footer-trust">
    <div class="footer-trust-item">
      <div class="footer-trust-icon">🚚</div>
      Free shipping over 3,000 L
    </div>
    <div class="footer-trust-item">
      <div class="footer-trust-icon">✓</div>
      Dermatologist tested
    </div>
    <div class="footer-trust-item">
      <div class="footer-trust-icon">🌿</div>
      Clean ingredients
    </div>
    <div class="footer-trust-item">
      <div class="footer-trust-icon">↩</div>
      Easy returns
    </div>
  </div>

  <!-- Bottom bar -->
  <div class="footer-bottom">
    <span>&copy; <?php echo date('Y'); ?> Clearè · Academic PHP Project</span>
    <div class="footer-bottom-right">
      <a href="#">Privacy</a>
      <a href="#">Terms</a>
      <span>Iva Pipero</span>
    </div>
  </div>

</footer>


<!-- ============================================================
     JAVASCRIPT — Menu mobile
     ============================================================ -->
<script>
    const hamburger  = document.getElementById('hamburgerBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    const overlay    = document.getElementById('mobileOverlay');

    hamburger.addEventListener('click', () => {
        hamburger.classList.toggle('open');
        mobileMenu.classList.toggle('open');
        overlay.classList.toggle('open');
    });

    overlay.addEventListener('click', () => {
        hamburger.classList.remove('open');
        mobileMenu.classList.remove('open');
        overlay.classList.remove('open');
    });
</script>

</body>
</html>
