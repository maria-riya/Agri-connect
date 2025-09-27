<?php
require_once __DIR__.'/includes/db.php'; // Adjust path if checkout.php is in public/
if(session_status()===PHP_SESSION_NONE) session_start();

// Ensure user is logged in
if(!isset($_SESSION['user_id'])){
    header('Location:login.php');
    exit;
}
$user_id = $_SESSION['user_id'];

// Initialize items and total
$items = [];
$total = 0;

// ----- 1. Direct Buy Now flow -----
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])){
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)($_POST['quantity'] ?? 1);

    $stmt = $pdo->prepare('SELECT * FROM products WHERE id=?');
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if(!$product){
        die('Product not found.');
    }

    $items[] = [
        'product_id' => $product['id'],
        'price' => $product['price'],
        'quantity' => $quantity
    ];
    $total = $product['price'] * $quantity;
}

// ----- 2. Cart checkout flow -----
else {
    // get user's cart
    $stmt = $pdo->prepare('SELECT c.id as cart_id FROM carts c WHERE c.user_id=?');
    $stmt->execute([$user_id]);
    $cart = $stmt->fetch();

    if(!$cart){
        echo '<div class="alert alert-warning">Your cart is empty.</div>';
        require_once __DIR__.'/includes/footer.php';
        exit;
    }

    $stmt = $pdo->prepare('SELECT ci.*, p.price FROM cart_items ci JOIN products p ON ci.product_id=p.id WHERE ci.cart_id=?');
    $stmt->execute([$cart['cart_id']]);
    $items = $stmt->fetchAll();

    if(empty($items)){
        echo '<div class="alert alert-warning">Your cart is empty.</div>';
        require_once __DIR__.'/includes/footer.php';
        exit;
    }

    foreach($items as $it) $total += $it['price'] * $it['quantity'];
}

// ----- Place Order -----
if($_SERVER['REQUEST_METHOD']==='POST'){
    $pdo->beginTransaction();
    try {
        // Insert order
        $stmt = $pdo->prepare('INSERT INTO orders (user_id,total,status) VALUES (?,?,?)');
        $stmt->execute([$user_id,$total,'pending']);
        $order_id = $pdo->lastInsertId();

        // Insert order items
        $ins = $pdo->prepare('INSERT INTO order_items (order_id,product_id,price,quantity) VALUES (?,?,?,?)');
        foreach($items as $it) $ins->execute([$order_id,$it['product_id'],$it['price'],$it['quantity']]);

        // Mock payment (COD)
        $pdo->prepare('INSERT INTO payments (order_id,amount,method,status) VALUES (?,?,?,?)')
            ->execute([$order_id,$total,'cod','pending']);

        // Clear cart if it was a cart checkout
        if(isset($cart)){
            $pdo->prepare('DELETE FROM cart_items WHERE cart_id = ?')->execute([$cart['cart_id']]);
            $pdo->prepare('DELETE FROM carts WHERE id = ?')->execute([$cart['cart_id']]);
        }

        $pdo->commit();

        // Corrected Redirect: Use a relative path
        header('Location: order_success.php?id='.$order_id);
        exit;
    } catch(Exception $e){
        $pdo->rollBack();
        die('Error placing order: '.$e->getMessage());
    }
}

// ----- Show Checkout Page -----
require_once __DIR__.'/includes/header.php';
?>
<h3>Checkout</h3>
<p>Total: ₹<?php echo $total; ?></p>
<form method="POST">
    <?php
    // Include hidden fields if direct Buy Now
    if(!empty($items) && count($items)===1 && isset($items[0]['product_id'])): ?>
        <input type="hidden" name="product_id" value="<?php echo $items[0]['product_id']; ?>">
        <input type="hidden" name="quantity" value="<?php echo $items[0]['quantity']; ?>">
    <?php endif; ?>
    <button class="btn btn-primary">Place Order (Cash on Delivery)</button>
</form>
<?php require_once __DIR__.'/includes/footer.php'; ?>