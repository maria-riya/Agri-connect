<?php
require_once __DIR__ . "/../includes/db.php";
require_once __DIR__ . "/../includes/auth.php";
requireSeller();
include __DIR__ . "/../includes/header.php";
?>

<main class="container mx-auto p-4">
    <h2 class="text-3xl font-bold mb-6 text-green-800">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
    <p class="text-gray-600 mb-8">This is your central hub to manage your products and orders.</p>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        <div class="bg-white p-6 rounded-xl shadow-lg border border-green-200 hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-green-700">Manage My Tubers</h3>
                <i class="fas fa-carrot text-2xl text-green-500"></i>
            </div>
            <p class="text-gray-600 mb-4">
                Add, update, or remove your tuber listings. Keep your inventory up-to-date.
            </p>
            <a href="products.php" class="inline-block bg-green-600 text-white font-semibold py-2 px-4 rounded-full hover:bg-green-700 transition-colors duration-300">
                Go to Products
            </a>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-lg border border-yellow-200 hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-yellow-700">View Orders</h3>
                <i class="fas fa-clipboard-list text-2xl text-yellow-500"></i>
            </div>
            <p class="text-gray-600 mb-4">
                Check the status of new and ongoing orders from your customers. Fulfill them in a timely manner.
            </p>
            <a href="orders.php" class="inline-block bg-yellow-600 text-white font-semibold py-2 px-4 rounded-full hover:bg-yellow-700 transition-colors duration-300">
                Go to Orders
            </a>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-gray-700">Quick Actions</h3>
                <i class="fas fa-plus-circle text-2xl text-gray-500"></i>
            </div>
            <ul class="space-y-2">
                <li><a href="add_product.php" class="text-blue-600 hover:underline"><i class="fas fa-plus"></i> Add New Tuber</a></li>
                <li><a href="products.php" class="text-blue-600 hover:underline"><i class="fas fa-edit"></i> Edit an Existing Product</a></li>
            </ul>
        </div>
    </div>
</main>
<?php include __DIR__ . "/../includes/footer.php"; ?>