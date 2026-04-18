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

// Filtrojmë produktet sipas kategorisë
// Nëse nuk ka kategori të zgjedhur, marrim të gjitha
$filtered_products = [];

foreach ($products as $product) {
    $no_filter      = $selected_category === '';
    $matches_filter = $product['category'] === $selected_category;

    if ($no_filter || $matches_filter) {
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

<!-- Menu mobile (slide-in nga e djathta) -->
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
     SEKSIONI KRYESOR I SHOP-IT
     ============================================================ -->
<section class="shop-page">

    <!-- Titulli i faqes -->
    <div class="shop-header">
        <div class="section-eyebrow">Shop</div>
        <h1 class="section-title">Produktet <em>Clearè</em></h1>
        <p class="shop-subtitle">Zgjidh produktet e preferuara për kujdesin e lëkurës.</p>
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
        <!-- Linkat e llogarisë -->
        <div class="footer-links">
            <h4>Llogaria</h4>
            <ul>
                <li><a href="../pages/login.php">Hyr</a></li>
                <li><a href="../pages/register.php">Regjistrohu</a></li>
                <li><a href="../pages/cart.php">Shporta</a></li>
            </ul>
        </div>

    </div><!-- .footer-top -->

    <div class="footer-bottom">
        <span>&copy; <?php echo date('Y'); ?> Clearè · Projekt Akademik PHP</span>
        <span>Iva Pipero</span>
    </div>
</footer>


<!-- ============================================================
     JAVASCRIPT — Menu mobile
     ============================================================ -->
<script>
    const hamburger   = document.getElementById('hamburgerBtn');
    const mobileMenu  = document.getElementById('mobileMenu');
    const overlay     = document.getElementById('mobileOverlay');

    // Hap / mbyll menunë kur klikohet hamburger-i
    hamburger.addEventListener('click', () => {
        hamburger.classList.toggle('open');
        mobileMenu.classList.toggle('open');
        overlay.classList.toggle('open');
    });

    // Mbyll menunë kur klikohet overlay-i i errët
    overlay.addEventListener('click', () => {
        hamburger.classList.remove('open');
        mobileMenu.classList.remove('open');
        overlay.classList.remove('open');
    });
</script>

</body>
</html>
