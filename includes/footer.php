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

<?php endif; ?><div id="ai-chatbot-box" class="ai-chatbot-box">
    <div class="ai-chatbot-header">
        <div>
            <strong>Cleare AI Assistant</strong>
            <span>Online support</span>
        </div>

        <button type="button" id="ai-chatbot-close" class="ai-chatbot-close">
            ×
        </button>
    </div>

    <div id="ai-chatbot-messages" class="ai-chatbot-messages">
        <div class="ai-chat-message ai-bot-message">
            Hi! I am Cleare AI Assistant. Ask me about products, cart, checkout, coupons, or skincare routines.
        </div>
    </div>

    <div class="ai-chatbot-input-area">
        <input
            type="text"
            id="ai-chatbot-input"
            placeholder="Ask something..."
            autocomplete="off"
        >

        <button type="button" id="ai-chatbot-send">
            Send
        </button>
    </div>
</div>

<button type="button" id="ai-chatbot-toggle" class="ai-chatbot-toggle" aria-label="Open support chat">
    <svg class="ai-chatbot-icon" width="28" height="28" viewBox="0 0 24 24" fill="none">
        <path d="M4 12C4 7.58 7.58 4 12 4C16.42 4 20 7.58 20 12V15C20 16.1 19.1 17 18 17H16V10H18C18.35 10 18.69 10.06 19 10.17C18.18 7.18 15.37 5 12 5C8.63 5 5.82 7.18 5 10.17C5.31 10.06 5.65 10 6 10H8V17H6C4.9 17 4 16.1 4 15V12Z" fill="currentColor"/>
        <path d="M10 18H14C14.55 18 15 18.45 15 19C15 19.55 14.55 20 14 20H10C9.45 20 9 19.55 9 19C9 18.45 9.45 18 10 18Z" fill="currentColor"/>
        <path d="M16 16H18C18.55 16 19 15.55 19 15V13H20V15C20 16.66 18.66 18 17 18H16V16Z" fill="currentColor"/>
    </svg>
</button>

<script src="/cleare/assets/js/chatbot.js"></script>