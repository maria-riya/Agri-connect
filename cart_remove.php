<?php
require_once __DIR__.'/includes/db.php';
if(session_status()===PHP_SESSION_NONE) session_start();
// Support guest carts using session_id
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$session_id = session_id();
if(!$user_id && !$session_id){
    die('Unable to identify user or session.');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_item_id'])) {
    $cart_item_id = (int)$_POST['cart_item_id'];
    // Remove the item from cart_items, but ensure it belongs to the current user's cart
    if($user_id){
        $stmt = $pdo->prepare('SELECT c.id FROM carts c WHERE c.user_id=?');
        $stmt->execute([$user_id]);
        $cart = $stmt->fetch();
    } else {
        $stmt = $pdo->prepare('SELECT c.id FROM carts c WHERE c.session_id=?');
        $stmt->execute([$session_id]);
        $cart = $stmt->fetch();
    }
    if($cart){
        $cart_id = $cart['id'];
        // Only delete if the cart_item belongs to this cart
        $stmt = $pdo->prepare('DELETE FROM cart_items WHERE id = ? AND cart_id = ?');
        $stmt->execute([$cart_item_id, $cart_id]);
    }
    header('Location: /project/cart.php');
    exit;
}
// If accessed directly, redirect to cart
header('Location: /project/cart.php');
exit;
