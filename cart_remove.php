<?php
require_once __DIR__.'/includes/db.php';
if(session_status()===PHP_SESSION_NONE) session_start();
if(!isset($_SESSION['user'])){
    header('Location: /login.php');
    exit;
}
$user_id = $_SESSION['user']['id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_item_id'])) {
    $cart_item_id = (int)$_POST['cart_item_id'];
    // Remove the item from cart_items
    $stmt = $pdo->prepare('DELETE FROM cart_items WHERE id = ?');
    $stmt->execute([$cart_item_id]);
    // Optionally, check if cart is empty and handle
    header('Location: /cart.php');
    exit;
}
// If accessed directly, redirect to cart
header('Location: /cart.php');
exit;
