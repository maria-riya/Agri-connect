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
    <?php
    // Detect if included from guest folder
    $isGuest = (strpos($_SERVER['PHP_SELF'], '/guest/') !== false);
    $cssPrefix = $isGuest ? '../' : '';
    ?>
    <link href="<?= $cssPrefix ?>https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="<?= $cssPrefix ?>https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $cssPrefix ?>assets/css/style.css">
    <link rel="stylesheet" href="<?= $cssPrefix ?>assets/css/index.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen">
    <nav class="tuber-nav sticky top-0 z-10 py-4 bg-white shadow">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <div class="flex items-center">
                <img src="<?= $cssPrefix ?>assets/images/logo.png" alt="Logo" class="w-10 h-10 mr-2">
                <span class="text-xl font-bold text-green-700">THE TUBER CART</span>
            </div>
            <div class="flex items-center space-x-6">
                <?php
                $role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
                if (!isset($_SESSION['user_id'])) {
                    // Guest navbar
                ?>
                    <a href="<?= $cssPrefix ?>guest/index_guest.php" class="text-gray-700 hover:text-green-600">Home</a>
                    <a href="<?= $cssPrefix ?>guest/products_guest.php" class="text-gray-700 hover:text-green-600">Products</a>
                    <a href="<?= $cssPrefix ?>about.php" class="text-gray-700 hover:text-green-600">About</a>
                    <a href="<?= $cssPrefix ?>contact.php" class="text-gray-700 hover:text-green-600">Contact</a>
                    <a href="<?= $cssPrefix ?>login.php" class="text-gray-700 hover:text-green-600">Login</a>
                <?php
                } else if ($role === 'admin') {
                ?>
                    <a href="index_admin.php" class="text-gray-700 hover:text-green-600">Dashboard</a>
                    <a href="admin/products.php" class="text-gray-700 hover:text-green-600">Manage Products</a>
                    <a href="admin/users.php" class="text-gray-700 hover:text-green-600">Manage Users</a>
                    <a href="logout.php" class="text-gray-700 hover:text-green-600">Log Out</a>
                <?php
                } else if ($role === 'farmer') {
                ?>
                    <a href="index_farmer.php" class="text-gray-700 hover:text-green-600">Dashboard</a>
                    <a href="farmer/products.php" class="text-gray-700 hover:text-green-600">My Products</a>
                    <a href="farmer/orders.php" class="text-gray-700 hover:text-green-600">Orders</a>
                    <a href="logout.php" class="text-gray-700 hover:text-green-600">Log Out</a>
                <?php
                } else if ($role === 'wholesaler') {
                ?>
                    <a href="index_wholesaler.php" class="text-gray-700 hover:text-green-600">Dashboard</a>
                    <a href="products.php" class="text-gray-700 hover:text-green-600">Bulk Products</a>
                    <a href="cart.php" class="text-gray-700 hover:text-green-600">Cart</a>
                    <a href="logout.php" class="text-gray-700 hover:text-green-600">Log Out</a>
                <?php
                } else {
                    // Default: customer
                ?>
                    <a href="index.php" class="text-gray-700 hover:text-green-600">Home</a>
                    <a href="products.php" class="text-gray-700 hover:text-green-600">Products</a>
                    <a href="cart.php" class="text-gray-700 hover:text-green-600">Cart</a>
                    <a href="logout.php" class="text-gray-700 hover:text-green-600">Log Out</a>
                <?php } ?>
            </div>
        </div>
    </nav>
    <script src="assets/js/main.js" defer></script>
    <script>
    // Fix JS path for guest pages
    (function() {
        var isGuest = window.location.pathname.indexOf('/guest/') !== -1;
        if(isGuest) {
            var script = document.createElement('script');
            script.src = '../assets/js/main.js';
            script.defer = true;
            document.head.appendChild(script);
        }
    })();
    </script>
    <script>
    function updateCartCount() {
        fetch('cart_count.php')
            .then(res => res.json())
            .then(data => {
                var cartCount = document.getElementById('cart-count');
                if(cartCount) cartCount.textContent = data.count;
            });
    }
    document.addEventListener('DOMContentLoaded', updateCartCount);
    window.addEventListener('cart-updated', updateCartCount);
    </script>