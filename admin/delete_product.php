<?php
require_once __DIR__ . "/../includes/db.php";
require_once __DIR__ . "/../includes/auth.php";
requireAdmin();

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: products.php");
    exit;
}

try {
    $pdo->beginTransaction();

    // 1. Delete all cart items related to this product
    $stmt = $pdo->prepare("DELETE FROM cart_items WHERE product_id = ?");
    $stmt->execute([$id]);

    // 2. Delete all order items related to this product
    $stmt = $pdo->prepare("DELETE FROM order_items WHERE product_id = ?");
    $stmt->execute([$id]);

    // 3. Delete all reviews related to this product
    $stmt = $pdo->prepare("DELETE FROM reviews WHERE product_id = ?");
    $stmt->execute([$id]);
    
    // 4. Finally, delete the product itself
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);

    $pdo->commit();
    
} catch (Exception $e) {
    $pdo->rollBack();
    die('Error deleting product: ' . $e->getMessage());
}

header("Location: products.php");
exit;