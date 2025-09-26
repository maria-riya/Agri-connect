
<?php
require_once dirname(__DIR__) . '/includes/db.php';
include dirname(__DIR__) . '/includes/header.php';

// Get product ID from query
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    echo '<div class="container mx-auto p-8 text-center text-red-600">Invalid product ID.</div>';
    include dirname(__DIR__) . '/includes/footer.php';
    exit;
}
// Fetch product details
$sql = "SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$product) {
    echo '<div class="container mx-auto p-8 text-center text-red-600">Product not found.</div>';
    include dirname(__DIR__) . '/includes/footer.php';
    exit;
}
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
    <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-xl p-8 flex flex-col md:flex-row gap-10 items-center">
        <div class="md:w-1/2 w-full flex justify-center items-center">
            <?php
            $imgPath = $product['image'];
            if(strpos($imgPath, 'assets/images/') === 0){
                $imgSrc = '../' . $imgPath;
            } else {
                $imgSrc = '../uploads/products/' . $imgPath;
            }
            ?>
            <img src="<?= htmlspecialchars($imgSrc) ?>" alt="<?= htmlspecialchars($product['title']) ?>" class="rounded-xl shadow-lg w-full max-w-xs object-cover border-2 border-green-200" style="height:320px;" onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 300 200%22><rect width=%22300%22 height=%22200%22 fill=%22%23f0f0f0%22/><text x=%22150%22 y=%22100%22 text-anchor=%22middle%22 dy=%22.3em%22 font-family=%22Arial%22 font-size=%2216%22 fill=%22%23999%22>No Image</text></svg>'">
        </div>
        <div class="md:w-1/2 w-full">
            <h2 class="text-4xl font-extrabold text-green-700 mb-3 leading-tight"><?= htmlspecialchars($product['title']) ?></h2>
            <div class="flex items-center gap-3 mb-2">
                <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">Category: <?= htmlspecialchars($product['category_name']) ?></span>
            </div>
            <p class="text-2xl font-bold text-brown-700 mb-4">₹<?= number_format($product['price'], 2) ?> <span class="text-base font-normal text-gray-500">/kg</span></p>
            <p class="mb-6 text-gray-700 text-base leading-relaxed">Description: <?= htmlspecialchars($product['description']) ?></p>
            <div class="flex gap-4 mt-8 items-center">
                <button type="button" id="showLoginModalBtn" class="btn-add-cart flex items-center justify-center gap-2 bg-gradient-to-r from-green-500 to-green-700 text-white px-8 py-4 rounded-full font-bold text-lg shadow-lg border-2 border-green-600" style="opacity:1;cursor:pointer; min-width:180px;">
                    <i class="fas fa-shopping-cart text-xl"></i>
                    <span class="whitespace-nowrap">Add to Cart</span>
                </button>
                <span class="text-red-600 text-base font-semibold ml-2">(Login required to purchase)</span>
            </div>
        </div>
    </div>
</main>
<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var loginModal = document.getElementById('loginModal');
    var closeModalBtn = document.getElementById('closeModalBtn');
    var showLoginModalBtn = document.getElementById('showLoginModalBtn');
    if (showLoginModalBtn) {
        showLoginModalBtn.addEventListener('click', function(e) {
            e.preventDefault();
            loginModal.classList.remove('hidden');
        });
    }
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', function() {
            loginModal.classList.add('hidden');
        });
    }
    // Optional: close modal on background click
    if (loginModal) {
        loginModal.addEventListener('click', function(e) {
            if (e.target === loginModal) {
                loginModal.classList.add('hidden');
            }
        });
    }
});
</script>
