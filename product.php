<?php require_once __DIR__.'/includes/db.php'; require_once __DIR__.'/includes/header.php';
if(!isset($_GET['id'])){ echo '<div class="alert alert-warning">Product not found</div>'; require_once __DIR__.'/includes/footer.php'; exit; }
$id = (int)$_GET['id'];
$stmt = $pdo->prepare('SELECT p.*, c.name as category_name, u.name as seller_name FROM products p LEFT JOIN categories c ON p.category_id=c.id LEFT JOIN users u ON p.seller_id=u.id WHERE p.id=?');
$stmt->execute([$id]); $p = $stmt->fetch(); if(!$p){ echo '<div class="alert alert-warning">Product not found</div>'; require_once __DIR__.'/includes/footer.php'; exit; }
?>
<div class="row">
<div class="col-md-5"><img src="/uploads/<?php echo htmlspecialchars($p['image']); ?>" class="img-fluid"></div>
<div class="col-md-7">
<h2><?php echo htmlspecialchars($p['title']); ?></h2>
<p><?php echo nl2br(htmlspecialchars($p['description'])); ?></p>
<p><strong>Price: </strong>₹<?php echo $p['price']; ?></p>
<p><strong>Seller: </strong><?php echo htmlspecialchars($p['seller_name']); ?></p>
<form method="POST" action="/cart_add.php">
<input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
<div class="mb-2"><input name="quantity" value="1" class="form-control" style="width:120px"></div>
<button class="btn btn-success">Add to Cart</button>
</form>
</div>
</div>
<hr>
<h4>Reviews</h4>
<?php
$revSt = $pdo->prepare('SELECT r.*, u.name FROM reviews r JOIN users u ON r.user_id=u.id WHERE r.product_id=? ORDER BY r.created_at DESC'); $revSt->execute([$p['id']]); $reviews = $revSt->fetchAll();
foreach($reviews as $r){
echo '<div class="mb-2"><strong>'.htmlspecialchars($r['name']).'</strong> — '.str_repeat('★',$r['rating']).'<br>'.htmlspecialchars($r['comment']).'</div>';
}
?>
<?php if(isset($_SESSION['user'])): ?>
<form method="POST" action="/review_submit.php">
<input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
<div class="mb-2"><select name="rating" class="form-control"><option>5</option><option>4</option><option>3</option><option>2</option><option>1</option></select></div>
<div class="mb-2"><textarea name="comment" class="form-control" placeholder="Write review"></textarea></div>
<button class="btn btn-primary">Submit Review</button>
</form>
<?php else: ?>
<p><a href="../login.php">Login</a> to write a review.</p>
<?php endif; ?>


<?php require_once __DIR__.'/includes/footer.php'; ?>