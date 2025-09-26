<?php
require_once __DIR__.'/includes/db.php';
if(session_status()===PHP_SESSION_NONE) session_start();

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : (isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : null);
if(!$user_id){
    header('Location: /login.php');
    exit;
}
$product_id = (int)$_POST['product_id'];
$qty = max(1,(int)$_POST['quantity']);

// get or create cart
$stmt = $pdo->prepare('SELECT * FROM carts WHERE user_id=?');
$stmt->execute([$user_id]);
$cart = $stmt->fetch();

if(!$cart){
    $pdo->prepare('INSERT INTO carts (user_id) VALUES (?)')->execute([$user_id]);
    $cart_id = $pdo->lastInsertId();
} else {
    $cart_id = $cart['id'];
}

// upsert cart item
$stmt = $pdo->prepare('SELECT * FROM cart_items WHERE cart_id=? AND product_id=?');
$stmt->execute([$cart_id,$product_id]);
$ci = $stmt->fetch();

if($ci){
    $pdo->prepare('UPDATE cart_items SET quantity = quantity + ? WHERE id = ?')->execute([$qty,$ci['id']]);
}
else {
    $pdo->prepare('INSERT INTO cart_items (cart_id,product_id,quantity) VALUES (?, ?, ?)')->execute([$cart_id,$product_id,$qty]);
}

header('Location: /cart.php');
exit;