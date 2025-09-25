<?php
require_once __DIR__ . "/../includes/db.php";
require_once __DIR__ . "/../includes/auth.php";
requireSeller();
include __DIR__ . "/../includes/header.php";
?>
<h2>Seller Dashboard</h2>
<p>Welcome Seller! You can manage your products and orders here.</p>
<ul>
    <li><a href="products.php">Manage My Products</a></li>
    <li><a href="orders.php">View Orders</a></li>
</ul>
<?php include __DIR__ . "/../includes/footer.php"; ?>
