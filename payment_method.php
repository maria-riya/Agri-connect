<?php
require_once __DIR__ . '/includes/db.php';
if(session_status()===PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/includes/header.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])){
    header('Location:/login.php');
    exit;
}
$user_id = $_SESSION['user_id'];

// Get user's cart
$stmt = $pdo->prepare('SELECT c.id as cart_id FROM carts c WHERE c.user_id=?');
$stmt->execute([$user_id]);
$cart = $stmt->fetch();

// If no cart, redirect back
if(!$cart){
    header('Location:/cart.php');
    exit;
}

// Calculate cart total
$stmt = $pdo->prepare('SELECT ci.*, p.price FROM cart_items ci JOIN products p ON ci.product_id=p.id WHERE ci.cart_id=?');
$stmt->execute([$cart['cart_id']]);
$items = $stmt->fetchAll();
$total = 0;
foreach($items as $it) $total += $it['price'] * $it['quantity'];

if(empty($items)){
    header('Location:/cart.php');
    exit;
}
?>

<main class="container mx-auto p-4 py-8">
    <div class="max-w-xl mx-auto">
        <h2 class="text-2xl font-bold mb-4 text-green-700">Checkout</h2>
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h3 class="text-xl font-semibold mb-2">Order Summary</h3>
            <p class="text-lg">Total Amount: <span class="font-bold text-green-700">₹<?php echo number_format($total, 2); ?></span></p>
        </div>

        <h3 class="text-xl font-bold mb-4">Select a Payment Method</h3>
        <div class="space-y-4">
            <form method="POST" action="checkout.php">
                <input type="hidden" name="payment_method" value="cod">
                <button type="submit" class="w-full text-left p-6 border rounded-lg shadow-sm flex items-center justify-between transition-colors hover:bg-gray-50">
                    <div class="flex items-center space-x-4">
                        <i class="fas fa-money-bill-wave text-2xl text-green-600"></i>
                        <div>
                            <span class="block text-lg font-bold">Cash on Delivery</span>
                            <span class="block text-sm text-gray-500">Pay with cash when your order is delivered.</span>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400"></i>
                </button>
            </form>

            <form method="POST" action="payment_process.php">
                <input type="hidden" name="payment_method" value="online">
                <button type="submit" class="w-full text-left p-6 border rounded-lg shadow-sm flex items-center justify-between transition-colors hover:bg-gray-50">
                    <div class="flex items-center space-x-4">
                        <i class="fas fa-credit-card text-2xl text-blue-600"></i>
                        <div>
                            <span class="block text-lg font-bold">Online Payment</span>
                            <span class="block text-sm text-gray-500">Pay securely with UPI, card, or net banking.</span>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400"></i>
                </button>
            </form>
        </div>
    </div>
</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>