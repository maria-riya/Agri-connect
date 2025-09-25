<?php require_once __DIR__.'/includes/db.php'; if(session_status()===PHP_SESSION_NONE) session_start(); require_once __DIR__.'/includes/header.php';
if(!isset($_SESSION['user'])){ echo '<div class="alert alert-warning">Please <a href="/login.php">login</a> to see cart.</div>'; require_once __DIR__.'/includes/footer.php'; exit; }
$user_id = $_SESSION['user']['id'];
$stmt = $pdo->prepare('SELECT c.id as cart_id FROM carts c WHERE c.user_id=?'); $stmt->execute([$user_id]); $cart = $stmt->fetch();
$items = [];
if($cart){
$stmt = $pdo->prepare('SELECT ci.*, p.title, p.price, p.image FROM cart_items ci JOIN products p ON ci.product_id = p.id WHERE ci.cart_id = ?');
$stmt->execute([$cart['cart_id']]); $items = $stmt->fetchAll();
}
$total = 0; foreach($items as $it) $total += $it['price'] * $it['quantity'];
?>
<h3>Your Cart</h3>
<?php if(empty($items)) echo '<p>Cart is empty</p>'; ?>
<table class="table">
<thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr></thead>
<tbody>
<?php foreach($items as $it): ?>
<tr>
<td><?php echo htmlspecialchars($it['title']); ?></td>
<td>₹<?php echo $it['price']; ?></td>
<td><?php echo $it['quantity']; ?></td>
<td>₹<?php echo $it['price'] * $it['quantity']; ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<p><strong>Total: ₹<?php echo $total; ?></strong></p>
<a href="/checkout.php" class="btn btn-success">Checkout</a>
<?php  require_once __DIR__.'/includes/footer.php'; ?>