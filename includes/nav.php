<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav>
  <a href="/cleare/index.php" class="nav-logo">Clear<span>è</span></a>

  <ul class="nav-links">
    <li><a href="/cleare/pages/shop.php?cat=skincare">Skincare</a></li>
    <li><a href="/cleare/pages/shop.php?cat=makeup">Makeup</a></li>
    <li><a href="/cleare/pages/shop.php?cat=hair">Hair</a></li>
    <li><a href="/cleare/pages/shop.php?cat=body">Body</a></li>
  </ul>

  <form class="nav-search" action="/cleare/pages/shop.php" method="GET">
    <input type="text" name="search" class="nav-search-input"
           placeholder="Search products..."
           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
    <button type="submit" class="nav-search-btn">🔍</button>
  </form>

  <div class="nav-actions">
    <?php if (isset($_SESSION['user_id'])): ?>
    <a href="/cleare/pages/profile.php" class="nav-icon" title="<?php echo htmlspecialchars($_SESSION['user_name']); ?>"> 👤 </a>
    <?php else: ?>
        <a href="/cleare/pages/login.php" class="nav-icon" title="Account">👤</a>
    <?php endif; ?>
    <a href="/cleare/pages/cart.php" class="nav-icon" title="Cart">
      🛒<span class="cart-badge">0</span>
    </a>
    <button class="hamburger" id="hamburgerBtn" aria-label="Open menu">
      <span></span><span></span><span></span>
    </button>
  </div>
</nav>

<div class="mobile-overlay" id="mobileOverlay"></div>
<div class="mobile-menu" id="mobileMenu">

  <form class="mobile-search" action="/cleare/pages/shop.php" method="GET">
    <input type="text" name="search" class="mobile-search-input"
           placeholder="Search products..."
           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
    <button type="submit" class="mobile-search-btn">🔍</button>
  </form>

  <ul>
    <li><a href="/cleare/pages/shop.php?cat=skincare">Skincare</a></li>
    <li><a href="/cleare/pages/shop.php?cat=makeup">Makeup</a></li>
    <li><a href="/cleare/pages/shop.php?cat=hair">Hair</a></li>
    <li><a href="/cleare/pages/shop.php?cat=body">Body</a></li>
    <?php if (isset($_SESSION['user_id'])): ?>
        <li><a href="/cleare/pages/logout.php">Logout</a></li>
    <?php else: ?>
        <li><a href="/cleare/pages/login.php">Account</a></li>
    <?php endif; ?>
    <li><a href="/cleare/pages/cart.php">Cart</a></li>
  </ul>
</div>