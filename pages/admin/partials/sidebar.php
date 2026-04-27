<aside class="admin-sidebar">
    <div class="admin-logo">
        <a href="/cleare/index.php">Clear<span>è</span></a>
        <small>Admin Panel</small>
    </div>
    <nav class="admin-nav">
        <a href="/cleare/pages/admin/index.php"
           class="admin-nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : ''; ?>">
            📊 Dashboard
        </a>
        <a href="/cleare/pages/admin/products.php"
           class="admin-nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'products.php' ? 'active' : ''; ?>">
            🧴 Products
        </a>
        <a href="/cleare/pages/admin/orders.php"
           class="admin-nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'orders.php' ? 'active' : ''; ?>">
            📦 Orders
        </a>
        <a href="/cleare/pages/admin/coupons.php"
           class="admin-nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'coupons.php' ? 'active' : ''; ?>">
            🏷️ Coupons
        </a>
    </nav>
</aside>