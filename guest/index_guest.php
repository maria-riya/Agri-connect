<?php require_once __DIR__ . '/../includes/db.php';
include __DIR__ . '/../includes/header.php';
// ...existing code...
// Fetch products along with their average rating
$sql = "SELECT p.*, AVG(r.rating) as avg_rating, COUNT(r.id) as review_count
        FROM products p
        LEFT JOIN reviews r ON p.id = r.product_id
        GROUP BY p.id
        ORDER BY p.created_at DESC
        LIMIT 6";
$stmt = $pdo->query($sql);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fresh Tubers - The Tuber Cart</title>
    <link href="../https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/index.css">
</head>
<body>
    <section class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title">Fresh Premium Tubers</h1>
            <p class="hero-subtitle">Farm-fresh tubers delivered straight to your doorstep. Quality guaranteed, nutrition preserved.</p>
            <div class="hero-stats">
                <div class="stat-item">
                    <span class="stat-number">500+</span>
                    <span class="stat-label">Happy Customers</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">50+</span>
                    <span class="stat-label">Tuber Varieties</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">24h</span>
                    <span class="stat-label">Fresh Delivery</span>
                </div>
            </div>
        </div>
    </section>
    <section class="products-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Available Products</h2>
                <p class="section-description">Discover our premium selection of fresh tubers, carefully sourced from trusted local farms and delivered with care.</p>
            </div>
            <div class="products-grid" id="productsGrid">
                <?php if ($rows): ?>
                    <?php foreach ($rows as $index => $row): ?>
                        <div class="product-card" style="animation-delay: <?= ($index * 0.1) ?>s;">
                            <div class="product-image">
                                <?php
                                $imgPath = $row['image'];
                                if(strpos($imgPath, 'assets/images/') === 0){
                                    $imgSrc = '../' . $imgPath;
                                } else {
                                    $imgSrc = '../uploads/products/' . $imgPath;
                                }
                                ?>
                                <img src="<?= htmlspecialchars($imgSrc) ?>" alt="<?= htmlspecialchars($row['title']) ?>" onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 300 200%22><rect width=%22300%22 height=%22200%22 fill=%22%23f0f0f0%22/><text x=%22150%22 y=%22100%22 text-anchor=%22middle%22 dy=%22.3em%22 font-family=%22Arial%22 font-size=%2216%22 fill=%22%23999%22>No Image</text></svg>'">
                                <div class="product-badge">Fresh</div>
                            </div>
                            <div class="product-content">
                                <h3 class="product-title"><?= htmlspecialchars($row['title']) ?></h3>
                                <div class="product-meta">
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
        </div>
    </section>
</body>
</html>
<?php include __DIR__ . '/../includes/footer.php'; ?>
