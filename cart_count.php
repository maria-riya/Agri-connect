<?php
require_once __DIR__.'/includes/db.php';
if(session_status()===PHP_SESSION_NONE) session_start();
$count = 0;
if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare('SELECT c.id as cart_id FROM carts c WHERE c.user_id=?');
    $stmt->execute([$user_id]);
    $cart = $stmt->fetch();
    if($cart){
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM cart_items WHERE cart_id = ?');
        $stmt->execute([$cart['cart_id']]);
        $count = (int)$stmt->fetchColumn();
    }
}
echo json_encode(['count'=>$count]);
