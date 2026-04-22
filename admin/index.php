<?php
require_once __DIR__ . '/includes/products_db.php';
$featured = getFeaturedProducts(4);
?>

<div class="products-grid">
<?php foreach ($featured as $product): ?>
    <a href="pages/product.php?id=<?php echo $product['id']; ?>" class="product-card">
        <div class="product-img product-img-real">
            <img src="assets/images/<?php echo htmlspecialchars($product['image']); ?>"
                 alt="<?php echo htmlspecialchars($product['name']); ?>">
        </div>
        <div class="product-body">
            <div class="product-cat-tag"><?php echo ucfirst($product['type']); ?></div>
            <div class="product-name"><?php echo htmlspecialchars($product['name']); ?></div>
            <div class="product-footer">
                <span class="product-price">
                    <?php echo number_format($product['price'], 2); ?> L
                </span>
                <button class="btn-add" onclick="event.preventDefault()">+</button>
            </div>
        </div>
    </a>
<?php endforeach; ?>
</div>