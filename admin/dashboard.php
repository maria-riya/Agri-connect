<?php
require_once __DIR__ . "/../includes/db.php";
require_once __DIR__ . "/../includes/auth.php";
requireAdmin();
include __DIR__ . "/../includes/header.php";

$user_count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$product_count = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$order_count = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
?>

<main class="container mx-auto p-4">
    <h2 class="text-3xl font-bold mb-6 text-green-800">Admin Dashboard</h2>
    <p class="text-gray-600 mb-8">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>! Here is a summary of your marketplace.</p>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        <div class="bg-white p-6 rounded-xl shadow-lg border border-green-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-green-700">Total Users</h3>
                <i class="fas fa-users text-2xl text-green-500"></i>
            </div>
            <p class="text-gray-600 mb-4">
                <span class="text-4xl font-bold text-green-800"><?php echo $user_count; ?></span>
                <span class="text-sm text-gray-500"> registered accounts</span>
            </p>
            <a href="users.php" class="inline-block text-blue-600 hover:underline">Manage Users &rarr;</a>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-lg border border-yellow-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-yellow-700">Total Products</h3>
                <i class="fas fa-box-open text-2xl text-yellow-500"></i>
            </div>
            <p class="text-gray-600 mb-4">
                <span class="text-4xl font-bold text-yellow-800"><?php echo $product_count; ?></span>
                <span class="text-sm text-gray-500"> products listed</span>
            </p>
            <a href="fertilizers.php" class="inline-block text-blue-600 hover:underline">Manage Products &rarr;</a>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-lg border border-blue-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-blue-700">Total Orders</h3>
                <i class="fas fa-clipboard-list text-2xl text-blue-500"></i>
            </div>
            <p class="text-gray-600 mb-4">
                <span class="text-4xl font-bold text-blue-800"><?php echo $order_count; ?></span>
                <span class="text-sm text-gray-500"> orders in total</span>
            </p>
            <a href="orders.php" class="inline-block text-blue-600 hover:underline">View All Orders &rarr;</a>
        </div>
        
    </div>
    
    <div class="mt-10">
        <h3 class="text-2xl font-bold mb-4 text-green-700">Quick Navigation</h3>
        <ul class="list-disc list-inside space-y-2">
            <li><a href="products.php" class="text-blue-600 hover:underline">Manage Products</a></li>
            <li><a href="categories.php" class="text-blue-600 hover:underline">Manage Categories</a></li>
            <li><a href="users.php" class="text-blue-600 hover:underline">Manage Users</a></li>
        </ul>
    </div>
</main>
<?php 
include __DIR__ . "/../includes/footer.php"; 
?>