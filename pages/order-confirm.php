<?php
$page_title = "Order Confirmed — Clearè";

// Demo order data (do të vijë nga session/DB më vonë)
$order = [
    "number"   => "CLR-" . rand(10000, 99999),
    "date"     => date("d M Y"),
    "name"     => "Jane Doe",
    "email"    => "jane@example.com",
    "address"  => "Rruga e Durrësit, Nr. 12, Tirana",
    "payment"  => "Credit Card",
    "items"    => [
        ["name" => "The Ordinary Serum",  "qty" => 1, "price" => 3200],
        ["name" => "Centella Sunscreen",  "qty" => 2, "price" => 2400],
    ],
    "subtotal" => 8000,
    "discount" => 0,
    "total"    => 8000,
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $page_title; ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

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
      🛒<span class="cart-badge">0</span>
    </a>
    <button class="hamburger" id="hamburgerBtn" aria-label="Open menu">
      <span></span><span></span><span></span>
    </button>
  </div>
</nav>

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
     ORDER CONFIRMATION
     ============================================================ -->
<section class="confirm-page">

  <!-- Success icon & message -->
  <div class="confirm-hero">
    <div class="confirm-icon">✓</div>
    <h1 class="confirm-title">Order Confirmed!</h1>
    <p class="confirm-sub">
      Thank you, <strong><?php echo $order['name']; ?></strong>!
      Your order has been placed successfully.
    </p>
    <div class="confirm-order-number">
      Order #<?php echo $order['number']; ?>
    </div>
  </div>

  <div class="confirm-layout">

    <!-- ── Left: order details ── -->
    <div class="confirm-details">

      <!-- Items ordered -->
      <div class="confirm-card">
        <h2 class="confirm-card-title">Items Ordered</h2>
        <?php foreach ($order['items'] as $item): ?>
        <div class="confirm-item-row">
          <div class="confirm-item-name">
            <?php echo $item['name']; ?>
            <span class="confirm-item-qty">× <?php echo $item['qty']; ?></span>
          </div>
          <div class="confirm-item-price">
            <?php echo number_format($item['price'] * $item['qty'], 0, ',', ','); ?> L
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- Shipping info -->
      <div class="confirm-card">
        <h2 class="confirm-card-title">Shipping To</h2>
        <div class="confirm-info-row">
          <span class="confirm-info-label">Name</span>
          <span><?php echo $order['name']; ?></span>
        </div>
        <div class="confirm-info-row">
          <span class="confirm-info-label">Email</span>
          <span><?php echo $order['email']; ?></span>
        </div>
        <div class="confirm-info-row">
          <span class="confirm-info-label">Address</span>
          <span><?php echo $order['address']; ?></span>
        </div>
        <div class="confirm-info-row">
          <span class="confirm-info-label">Payment</span>
          <span><?php echo $order['payment']; ?></span>
        </div>
      </div>

    </div><!-- .confirm-details -->

    <!-- ── Right: order summary ── -->
    <div class="cart-summary">

      <h2 class="summary-title">Order Summary</h2>

      <div class="summary-row">
        <span>Date</span>
        <span><?php echo $order['date']; ?></span>
      </div>
      <div class="summary-row">
        <span>Subtotal</span>
        <span><?php echo number_format($order['subtotal'], 0, ',', ','); ?> L</span>
      </div>
      <div class="summary-row">
        <span>Shipping</span>
        <span class="summary-free">Free</span>
      </div>

      <?php if ($order['discount'] > 0): ?>
      <div class="summary-row">
        <span>Discount</span>
        <span style="color: var(--green-deep);">
          −<?php echo number_format($order['discount'], 0, ',', ','); ?> L
        </span>
      </div>
      <?php endif; ?>

      <div class="summary-divider"></div>

      <div class="summary-row summary-total">
        <span>Total Paid</span>
        <span><?php echo number_format($order['total'], 0, ',', ','); ?> L</span>
      </div>

      <div class="confirm-actions">
        <a href="shop.php" class="btn-primary btn-full">Continue Shopping</a>
        <a href="../index.php" class="cart-continue">← Back to Home</a>
      </div>

    </div><!-- .cart-summary -->

  </div><!-- .confirm-layout -->

</section>


<!-- FOOTER -->
<footer>
  <div class="footer-top">
    <div class="footer-brand">
      <div class="logo">Clear<span>è</span></div>
      <div class="footer-tagline">The clearest in sight.</div>
    </div>
    <div class="footer-links">
      <h4>Shop</h4>
      <ul>
        <li><a href="shop.php">All Products</a></li>
        <li><a href="shop.php?cat=skincare">Skincare</a></li>
        <li><a href="shop.php?cat=makeup">Makeup</a></li>
        <li><a href="shop.php?cat=hair">Hair</a></li>
        <li><a href="shop.php?cat=body">Body</a></li>
      </ul>
    </div>
    <div class="footer-links">
      <h4>Account</h4>
      <ul>
        <li><a href="login.php">Login</a></li>
        <li><a href="register.php">Register</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <span>&copy; <?php echo date('Y'); ?> Clearè · Academic PHP Project</span>
    <span>Iva Pipero</span>
  </div>
</footer>


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