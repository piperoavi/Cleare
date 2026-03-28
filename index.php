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
  <style>
    :root {
      --sky:      #D6EEF5;
      --sky-mid:  #A8D8EA;
      --sky-deep: #5BAFC9;
      --green:    #B8D8C8;
      --green-deep:#6FAE8E;
      --white:    #FAFCFD;
      --ink:      #1E2A2E;
      --ink-soft: #4A6070;
      --gold:     #A89070;
      --cream:    #F0F7F4;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    html { scroll-behavior: smooth; }

    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--white);
      color: var(--ink);
      overflow-x: hidden;
    }

    /* ── NAV ── */
    nav {
      position: fixed;
      top: 0; left: 0; right: 0;
      z-index: 100;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 20px 48px;
      background: rgba(250,252,253,0.85);
      backdrop-filter: blur(12px);
      border-bottom: 1px solid rgba(91,175,201,0.12);
      transition: padding 0.3s ease;
    }

    .nav-logo {
      font-family: 'Cormorant Garamond', serif;
      font-size: 28px;
      font-weight: 300;
      letter-spacing: 0.08em;
      color: var(--ink);
      text-decoration: none;
    }
    .nav-logo span { color: var(--sky-deep); font-style: italic; }

    .nav-links {
      display: flex;
      gap: 36px;
      list-style: none;
    }

    .nav-links a {
      font-size: 13px;
      letter-spacing: 0.15em;
      text-transform: uppercase;
      color: var(--ink-soft);
      text-decoration: none;
      font-weight: 400;
      transition: color 0.2s;
      position: relative;
    }

    .nav-links a::after {
      content: '';
      position: absolute;
      bottom: -3px; left: 0; right: 0;
      height: 1px;
      background: var(--sky-deep);
      transform: scaleX(0);
      transition: transform 0.3s ease;
    }

    .nav-links a:hover { color: var(--sky-deep); }
    .nav-links a:hover::after { transform: scaleX(1); }

    .nav-actions {
      display: flex;
      align-items: center;
      gap: 20px;
    }

    .nav-icon {
      width: 38px; height: 38px;
      display: flex; align-items: center; justify-content: center;
      border-radius: 50%;
      border: 1px solid rgba(91,175,201,0.25);
      color: var(--ink-soft);
      text-decoration: none;
      font-size: 16px;
      transition: all 0.25s;
      position: relative;
    }

    .nav-icon:hover {
      background: var(--sky);
      border-color: var(--sky-deep);
      color: var(--sky-deep);
    }

    .cart-badge {
      position: absolute;
      top: -4px; right: -4px;
      width: 16px; height: 16px;
      background: var(--sky-deep);
      color: white;
      font-size: 9px;
      font-weight: 500;
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
    }

    /* ── HERO ── */
    .hero {
      min-height: 100vh;
      display: grid;
      grid-template-columns: 1fr 1fr;
      align-items: center;
      padding: 120px 72px 80px;
      position: relative;
      overflow: hidden;
      background: linear-gradient(145deg, #EAF6FB 0%, #D6EEF5 40%, #C8E8D8 80%, #EAF6FB 100%);
    }

    .hero::before {
      content: '';
      position: absolute; inset: 0;
      background:
        radial-gradient(ellipse 55% 55% at 85% 20%, rgba(91,175,201,0.2) 0%, transparent 60%),
        radial-gradient(ellipse 40% 50% at 10% 80%, rgba(111,174,142,0.18) 0%, transparent 55%);
      pointer-events: none;
    }

    /* floating blobs */
    .blob {
      position: absolute;
      border-radius: 50%;
      pointer-events: none;
      animation: drift 14s ease-in-out infinite alternate;
    }
    .blob-1 { width: 380px; height: 380px; background: rgba(91,175,201,0.1); top: -100px; right: -80px; animation-delay: 0s; }
    .blob-2 { width: 240px; height: 240px; background: rgba(111,174,142,0.12); bottom: 60px; left: -60px; animation-delay: -5s; }
    .blob-3 { width: 160px; height: 160px; background: rgba(168,216,234,0.15); top: 35%; left: 45%; animation-delay: -9s; }

    @keyframes drift {
      from { transform: translate(0,0) scale(1); }
      to   { transform: translate(24px, 18px) scale(1.07); }
    }

    .hero-content {
      position: relative;
      z-index: 2;
      animation: fadeUp 1s ease both;
    }

    .hero-eyebrow {
      font-size: 11px;
      letter-spacing: 0.35em;
      text-transform: uppercase;
      color: var(--sky-deep);
      font-weight: 500;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .hero-eyebrow::before {
      content: '';
      display: inline-block;
      width: 32px; height: 1px;
      background: var(--sky-deep);
    }

    .hero-title {
      font-family: 'Cormorant Garamond', serif;
      font-size: clamp(52px, 6vw, 88px);
      font-weight: 300;
      line-height: 1.05;
      color: var(--ink);
      margin-bottom: 28px;
    }

    .hero-title em {
      color: var(--sky-deep);
      font-style: italic;
    }

    .hero-desc {
      font-size: 16px;
      color: var(--ink-soft);
      line-height: 1.75;
      max-width: 420px;
      margin-bottom: 44px;
      font-weight: 300;
    }

    .hero-buttons {
      display: flex;
      gap: 16px;
      flex-wrap: wrap;
    }

    .btn-primary {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      padding: 15px 36px;
      background: var(--ink);
      color: var(--white);
      font-family: 'DM Sans', sans-serif;
      font-size: 13px;
      letter-spacing: 0.15em;
      text-transform: uppercase;
      font-weight: 500;
      text-decoration: none;
      border-radius: 40px;
      transition: all 0.3s ease;
      border: 1.5px solid var(--ink);
    }

    .btn-primary:hover {
      background: var(--sky-deep);
      border-color: var(--sky-deep);
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(91,175,201,0.3);
    }

    .btn-outline {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 15px 32px;
      background: transparent;
      color: var(--ink);
      font-family: 'DM Sans', sans-serif;
      font-size: 13px;
      letter-spacing: 0.15em;
      text-transform: uppercase;
      font-weight: 500;
      text-decoration: none;
      border-radius: 40px;
      border: 1.5px solid rgba(30,42,46,0.25);
      transition: all 0.3s ease;
    }

    .btn-outline:hover {
      border-color: var(--sky-deep);
      color: var(--sky-deep);
      transform: translateY(-2px);
    }

    /* hero image side */
    .hero-visual {
      position: relative;
      z-index: 2;
      display: flex;
      justify-content: center;
      align-items: center;
      animation: fadeUp 1s 0.3s ease both;
    }

    .hero-product-card {
      width: 300px;
      background: rgba(255,255,255,0.7);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255,255,255,0.9);
      border-radius: 28px;
      padding: 40px 32px;
      text-align: center;
      box-shadow: 0 24px 64px rgba(30,42,46,0.1), 0 2px 12px rgba(91,175,201,0.1);
      position: relative;
    }

    .hero-product-card::before {
      content: '';
      position: absolute;
      top: -1px; left: 20px; right: 20px; height: 3px;
      background: linear-gradient(90deg, var(--sky-mid), var(--green-deep));
      border-radius: 0 0 4px 4px;
    }

    .product-emoji {
      font-size: 72px;
      display: block;
      margin-bottom: 20px;
      filter: drop-shadow(0 8px 16px rgba(91,175,201,0.2));
    }

    .product-label {
      font-size: 10px;
      letter-spacing: 0.3em;
      text-transform: uppercase;
      color: var(--sky-deep);
      font-weight: 500;
      margin-bottom: 8px;
    }

    .product-name-card {
      font-family: 'Cormorant Garamond', serif;
      font-size: 22px;
      font-weight: 400;
      color: var(--ink);
      margin-bottom: 8px;
    }

    .product-price-card {
      font-size: 20px;
      font-weight: 500;
      color: var(--sky-deep);
      margin-bottom: 24px;
    }

    .btn-card {
      display: block;
      padding: 12px 28px;
      background: var(--ink);
      color: white;
      border-radius: 30px;
      font-size: 12px;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      text-decoration: none;
      font-weight: 500;
      transition: all 0.25s;
    }

    .btn-card:hover {
      background: var(--sky-deep);
      transform: translateY(-2px);
    }

    /* floating mini card */
    .float-badge {
      position: absolute;
      background: white;
      border-radius: 16px;
      padding: 12px 18px;
      box-shadow: 0 8px 32px rgba(30,42,46,0.1);
      font-size: 12px;
      font-weight: 500;
      color: var(--ink);
      display: flex;
      align-items: center;
      gap: 8px;
      animation: float 3s ease-in-out infinite alternate;
    }

    .float-badge-1 { top: 10px; right: -30px; animation-delay: 0s; }
    .float-badge-2 { bottom: 30px; left: -40px; animation-delay: -1.5s; }

    .float-dot {
      width: 8px; height: 8px;
      border-radius: 50%;
      background: var(--green-deep);
    }

    @keyframes float {
      from { transform: translateY(0); }
      to   { transform: translateY(-8px); }
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(32px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    /* ── TRUST BAR ── */
    .trust-bar {
      background: var(--ink);
      color: var(--sky-mid);
      padding: 16px 48px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 48px;
      flex-wrap: wrap;
    }

    .trust-item {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 12px;
      letter-spacing: 0.15em;
      text-transform: uppercase;
      font-weight: 400;
      opacity: 0.85;
    }

    .trust-item span { font-size: 15px; }

    .trust-sep {
      width: 1px; height: 20px;
      background: rgba(168,216,234,0.2);
    }

    /* ── CATEGORIES ── */
    .section-wrap {
      padding: 100px 72px;
    }

    .section-header {
      margin-bottom: 52px;
    }

    .section-eyebrow {
      font-size: 11px;
      letter-spacing: 0.3em;
      text-transform: uppercase;
      color: var(--sky-deep);
      font-weight: 500;
      margin-bottom: 12px;
    }

    .section-title {
      font-family: 'Cormorant Garamond', serif;
      font-size: clamp(36px, 4vw, 54px);
      font-weight: 400;
      color: var(--ink);
      line-height: 1.1;
    }

    .section-title em { color: var(--sky-deep); font-style: italic; }

    .categories-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 24px;
    }

    .cat-card {
      background: linear-gradient(145deg, rgba(214,238,245,0.4), rgba(200,232,216,0.25));
      border: 1px solid rgba(91,175,201,0.18);
      border-radius: 20px;
      padding: 40px 32px;
      text-decoration: none;
      color: inherit;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .cat-card::after {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(145deg, rgba(91,175,201,0.06), transparent);
      opacity: 0;
      transition: opacity 0.3s;
    }

    .cat-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 16px 40px rgba(91,175,201,0.15);
      border-color: rgba(91,175,201,0.35);
    }

    .cat-card:hover::after { opacity: 1; }

    .cat-icon { font-size: 40px; margin-bottom: 20px; display: block; }

    .cat-name {
      font-family: 'Cormorant Garamond', serif;
      font-size: 24px;
      font-weight: 400;
      color: var(--ink);
      margin-bottom: 8px;
    }

    .cat-desc {
      font-size: 13px;
      color: var(--ink-soft);
      line-height: 1.6;
    }

    .cat-arrow {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-size: 12px;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      color: var(--sky-deep);
      font-weight: 500;
      margin-top: 20px;
    }

    /* ── FEATURED PRODUCTS ── */
    .products-section {
      background: var(--cream);
      padding: 100px 72px;
    }

    .products-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 24px;
    }

    .product-card {
      background: white;
      border-radius: 20px;
      overflow: hidden;
      transition: all 0.3s ease;
      border: 1px solid rgba(91,175,201,0.1);
      text-decoration: none;
      color: inherit;
      display: block;
    }

    .product-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 20px 48px rgba(30,42,46,0.1);
    }

    .product-img {
      height: 200px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 64px;
      background: linear-gradient(145deg, #EAF6FB, #D6EEF5, #C8E8D8);
      position: relative;
    }

    .product-badge {
      position: absolute;
      top: 14px; left: 14px;
      background: var(--sky-deep);
      color: white;
      font-size: 10px;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      font-weight: 500;
      padding: 4px 10px;
      border-radius: 20px;
    }

    .product-body {
      padding: 20px 22px 24px;
    }

    .product-cat-tag {
      font-size: 10px;
      letter-spacing: 0.2em;
      text-transform: uppercase;
      color: var(--sky-deep);
      font-weight: 500;
      margin-bottom: 6px;
    }

    .product-name {
      font-family: 'Cormorant Garamond', serif;
      font-size: 18px;
      font-weight: 400;
      color: var(--ink);
      margin-bottom: 12px;
      line-height: 1.3;
    }

    .product-footer {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .product-price {
      font-size: 17px;
      font-weight: 500;
      color: var(--ink);
    }

    .btn-add {
      width: 34px; height: 34px;
      border-radius: 50%;
      background: var(--ink);
      color: white;
      border: none;
      font-size: 18px;
      display: flex; align-items: center; justify-content: center;
      cursor: pointer;
      transition: all 0.25s;
      line-height: 1;
    }

    .btn-add:hover {
      background: var(--sky-deep);
      transform: scale(1.1);
    }

    /* ── BANNER ── */
    .banner {
      margin: 0 72px;
      background: linear-gradient(135deg, var(--ink) 0%, #2A3F4A 100%);
      border-radius: 24px;
      padding: 72px 80px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 40px;
      position: relative;
      overflow: hidden;
    }

    .banner::before {
      content: '';
      position: absolute;
      top: -60px; right: -60px;
      width: 300px; height: 300px;
      border-radius: 50%;
      background: rgba(91,175,201,0.08);
    }

    .banner::after {
      content: '';
      position: absolute;
      bottom: -80px; left: 200px;
      width: 200px; height: 200px;
      border-radius: 50%;
      background: rgba(111,174,142,0.07);
    }

    .banner-content { position: relative; z-index: 1; }

    .banner-eyebrow {
      font-size: 11px;
      letter-spacing: 0.3em;
      text-transform: uppercase;
      color: var(--sky-mid);
      font-weight: 500;
      margin-bottom: 14px;
    }

    .banner-title {
      font-family: 'Cormorant Garamond', serif;
      font-size: clamp(32px, 3.5vw, 48px);
      font-weight: 300;
      color: white;
      line-height: 1.15;
      margin-bottom: 12px;
    }

    .banner-title em { color: var(--sky-mid); font-style: italic; }

    .banner-desc {
      font-size: 15px;
      color: rgba(214,238,245,0.7);
      margin-bottom: 32px;
      max-width: 400px;
      font-weight: 300;
      line-height: 1.7;
    }

    .btn-light {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      padding: 15px 36px;
      background: white;
      color: var(--ink);
      font-size: 13px;
      letter-spacing: 0.15em;
      text-transform: uppercase;
      font-weight: 500;
      text-decoration: none;
      border-radius: 40px;
      transition: all 0.3s;
    }

    .btn-light:hover {
      background: var(--sky-mid);
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(91,175,201,0.3);
    }

    .banner-coupon {
      position: relative;
      z-index: 1;
      background: rgba(255,255,255,0.07);
      border: 1px solid rgba(255,255,255,0.15);
      border-radius: 20px;
      padding: 36px 48px;
      text-align: center;
      flex-shrink: 0;
    }

    .coupon-pct {
      font-family: 'Cormorant Garamond', serif;
      font-size: 72px;
      font-weight: 300;
      color: var(--sky-mid);
      line-height: 1;
      margin-bottom: 4px;
    }

    .coupon-off {
      font-size: 14px;
      letter-spacing: 0.2em;
      color: rgba(255,255,255,0.6);
      text-transform: uppercase;
      margin-bottom: 16px;
    }

    .coupon-code-box {
      background: rgba(91,175,201,0.15);
      border: 1px dashed rgba(91,175,201,0.4);
      border-radius: 10px;
      padding: 10px 24px;
      font-family: monospace;
      font-size: 18px;
      color: var(--sky-mid);
      letter-spacing: 0.15em;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.25s;
    }

    .coupon-code-box:hover {
      background: rgba(91,175,201,0.25);
      color: white;
    }

    /* ── FOOTER ── */
    footer {
      background: var(--ink);
      color: rgba(214,238,245,0.6);
      padding: 60px 72px 32px;
      margin-top: 100px;
    }

    .footer-top {
      display: flex;
      justify-content: space-between;
      gap: 48px;
      margin-bottom: 48px;
      flex-wrap: wrap;
    }

    .footer-brand .logo {
      font-family: 'Cormorant Garamond', serif;
      font-size: 32px;
      font-weight: 300;
      color: white;
      letter-spacing: 0.08em;
      margin-bottom: 12px;
    }

    .footer-brand .logo span { color: var(--sky-mid); font-style: italic; }

    .footer-tagline {
      font-size: 13px;
      color: rgba(214,238,245,0.5);
      font-style: italic;
      font-family: 'Cormorant Garamond', serif;
    }

    .footer-links h4 {
      font-size: 11px;
      letter-spacing: 0.25em;
      text-transform: uppercase;
      color: rgba(214,238,245,0.4);
      font-weight: 500;
      margin-bottom: 16px;
    }

    .footer-links ul { list-style: none; }

    .footer-links ul li { margin-bottom: 10px; }

    .footer-links ul li a {
      color: rgba(214,238,245,0.65);
      text-decoration: none;
      font-size: 14px;
      transition: color 0.2s;
    }

    .footer-links ul li a:hover { color: var(--sky-mid); }

    .footer-bottom {
      border-top: 1px solid rgba(214,238,245,0.08);
      padding-top: 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-size: 12px;
      color: rgba(214,238,245,0.3);
    }

    /* ── RESPONSIVE ── */
    @media (max-width: 1024px) {
      .hero { grid-template-columns: 1fr; padding: 120px 40px 80px; text-align: center; }
      .hero-eyebrow { justify-content: center; }
      .hero-desc { margin: 0 auto 44px; }
      .hero-buttons { justify-content: center; }
      .hero-visual { margin-top: 48px; }
      .float-badge-1, .float-badge-2 { display: none; }
      .categories-grid { grid-template-columns: 1fr 1fr; }
      .products-grid { grid-template-columns: 1fr 1fr; }
      .section-wrap, .products-section { padding: 72px 40px; }
      .banner { margin: 0 40px; padding: 48px 40px; flex-direction: column; }
      footer { padding: 48px 40px 24px; }
    }

    @media (max-width: 640px) {
      nav { padding: 16px 24px; }
      .nav-links { display: none; }
      .hero { padding: 100px 24px 60px; }
      .categories-grid, .products-grid { grid-template-columns: 1fr; }
      .section-wrap, .products-section { padding: 60px 24px; }
      .banner { margin: 0 24px; padding: 40px 28px; }
      footer { padding: 40px 24px 20px; }
      .trust-bar { gap: 20px; padding: 16px 24px; }
      .trust-sep { display: none; }
    }
  </style>
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
  </div>
</nav>

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