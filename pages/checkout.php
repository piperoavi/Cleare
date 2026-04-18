<?php
$page_title = "Checkout — Clearè";

// Demo order summary (do të vijë nga session më vonë)
$cart_items = [
    [
        "name"     => "The Ordinary Serum",
        "category" => "Skincare",
        "price"    => 3200,
        "quantity" => 1,
        "image"    => "ordinary_serum.jpg"
    ],
    [
        "name"     => "Centella Sunscreen",
        "category" => "Skincare",
        "price"    => 2400,
        "quantity" => 2,
        "image"    => "centella_sunscreen.jpg"
    ],
];

$subtotal = 0;
foreach ($cart_items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
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
      🛒<span class="cart-badge"><?php echo count($cart_items); ?></span>
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
     CHECKOUT PAGE
     ============================================================ -->
<section class="checkout-page">

  <div class="section-header">
    <div class="section-eyebrow">Almost there</div>
    <h1 class="section-title">Check<em>out</em></h1>
  </div>

  <!-- Progress steps -->
  <div class="checkout-steps">
    <div class="step active">
      <div class="step-dot">1</div>
      <div class="step-label">Shipping</div>
    </div>
    <div class="step-line"></div>
    <div class="step">
      <div class="step-dot">2</div>
      <div class="step-label">Payment</div>
    </div>
    <div class="step-line"></div>
    <div class="step">
      <div class="step-dot">3</div>
      <div class="step-label">Confirm</div>
    </div>
  </div>

  <div class="checkout-layout">

    <!-- ── Left: form ── -->
    <div class="checkout-form-wrap">

      <!-- Step 1: Shipping -->
      <div class="checkout-section" id="step-shipping">
        <h2 class="checkout-section-title">Shipping Information</h2>

        <form id="checkout-form" class="checkout-form">

          <div class="form-row">
            <div class="form-group">
              <label for="first_name">First Name</label>
              <input type="text" id="first_name" name="first_name"
                     placeholder="Jane" required>
            </div>
            <div class="form-group">
              <label for="last_name">Last Name</label>
              <input type="text" id="last_name" name="last_name"
                     placeholder="Doe" required>
            </div>
          </div>

          <div class="form-group">
            <label for="co_email">Email</label>
            <input type="email" id="co_email" name="email"
                   placeholder="you@example.com" required>
          </div>

          <div class="form-group">
            <label for="phone">Phone</label>
            <input type="tel" id="phone" name="phone"
                   placeholder="+355 6X XXX XXXX" required>
          </div>

          <div class="form-group">
            <label for="address">Address</label>
            <input type="text" id="address" name="address"
                   placeholder="Rruga e Durrësit, Nr. 12" required>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="city">City</label>
              <input type="text" id="city" name="city"
                     placeholder="Tirana" required>
            </div>
            <div class="form-group">
              <label for="zip">ZIP Code</label>
              <input type="text" id="zip" name="zip"
                     placeholder="1001">
            </div>
          </div>

          <!-- Step 2: Payment -->
          <h2 class="checkout-section-title" style="margin-top: 40px;">
            Payment Method
          </h2>

          <div class="payment-options">

            <label class="payment-option">
              <input type="radio" name="payment" value="card" checked>
              <div class="payment-card">
                <span class="payment-icon">💳</span>
                <div>
                  <div class="payment-name">Credit / Debit Card</div>
                  <div class="payment-sub">Visa, Mastercard, Amex</div>
                </div>
              </div>
            </label>

            <label class="payment-option">
              <input type="radio" name="payment" value="paypal">
              <div class="payment-card">
                <span class="payment-icon">🅿️</span>
                <div>
                  <div class="payment-name">PayPal</div>
                  <div class="payment-sub">Fast & secure checkout</div>
                </div>
              </div>
            </label>

            <label class="payment-option">
              <input type="radio" name="payment" value="cash">
              <div class="payment-card">
                <span class="payment-icon">💵</span>
                <div>
                  <div class="payment-name">Cash on Delivery</div>
                  <div class="payment-sub">Pay when you receive</div>
                </div>
              </div>
            </label>

          </div>

          <!-- Card fields (shown only when card is selected) -->
          <div class="card-fields" id="cardFields">
            <div class="form-group">
              <label for="card_number">Card Number</label>
              <input type="text" id="card_number" name="card_number"
                     placeholder="1234 5678 9012 3456" maxlength="19">
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="card_expiry">Expiry Date</label>
                <input type="text" id="card_expiry" name="card_expiry"
                       placeholder="MM / YY" maxlength="7">
              </div>
              <div class="form-group">
                <label for="card_cvv">CVV</label>
                <input type="text" id="card_cvv" name="card_cvv"
                       placeholder="•••" maxlength="3">
              </div>
            </div>
          </div>

          <button type="submit" class="btn-primary btn-full" style="margin-top: 32px;">
            Place Order
          </button>

          <p class="checkout-note">
            🔒 This is a demo checkout. No real payment will be processed.
          </p>

        </form>
      </div>

    </div><!-- .checkout-form-wrap -->

    <!-- ── Right: order summary ── -->
    <div class="cart-summary">

      <h2 class="summary-title">Your Order</h2>

      <?php foreach ($cart_items as $item): ?>
      <div class="checkout-item-row">
        <div class="checkout-item-img">
          <img src="../assets/images/<?php echo $item['image']; ?>"
               alt="<?php echo $item['name']; ?>">
        </div>
        <div class="checkout-item-info">
          <div class="checkout-item-name"><?php echo $item['name']; ?></div>
          <div class="checkout-item-qty">Qty: <?php echo $item['quantity']; ?></div>
        </div>
        <div class="checkout-item-price">
          <?php echo number_format($item['price'] * $item['quantity'], 0, ',', ','); ?> L
        </div>
      </div>
      <?php endforeach; ?>

      <div class="summary-divider"></div>

      <div class="summary-row">
        <span>Subtotal</span>
        <span><?php echo number_format($subtotal, 0, ',', ','); ?> L</span>
      </div>
      <div class="summary-row">
        <span>Shipping</span>
        <span class="summary-free">Free</span>
      </div>
      <div class="summary-row">
        <span>Discount</span>
        <span id="discount-display">—</span>
      </div>

      <div class="summary-divider"></div>

      <div class="summary-row summary-total">
        <span>Total</span>
        <span id="total-display">
          <?php echo number_format($subtotal, 0, ',', ','); ?> L
        </span>
      </div>

      <!-- Coupon -->
      <div class="coupon-row">
        <input type="text" class="coupon-input" id="couponInput"
               placeholder="Coupon code">
        <button class="coupon-apply" id="couponApply">Apply</button>
      </div>
      <div class="coupon-msg" id="couponMsg"></div>

    </div><!-- .cart-summary -->

  </div><!-- .checkout-layout -->

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
  // Mobile menu
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

  // Show/hide card fields based on payment method
  const paymentOptions = document.querySelectorAll('input[name="payment"]');
  const cardFields     = document.getElementById('cardFields');
  paymentOptions.forEach(opt => {
    opt.addEventListener('change', () => {
      cardFields.style.display = opt.value === 'card' ? 'block' : 'none';
    });
  });

  // Coupon logic (frontend only)
  const coupons = { 'CLEARE10': 10, 'NEWUSER': 15, 'SKIN20': 20 };
  const subtotal = <?php echo $subtotal; ?>;
  let discount = 0;

  document.getElementById('couponApply').addEventListener('click', () => {
    const code    = document.getElementById('couponInput').value.trim().toUpperCase();
    const msgEl   = document.getElementById('couponMsg');
    const discEl  = document.getElementById('discount-display');
    const totalEl = document.getElementById('total-display');

    if (coupons[code]) {
      discount = Math.round(subtotal * coupons[code] / 100);
      const total = subtotal - discount;
      discEl.textContent  = '−' + discount.toLocaleString() + ' L';
      discEl.style.color  = 'var(--green-deep)';
      totalEl.textContent = total.toLocaleString() + ' L';
      msgEl.textContent   = '✓ Coupon applied — ' + coupons[code] + '% off';
      msgEl.style.color   = 'var(--green-deep)';
    } else {
      msgEl.textContent  = '✕ Invalid coupon code';
      msgEl.style.color  = '#E74C3C';
    }
  });

  // Form submit (demo — do të lidhet me backend)
  document.getElementById('checkout-form').addEventListener('submit', (e) => {
    e.preventDefault();
    window.location.href = 'order-confirm.php';
  });
</script>

</body>
</html>