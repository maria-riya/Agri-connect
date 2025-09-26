<?php
require_once __DIR__.'/includes/db.php';
if(session_status()===PHP_SESSION_NONE) session_start();

// Support guest carts using session_id
if(session_status() === PHP_SESSION_NONE) session_start();
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$session_id = session_id();
if(!$user_id && !$session_id){
    die('Unable to identify user or session.');
}
if (!isset($_POST['product_id']) || !isset($_POST['quantity'])) {
    die("Invalid request. Product or quantity not provided.");
}

$product_id = (int)$_POST['product_id'];
$qty = max(1,(int)$_POST['quantity']);

// get or create cart
//$stmt = $pdo->prepare('SELECT * FROM carts WHERE user_id=?');
//$stmt->execute([$user_id]);
//$cart = $stmt->fetch();


$stmt = $pdo->prepare("SELECT id FROM products WHERE id = ?");
$stmt->execute([$product_id]);
if ($stmt->rowCount() === 0) {
    die("Invalid product. Cannot add to cart.");
}

// get or create cart
if($user_id){
    $stmt = $pdo->prepare('SELECT * FROM carts WHERE user_id=?');
    $stmt->execute([$user_id]);
    $cart = $stmt->fetch();
    if(!$cart){
        $pdo->prepare('INSERT INTO carts (user_id, session_id, created_at) VALUES (?, ?, NOW())')->execute([$user_id, $session_id]);
        $cart_id = $pdo->lastInsertId();
    } else {
        $cart_id = $cart['id'];
    }
} else {
    $stmt = $pdo->prepare('SELECT * FROM carts WHERE session_id=?');
    $stmt->execute([$session_id]);
    $cart = $stmt->fetch();
    if(!$cart){
        $pdo->prepare('INSERT INTO carts (session_id, created_at) VALUES (?, NOW())')->execute([$session_id]);
        $cart_id = $pdo->lastInsertId();
    } else {
        $cart_id = $cart['id'];
    }
}

// upsert cart item
$stmt = $pdo->prepare('SELECT * FROM cart_items WHERE cart_id=? AND product_id=?');
$stmt->execute([$cart_id,$product_id]);
$ci = $stmt->fetch();
if($ci){
    $pdo->prepare('UPDATE cart_items SET quantity = quantity + ? WHERE id = ?')->execute([$qty,$ci['id']]);
    // Redirect to cart page after adding
    header('Location: /project/cart.php');
    exit;
} else {
    $pdo->prepare('INSERT INTO cart_items (cart_id,product_id,quantity) VALUES (?, ?, ?)')->execute([$cart_id,$product_id,$qty]);
    // Redirect to cart page after adding
    header('Location: /project/cart.php');
    exit;
}

header('Location: /cart.php');
exit;