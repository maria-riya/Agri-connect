<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php'; // For session check
include __DIR__ . '/includes/header.php';

// Check if user is logged in and if an order ID is provided
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    echo '<div class="alert alert-warning">Invalid request.</div>';
    include __DIR__ . '/includes/footer.php';
    exit;
}
$order_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

// Fetch order details for the logged-in user
$order_stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ? AND user_id = ?');
$order_stmt->execute([$order_id, $user_id]);
$order = $order_stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo '<div class="alert alert-warning">Order not found.</div>';
    include __DIR__ . '/includes/footer.php';
    exit;
}

// Fetch order items
$items_stmt = $pdo->prepare('SELECT oi.*, p.title, p.image FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?');
$items_stmt->execute([$order_id]);
$items = $items_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="container mx-auto p-4 py-8">
    <div class="max-w-2xl mx-auto text-center bg-green-50 p-8 rounded-lg shadow-lg border border-green-200">
        <div class="flex flex-col items-center mb-6">
            <i class="fas fa-check-circle text-6xl text-green-600 mb-4 animate-bounce"></i>
            <h1 class="text-3xl font-bold text-green-800">Order Placed Successfully!</h1>
            <p class="text-lg text-gray-600 mt-2">Your order #<?php echo htmlspecialchars($order['id']); ?> has been confirmed.</p>
        </div>

        <div class="text-left bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-bold mb-4">Order Details</h2>
            <div class="space-y-2">
                <p><strong>Total Amount:</strong> ₹<?php echo number_format($order['total'], 2); ?></p>
                <p><strong>Status:</strong> <span class="bg-yellow-200 text-yellow-800 text-sm font-semibold px-2 py-1 rounded-full"><?php echo ucfirst($order['status']); ?></span></p>
                <p><strong>Date:</strong> <?php echo date('F j, Y', strtotime($order['created_at'])); ?></p>
            </div>
            <h3 class="font-bold mt-4 mb-2">Items Ordered:</h3>
            <ul class="list-disc list-inside space-y-2 text-gray-700">
                <?php foreach ($items as $item): ?>
                    <li>
                        <?php echo htmlspecialchars($item['title']); ?> (x<?php echo $item['quantity']; ?>) - ₹<?php echo number_format($item['price'], 2); ?> each
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <a href="index.php" class="inline-block bg-blue-600 text-white font-semibold py-3 px-8 rounded-full hover:bg-blue-700 transition-colors duration-300">
            Continue Shopping
        </a>
    </div>
</main>
<script src="https://kit.fontawesome.com/2c36e9b7b1.js" crossorigin="anonymous"></script>
<?php include __DIR__ . '/includes/footer.php'; ?>