<?php require_once __DIR__.'/../includes/db.php'; if(session_status()===PHP_SESSION_NONE) session_start();
if(!isset($_SESSION['user'])){ header('Location:/login.php'); exit; }
$user_id = $_SESSION['user']['id'];
// compute cart total
$stmt = $pdo->prepare('SELECT c.id as cart_id FROM carts c WHERE c.user_id=?'); $stmt->execute([$user_id]); $cart = $stmt->fetch();
if(!$cart){ header('Location:/cart.php'); exit; }
$stmt = $pdo->prepare('SELECT ci.*, p.price FROM cart_items ci JOIN products p ON ci.product_id=p.id WHERE ci.cart_id=?'); $stmt->execute([$cart['cart_id']]); $items=$stmt->fetchAll();
$total = 0; foreach($items as $it) $total += $it['price'] * $it['quantity'];
if($_SERVER['REQUEST_METHOD']==='POST'){
// create order
$pdo->beginTransaction();
$stmt = $pdo->prepare('INSERT INTO orders (user_id,total,status) VALUES (?,?,?)'); $stmt->execute([$user_id,$total,'pending']); $order_id = $pdo->lastInsertId();
$ins = $pdo->prepare('INSERT INTO order_items (order_id,product_id,price,quantity) VALUES (?,?,?,?)');
foreach($items as $it) $ins->execute([$order_id,$it['product_id'],$it['price'],$it['quantity']]);
// create mock payment record
$pdo->prepare('INSERT INTO payments (order_id,amount,method,status) VALUES (?,?,?,?)')->execute([$order_id,$total,'cod','pending']);
// clear cart
$pdo->prepare('DELETE FROM cart_items WHERE cart_id = ?')->execute([$cart['cart_id']]);
$pdo->prepare('DELETE FROM carts WHERE id = ?')->execute([$cart['cart_id']]);
$pdo->commit();
header('Location: /order_success.php?id='.$order_id); exit;
}
require_once __DIR__.'/../includes/header.php'; ?>
<h3>Checkout</h3>
<p>Total: ₹<?php echo $total; ?></p>
<form method="POST">
<button class="btn btn-primary">Place Order (Cash on Delivery)</button>
</form>
<?php require_once __DIR__.'/../includes/footer.php'; ?>