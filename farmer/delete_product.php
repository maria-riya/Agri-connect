<?php
require_once __DIR__ . "/../includes/db.php";
require_once __DIR__ . "/../includes/auth.php";
requireSeller();

$id = $_GET['id'] ?? null;
$seller_id = $_SESSION['user_id'];

if ($id) {
    // Check if the product belongs to the logged-in seller before deleting
    $stmt = $pdo->prepare("SELECT id FROM products WHERE id = ? AND seller_id = ?");
    $stmt->execute([$id, $seller_id]);
    $product = $stmt->fetch();

    if ($product) {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
    }
}

header("Location: products.php");
exit;
?>