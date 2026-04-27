<?php
$page_title = "Cart — Clearè";

// Produkte demo për shportën (do të zëvendësohen me session/DB më vonë)
$cart_items = [
    [
        "id"       => 5,
        "name"     => "The Ordinary Serum",
        "category" => "Skincare",
        "price"    => 3200,
        "quantity" => 1,
        "image"    => "ordinary_serum.jpg"
    ],
    [
        "id"       => 1,
        "name"     => "Centella Sunscreen",
        "category" => "Skincare",
        "price"    => 2400,
        "quantity" => 2,
        "image"    => "centella_sunscreen.jpg"
    ],
];

// Llogarit subtotalin
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

<?php include(__DIR__ . '/../includes/nav.php'); ?>



<!-- ============================================================
     CART PAGE
     ============================================================ -->
<section class="cart-page">

  <div class="section-header">
    <div class="section-eyebrow">Your Selection</div>
    <h1 class="section-title">Shopping <em>Cart</em></h1>
  </div>

  <?php if (count($cart_items) > 0): ?>

  <div class="cart-layout">

    <!-- ── Left: product list ── -->
    <div class="cart-items">

      <?php foreach ($cart_items as $item): ?>
      <div class="cart-row">

        <div class="cart-img">
          <img src="../assets/images/<?php echo $item['image']; ?>"
               alt="<?php echo $item['name']; ?>">
        </div>

        <div class="cart-info">
          <div class="cart-cat"><?php echo $item['category']; ?></div>
          <div class="cart-name"><?php echo $item['name']; ?></div>
          <div class="cart-unit-price">
            <?php echo number_format($item['price'], 0, ',', ','); ?> L / unit
          </div>
        </div>

        <div class="cart-qty">
          <button class="qty-btn" onclick="changeQty(this, -1)">−</button>
          <span class="qty-val"><?php echo $item['quantity']; ?></span>
          <button class="qty-btn" onclick="changeQty(this, 1)">+</button>
        </div>

        <div class="cart-line-total">
          <?php echo number_format($item['price'] * $item['quantity'], 0, ',', ','); ?> L
        </div>

        <button class="cart-remove" title="Remove item">✕</button>

      </div>
      <?php endforeach; ?>

    </div><!-- .cart-items -->

    <!-- ── Right: order summary ── -->
    <div class="cart-summary">

      <h2 class="summary-title">Order Summary</h2>

      <div class="summary-row">
        <span>Subtotal</span>
        <span><?php echo number_format($subtotal, 0, ',', ','); ?> L</span>
      </div>

      <div class="summary-row">
        <span>Shipping</span>
        <span class="summary-free">Free</span>
      </div>

      <!-- Coupon input -->
      <div class="coupon-row">
        <input type="text" class="coupon-input" placeholder="Coupon code">
        <button class="coupon-apply">Apply</button>
      </div>

      <div class="summary-divider"></div>

      <div class="summary-row summary-total">
        <span>Total</span>
        <span><?php echo number_format($subtotal, 0, ',', ','); ?> L</span>
      </div>

      <a href="checkout.php" class="btn-primary btn-full">
        Proceed to Checkout
      </a>

      <a href="shop.php" class="cart-continue">
        ← Continue Shopping
      </a>

    </div><!-- .cart-summary -->

  </div><!-- .cart-layout -->

  <?php else: ?>

  <!-- Empty cart state -->
  <div class="cart-empty">
    <div class="cart-empty-icon">🛒</div>
    <h2>Your cart is empty</h2>
    <p>Looks like you haven't added anything yet.</p>
    <a href="shop.php" class="btn-primary">Start Shopping</a>
  </div>

  <?php endif; ?>

</section>


<!-- FOOTER -->
<?php include('../includes/footer.php'); ?>


<?php include(__DIR__ . '/../includes/nav-js.php'); ?>
<script>
  function changeQty(btn, delta) {
    const row   = btn.closest('.cart-qty');
    const valEl = row.querySelector('.qty-val');
    let qty     = parseInt(valEl.textContent) + delta;
    if (qty < 1) qty = 1;
    valEl.textContent = qty;
  }

  document.querySelector('.coupon-apply').addEventListener('click', () => {
    const code  = document.querySelector('.coupon-input').value.trim().toUpperCase();
    const valid = ['CLEARE10', 'NEWUSER', 'SKIN20'];
    if (valid.includes(code)) {
      alert('Coupon ' + code + ' applied! Discount will be calculated at checkout.');
    } else {
      alert('Invalid coupon code.');
    }
  });
</script>

</body>
</html>