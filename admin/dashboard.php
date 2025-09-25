<?php
// Include database connection and authentication functions
require_once __DIR__ . "/../includes/db.php";
require_once __DIR__ . "/../includes/auth.php";

// Ensure only admin can access this page

// Include the header
include __DIR__ . "/../includes/header.php";
?>

<main class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Admin Dashboard</h2>
    <p class="mb-6">Welcome, Admin. Manage products, categories, users, and orders here.</p>
    <ul class="list-disc list-inside space-y-2">
        <li><a href="products.php" class="text-green-700 hover:underline">Manage Products</a></li>
        <li><a href="categories.php" class="text-green-700 hover:underline">Manage Categories</a></li>
        <li><a href="orders.php" class="text-green-700 hover:underline">View Orders</a></li>
        <li><a href="users.php" class="text-green-700 hover:underline">Manage Users</a></li>
    </ul>
</main>

<?php 
// Include the footer
include __DIR__ . "/../includes/footer.php"; 
?>
