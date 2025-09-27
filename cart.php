<?php
require_once __DIR__.'/includes/db.php';
if(session_status()===PHP_SESSION_NONE) session_start();
require_once __DIR__.'/includes/header.php';
// Corrected line: Check for 'user_id' instead of 'user'
if(!isset($_SESSION['user_id'])){
    echo '<div class="alert alert-warning">Please <a href="/login.php">login</a> to see cart.</div>';
    require_once __DIR__.'/includes/footer.php';
    exit;
}
// Corrected line: Get user_id from the correct session variable
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare('SELECT c.id as cart_id FROM carts c WHERE c.user_id=?');
$stmt->execute([$user_id]);
$cart = $stmt->fetch();
$items = [];
if($cart){
    $stmt = $pdo->prepare('SELECT ci.*, p.title, p.price, p.image FROM cart_items ci JOIN products p ON ci.product_id = p.id WHERE ci.cart_id = ?');
    $stmt->execute([$cart['cart_id']]);
    $items = $stmt->fetchAll();
}
$total = 0; foreach($items as $it) $total += $it['price'] * $it['quantity'];
?>
<main class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4 text-green-700">Your Cart</h2>
    <?php if(empty($items)): ?>
        <div class="bg-yellow-100 text-yellow-800 p-4 rounded shadow mb-4">Cart is empty</div>
    <?php else: ?>
    <div class="overflow-x-auto">
    <table class="min-w-full bg-white rounded shadow-md">
        <thead class="bg-green-100">
            <tr>
                <th class="py-2 px-4 text-left">Image</th>
                <th class="py-2 px-4 text-left">Product</th>
                <th class="py-2 px-4 text-left">Price</th>
                <th class="py-2 px-4 text-left">Qty</th>
                <th class="py-2 px-4 text-left">Subtotal</th>
                <th class="py-2 px-4 text-left">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($items as $it): ?>
            <tr class="border-b">
                <td class="py-2 px-4">
                    <?php
                    $imgPath = $it['image'];
                    if(strpos($imgPath, 'assets/images/') === 0){
                        $imgSrc = $imgPath;
                    } else {
                        $imgSrc = '/uploads/' . $imgPath;
                    }
                    ?>
                    <img src="<?php echo !empty($imgPath) ? htmlspecialchars($imgSrc) : '/assets/images/logo.png'; ?>" alt="<?php echo htmlspecialchars($it['title']); ?>" class="w-16 h-16 object-cover rounded">
                </td>
                <td class="py-2 px-4 font-semibold text-gray-800"><?php echo htmlspecialchars($it['title']); ?></td>
                <td class="py-2 px-4 text-green-700">₹<?php echo $it['price']; ?></td>
                <td class="py-2 px-4">
                    <span class="inline-block px-2 py-1 bg-green-50 rounded"><?php echo $it['quantity']; ?></span>
                </td>
                <td class="py-2 px-4 font-bold">₹<?php echo $it['price'] * $it['quantity']; ?></td>
                <td class="py-2 px-4">
                    <form method="POST" action="cart_remove.php" onsubmit="return confirm('Remove this item?');">
                        <input type="hidden" name="cart_item_id" value="<?php echo $it['id']; ?>">
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded transition">Remove</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <div class="flex justify-between items-center mt-6">
        <div class="text-xl font-bold text-green-800">Total: ₹<?php echo $total; ?></div>
        <a href="payment_method.php" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded shadow transition">Checkout</a>
    </div>
    <?php endif; ?>
</main>
<script>
// Animate remove button
document.querySelectorAll('form[action="cart_remove.php"] button').forEach(btn => {
    btn.addEventListener('click', function(e) {
        btn.classList.add('scale-95');
        setTimeout(()=>btn.classList.remove('scale-95'), 200);
    });
});
</script>
<?php require_once __DIR__.'/includes/footer.php'; ?>