<?php
require_once __DIR__ . "/includes/db.php";
include __DIR__ . "/includes/header.php";

// Fetch all products
$sql = "SELECT p.*, c.name AS category_name, AVG(r.rating) as avg_rating, COUNT(r.id) as review_count
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN reviews r ON p.id = r.product_id
        GROUP BY p.id
        ORDER BY p.created_at DESC";
$stmt = $pdo->query($sql);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<main class="container mx-auto p-4 py-8">
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-green-700">All Products</h2>
        <p class="text-gray-600">Browse our full selection of farm-fresh tubers.</p>
    </div>
    
    <div class="products-grid" id="productsGrid">
        <?php if ($products): ?>
            <?php foreach ($products as $index => $row): ?>
                <div class="product-card" style="animation-delay: <?= ($index * 0.1) ?>s;">
                    <div class="product-image">
                        <img src="uploads/products/<?= htmlspecialchars($row['image']) ?>" 
                             alt="<?= htmlspecialchars($row['title']) ?>"
                             onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 300 200%22><rect width=%22300%22 height=%22200%22 fill=%22%23f0f0f0%22/><text x=%22150%22 y=%22100%22 text-anchor=%22middle%22 dy=%22.3em%22 font-family=%22Arial%22 font-size=%2216%22 fill=%22%23999%22>No Image</text></svg>'">
                        <div class="product-badge">Fresh</div>
                        <div class="product-favorite">
                            <i class="far fa-heart"></i>
                        </div>
                    </div>
                    
                    <div class="product-content">
                        <h3 class="product-title"><?= htmlspecialchars($row['title']) ?></h3>
                        <p class="product-description">Category: <?= htmlspecialchars($row['category_name']) ?></p>
                        
                        <div class="product-meta">
                            <div class="product-pricing">
                                <span class="product-price">₹<?= number_format($row['price'], 2) ?></span>
                                <span class="product-unit">/kg</span>
                            </div>
                            
                            <div class="product-rating">
                                <div class="rating-stars">
                                    <?php
                                        $rating = round($row['avg_rating']);
                                        for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fas fa-star star <?php if($i > $rating) echo 'empty'; ?>"></i>
                                    <?php endfor; ?>
                                </div>
                                <span class="rating-count">(<?= $row['review_count'] ?>)</span>
                            </div>
                        </div>
                        
       <div class="product-actions">
    <a href="product.php?id=<?= $row['id'] ?>" class="btn-view">
        <i class="fas fa-eye"></i>
        View Details
    </a>
    <form action="cart_add.php" method="POST" class="add-to-cart-form">
        <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
        <input type="hidden" name="quantity" value="1">
        <button type="submit" class="btn-add-cart">
            <i class="fas fa-shopping-cart"></i>
        </button>
    </form>
</div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-products">
                <i class="fas fa-seedling"></i>
                <h3>No Products Found</h3>
                <p>We're working hard to stock fresh tubers. Please check back soon!</p>
            </div>
        <?php endif; ?>
    </div>
</main>
<?php include __DIR__ . "/includes/footer.php"; ?>