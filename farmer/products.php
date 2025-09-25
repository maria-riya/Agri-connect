<?php
require_once __DIR__ . "/../includes/db.php";
require_once __DIR__ . "/../includes/auth.php";
requireSeller();
include __DIR__ . "/../includes/header.php";
?>
<h2>Seller - Manage Products</h2>
<p>Seller can add, update, and delete their own products.</p>
<?php include __DIR__ . "/../includes/footer.php"; ?>
