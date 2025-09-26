<?php
// Start session to check login status
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Tuber Cart</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/index.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen">
    <nav class="tuber-nav sticky top-0 z-10 py-4 bg-white shadow">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <div class="flex items-center">
                <img src="assets/images/logo.png" alt="Logo" class="w-10 h-10 mr-2">
                <span class="text-xl font-bold text-green-700">THE TUBER CART</span>
            </div>
            <div class="flex items-center space-x-6">
                <a href="index.php" class="text-gray-700 hover:text-green-600">Home</a>
                <a href="products.php" class="text-gray-700 hover:text-green-600">Products</a>
                <a href="about.php" class="text-gray-700 hover:text-green-600">About</a>
                <a href="contact.php" class="text-gray-700 hover:text-green-600">Contact</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="logout.php" class="text-gray-700 hover:text-green-600">Log Out</a>
                <?php else: ?>
                    <a href="login.php" class="text-gray-700 hover:text-green-600">Login</a>
                <?php endif; ?>
                <a href="cart.php" class="relative text-gray-700 hover:text-green-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m5-9v9m4-9v9m5-9l2 9" />
                    </svg>
                    <span id="cart-count" class="absolute -top-2 -right-2 bg-red-600 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full">0</span>
                </a>
            </div>
        </div>
    </nav>
    <script src="assets/js/main.js" defer></script>