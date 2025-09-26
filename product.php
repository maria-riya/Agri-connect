<?php
require_once __DIR__.'/includes/db.php';
require_once __DIR__.'/includes/header.php';
if(!isset($_GET['id'])){ echo '<div class="alert alert-warning">Product not found</div>'; require_once __DIR__.'/includes/footer.php'; exit; }
$id = (int)$_GET['id'];
$stmt = $pdo->prepare('SELECT p.*, c.name as category_name, u.name as seller_name FROM products p LEFT JOIN categories c ON p.category_id=c.id LEFT JOIN users u ON p.seller_id=u.id WHERE p.id=?');
$stmt->execute([$id]); $p = $stmt->fetch(); if(!$p){ echo '<div class="alert alert-warning">Product not found</div>'; require_once __DIR__.'/includes/footer.php'; exit; }
?>
<main class="container mx-auto p-6">
    <div class="flex flex-col md:flex-row bg-white rounded shadow-lg overflow-hidden">
        <div class="md:w-1/2 flex justify-center items-center p-6 bg-gray-50">
            <img src="<?php echo !empty($p['image']) ? '/uploads/' . htmlspecialchars($p['image']) : '/assets/images/logo.png'; ?>" alt="<?php echo htmlspecialchars($p['title']); ?>" class="w-72 h-72 object-cover rounded-lg shadow">
        </div>
        <div class="md:w-1/2 p-6 flex flex-col justify-between">
            <div>
                <h1 class="text-3xl font-bold text-green-700 mb-2"><?php echo htmlspecialchars($p['title']); ?></h1>
                <p class="text-gray-600 mb-4"><?php echo nl2br(htmlspecialchars($p['description'])); ?></p>
                <div class="mb-2 text-lg"><span class="font-semibold text-green-800">Price:</span> ₹<?php echo $p['price']; ?></div>
                <div class="mb-2"><span class="font-semibold">Category:</span> <?php echo htmlspecialchars($p['category_name']); ?></div>
                <div class="mb-2"><span class="font-semibold">Seller:</span> <?php echo htmlspecialchars($p['seller_name']); ?></div>
            </div>
           <form method="POST" action="cart_add.php" class="flex items-center gap-2 mt-4">
    <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
    <input name="quantity" type="number" min="1" value="1" class="border rounded px-2 py-1 w-20" aria-label="Quantity">
    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow transition flex items-center gap-2"><i class="fas fa-cart-plus"></i> Add to Cart</button>
</form>
            <form method="POST" action="/checkout.php" class="mt-2">
                <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded shadow transition w-full flex items-center justify-center gap-2"><i class="fas fa-bolt"></i> Buy Now</button>
            </form>
        </div>
    </div>
    <section class="mt-8">
        <h2 class="text-xl font-bold mb-4 text-green-700">Reviews</h2>
        <?php
        $revSt = $pdo->prepare('SELECT r.*, u.name FROM reviews r JOIN users u ON r.user_id=u.id WHERE r.product_id=? ORDER BY r.created_at DESC'); $revSt->execute([$p['id']]); $reviews = $revSt->fetchAll();
        if(empty($reviews)) echo '<div class="text-gray-500">No reviews yet.</div>';
        foreach($reviews as $r){
            echo '<div class="mb-4 p-4 bg-gray-50 rounded shadow"><strong class="text-green-700">'.htmlspecialchars($r['name']).'</strong> — <span class="text-yellow-500">'.str_repeat('★',$r['rating']).'</span><br><span class="text-gray-700">'.htmlspecialchars($r['comment']).'</span></div>';
        }
        ?>
        <?php // Corrected line: Check for 'user_id' instead of 'user'
        if(isset($_SESSION['user_id'])): ?>
        <form method="POST" action="/review_submit.php" class="mt-4 p-4 bg-white rounded shadow">
            <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
            <div class="mb-2">
                <label for="rating" class="block font-semibold mb-1">Rating</label>
                <select name="rating" id="rating" class="border rounded px-2 py-1">
                    <option>5</option><option>4</option><option>3</option><option>2</option><option>1</option>
                </select>
            </div>
            <div class="mb-2">
                <label for="comment" class="block font-semibold mb-1">Comment</label>
                <textarea name="comment" id="comment" class="border rounded px-2 py-1 w-full" placeholder="Write review"></textarea>
            </div>
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">Submit Review</button>
        </form>
        <?php else: ?>
        <div class="mt-4"><a href="../login.php" class="text-blue-600 hover:underline">Login</a> to write a review.</div>
        <?php endif; ?>
    </section>
</main>
<script src="https://kit.fontawesome.com/2c36e9b7b1.js" crossorigin="anonymous"></script>
<script>
// Add to Cart button animation
document.querySelectorAll('button[type="submit"]').forEach(btn => {
    btn.addEventListener('click', function(e) {
        btn.classList.add('scale-95');
        setTimeout(()=>btn.classList.remove('scale-95'), 200);
    });
});
</script>
<?php require_once __DIR__.'/includes/footer.php'; ?>