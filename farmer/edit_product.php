<?php
require_once __DIR__ . "/../includes/db.php";
require_once __DIR__ . "/../includes/auth.php";
requireSeller();
include __DIR__ . "/../includes/header.php";

$id = $_GET['id'] ?? null;
$seller_id = $_SESSION['user_id'];

if (!$id) { header("Location: products.php"); exit; }

// Fetch product and ensure it belongs to the logged-in seller
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND seller_id = ?");
$stmt->execute([$id, $seller_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    // If product does not exist or does not belong to this seller, redirect
    header("Location: products.php");
    exit;
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = $_POST['category_id'];
    $title = $_POST['title'];
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'];
    $stock = $_POST['stock'] ?? 0;
    $image = $_POST['image'] ?? '';

    $stmt = $pdo->prepare("UPDATE products SET category_id = ?, title = ?, description = ?, price = ?, stock = ?, image = ? WHERE id = ? AND seller_id = ?");
    $stmt->execute([$category_id, $title, $description, $price, $stock, $image, $id, $seller_id]);
    header("Location: products.php");
    exit;
}

// Fetch categories for the dropdown menu
$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Edit Product</h2>
    <form method="post" class="space-y-4 max-w-md">
        <div>
            <label>Title:</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($product['title']); ?>" required class="w-full border px-2 py-1">
        </div>
        <div>
            <label>Price:</label>
            <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required class="w-full border px-2 py-1">
        </div>
        <div>
            <label>Stock:</label>
            <input type="number" name="stock" value="<?php echo $product['stock']; ?>" class="w-full border px-2 py-1">
        </div>
        <div>
            <label>Category:</label>
            <select name="category_id" required class="w-full border px-2 py-1">
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>" <?php if($cat['id']==$product['category_id']) echo "selected"; ?>>
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label>Description:</label>
            <textarea name="description" class="w-full border px-2 py-1"><?php echo htmlspecialchars($product['description']); ?></textarea>
        </div>
        <div>
            <label>Image URL:</label>
            <input type="text" name="image" value="<?php echo htmlspecialchars($product['image']); ?>" class="w-full border px-2 py-1">
        </div>
        <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-600">Update Product</button>
    </form>
</main>

<?php include __DIR__ . "/../includes/footer.php"; ?>