<?php require_once __DIR__.'/../includes/db.php'; require_once __DIR__.'/../includes/header.php'; if(!isset($_GET['id'])){ echo '<div class="alert alert-warning">Order not found</div>'; require_once __DIR__.'/../includes/footer.php'; exit; }
$id=(int)$_GET['id']; echo '<div class="alert alert-success">Order #'.htmlspecialchars($id).' placed successfully.</div>'; require_once __DIR__.'/../includes/footer.php'; ?>


--- FILE: public/wishlist.php ---
<?php require_once __DIR__.'/../includes/db.php'; if(session_status()===PHP_SESSION_NONE) session_start(); require_once __DIR__.'/../includes/header.php';
if(!isset($_SESSION['user'])){ echo '<div class="alert alert-warning">Login to see wishlist</div>'; require_once __DIR__.'/../includes/footer.php'; exit; }
$stmt = $pdo->prepare('SELECT w.*, p.title, p.price, p.image FROM wishlists w JOIN products p ON w.product_id=p.id WHERE w.user_id=?'); $stmt->execute([$_SESSION['user']['id']]); $items=$stmt->fetchAll();
?>
<h3>Your Wishlist</h3>
<?php if(!$items) echo '<p>No items</p>'; else{ foreach($items as $it){ ?>
<div class="card mb-2" style="width:18rem"><img src="/uploads/<?php echo htmlspecialchars($it['image']); ?>" class="card-img-top"><div class="card-body"><h5><?php echo htmlspecialchars($it['title']); ?></h5><p>₹<?php echo $it['price']; ?></p></div></div>
<?php }} require_once __DIR__.'/../includes/footer.php'; ?>


--- FILE: public/review_submit.php ---
<?php require_once __DIR__.'/../includes/db.php'; if(session_status()===PHP_SESSION_NONE) session_start();
if(!isset($_SESSION['user'])){ header('Location:/login.php'); exit; }
if($_SERVER['REQUEST_METHOD']==='POST'){
$product_id=(int)$_POST['product_id']; $rating=(int)$_POST['rating']; $comment=trim($_POST['comment']);
$pdo->prepare('INSERT INTO reviews (product_id,user_id,rating,comment) VALUES (?,?,?,?)')->execute([$product_id,$_SESSION['user']['id'],$rating,$comment]);
}
header('Location: /product.php?id='.$product_id); exit; ?>