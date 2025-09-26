<?php
require_once dirname(__DIR__) . '/includes/db.php';
include dirname(__DIR__) . '/includes/header.php';

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
<!-- Login Required Modal -->
<div id="loginModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg p-8 max-w-sm w-full text-center">
        <h3 class="text-xl font-bold mb-4 text-green-700">Login Required</h3>
        <p class="mb-6 text-gray-700">To purchase products, you need to login.</p>
        <a href="../login.php" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded font-semibold">Login</a>
        <button id="closeModalBtn" class="ml-4 px-6 py-2 rounded border border-gray-300 text-gray-600 hover:bg-gray-100">Cancel</button>
    </div>
</div>
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-green-700">All Products</h2>
        <p class="text-gray-600">Browse our full selection of farm-fresh tubers.</p>
    </div>
    
    <div class="products-grid" id="productsGrid">
        <?php if ($products): ?>
            <?php foreach ($products as $index => $row): ?>
                <div class="product-card" style="animation-delay: <?= ($index * 0.1) ?>s;">
                    <div class="product-image">
                        <?php
                        $imgPath = $row['image'];
                        if(strpos($imgPath, 'assets/images/') === 0){
                            $imgSrc = $imgPath;
                        } else {
                            $imgSrc = 'uploads/products/' . $imgPath;
                        }
                        ?>
                        <img src="<?= htmlspecialchars($imgSrc) ?>" 
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
    <a href="product_guest.php?id=<?= $row['id'] ?>" class="btn-view">
        <i class="fas fa-eye"></i>
        View Details
    </a>
    <div class="add-to-cart-form" data-product-id="<?= $row['id'] ?>">
        <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
        <input type="hidden" name="quantity" value="1">
        <button type="button" class="btn-add-cart">
            <i class="fas fa-shopping-cart"></i> Add to Cart
        </button>
    </div>
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    var loginModal = document.getElementById('loginModal');
    var closeModalBtn = document.getElementById('closeModalBtn');
    document.querySelectorAll('.add-to-cart-form .btn-add-cart').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            loginModal.classList.remove('hidden');
        });
    });
    closeModalBtn.addEventListener('click', function() {
        loginModal.classList.add('hidden');
    });
    // Optional: close modal on background click
    loginModal.addEventListener('click', function(e) {
        if (e.target === loginModal) {
            loginModal.classList.add('hidden');
        }
    });
    // Cart count logic (unchanged)
    fetch('cart_count.php')
        .then(res => res.json())
        .then(data => {
            const cartCount = document.getElementById('cart-count');
            if(cartCount) cartCount.textContent = data.count;
        });
});
</script>
<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
