<?php require_once __DIR__.'/includes/db.php'; require_once __DIR__.'/includes/header.php';
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$products = [];
if($q !== ''){
$stmt = $pdo->prepare('SELECT * FROM products WHERE title LIKE ? OR description LIKE ? ORDER BY created_at DESC'); $like="%$q%"; $stmt->execute([$like,$like]); $products = $stmt->fetchAll();
}
?>
<h3>Search</h3>
<form method="GET" class="mb-3"><div class="input-group"><input name="q" value="<?php echo htmlspecialchars($q); ?>" class="form-control" placeholder="Search products..."><button class="btn btn-secondary">Search</button></div></form>
<?php if($q === '') echo '<p>Type keyword and press Search</p>'; else if(!$products) echo '<p>No results</p>'; else { echo '<div class="row">'; foreach($products as $p){ ?>
<div class="col-md-3"><div class="card mb-3"><img src="/uploads/<?php echo htmlspecialchars($p['image']); ?>" class="card-img-top" style="height:160px;object-fit:cover"><div class="card-body"><h5><?php echo htmlspecialchars($p['title']); ?></h5><p>₹<?php echo $p['price']; ?></p><a href="/product.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-primary">View</a></div></div></div>
<?php } echo '</div>'; } require_once __DIR__.'/includes/footer.php'; ?>