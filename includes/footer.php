<footer>
  <div class="footer-top">

    <div class="footer-brand">
      <div class="logo">Clear<span>è</span></div>
      <div class="footer-tagline">The clearest in sight.</div>
      <div class="footer-social">
        <a href="#" title="Instagram">📷</a>
        <a href="#" title="TikTok">🎵</a>
        <a href="#" title="Pinterest">📌</a>
      </div>
    </div>

<div class="footer-links">
  <h4>Shop</h4>
  <ul>
    <li><a href="/cleare/pages/shop.php">All Products</a></li>
    <li><a href="/cleare/pages/shop.php?cat=skincare">Skincare</a></li>
    <li><a href="/cleare/pages/shop.php?cat=makeup">Makeup</a></li>
    <li><a href="/cleare/pages/shop.php?cat=hair">Hair</a></li>
    <li><a href="/cleare/pages/shop.php?cat=body">Body</a></li>
  </ul>
</div>

<div class="footer-links">
  <h4>Account</h4>
  <ul>
    <li><a href="/cleare/pages/login.php">Login</a></li>
    <li><a href="/cleare/pages/register.php">Register</a></li>
    <li><a href="/cleare/pages/cart.php">Cart</a></li>
  </ul>
</div>

<div class="footer-links">
  <h4>Info</h4>
  <ul>
    <li><a href="/cleare/pages/about.php">About Us</a></li>
    <li><a href="/cleare/pages/contact.php">Contact</a></li>
    <li><a href="/cleare/pages/terms.php">Terms & Privacy</a></li>
  </ul>
</div>

  </div>

  <div class="footer-trust">
    <div class="footer-trust-item"><div class="footer-trust-icon">🚚</div> Free shipping over 3,000 L</div>
    <div class="footer-trust-item"><div class="footer-trust-icon">✓</div> Dermatologist tested</div>
    <div class="footer-trust-item"><div class="footer-trust-icon">🌿</div> Clean ingredients</div>
    <div class="footer-trust-item"><div class="footer-trust-icon">↩</div> Easy returns</div>
  </div>

  <div class="footer-bottom">
  <span>&copy; <?php echo date('Y'); ?> Clearè</span>
  <div class="footer-bottom-right">
    <a href="/cleare/pages/terms.php">Terms & Privacy</a>
  </div>
</div>

</footer>
<?php if (!isset($_COOKIE['cleare_cookie_consent'])): ?>
    <div id="cookie-consent" class="cookie-consent show">
        <p class="cookie-consent-text">
            <strong>Cookies Notice:</strong>
            We use cookies to improve your browsing experience, remember preferences,
            and analyze website usage. By clicking Accept, you agree to our cookie policy.
            <a href="/cleare/pages/terms.php">Learn more</a>
        </p>

        <div class="cookie-consent-actions">
            <form method="POST" action="/cleare/actions/cookie-consent.php">
                <input type="hidden" name="choice" value="declined">
                <button type="submit" class="cookie-btn cookie-decline">
                    Decline
                </button>
            </form>

            <form method="POST" action="/cleare/actions/cookie-consent.php">
                <input type="hidden" name="choice" value="accepted">
                <button type="submit" class="cookie-btn cookie-accept">
                    Accept
                </button>
            </form>
        </div>
    </div>
<?php endif; ?>