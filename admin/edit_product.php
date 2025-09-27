<?php
require_once __DIR__ . "/../includes/db.php";
require_once __DIR__ . "/../includes/auth.php";
requireAdmin();
include __DIR__ . "/../includes/header.php";

$id = $_GET['id'] ?? null;
if (!$id) { header("Location: products.php"); exit; }

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$product) { header("Location: products.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = $_POST['category_id'];
    $title = $_POST['title'];
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'];
    $stock = $_POST['stock'] ?? 0;
    
    $image = $product['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . "/../assets/images/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $fileName = uniqid("prod_", true) . "." . strtolower($ext);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $image = "assets/images/" . $fileName;
            @unlink(__DIR__ . "/../" . $product['image']);
        }
    }

    $stmt = $pdo->prepare("UPDATE products SET category_id = ?, title = ?, description = ?, price = ?, stock = ?, image = ? WHERE id = ?");
    $stmt->execute([$category_id, $title, $description, $price, $stock, $image, $id]);
    header("Location: products.php");
    exit;
}

$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Edit Product (Fertilizer/Tool)</h2>
    <form method="post" enctype="multipart/form-data" class="space-y-4 max-w-md">
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
            <label>Current Image:</label>
            <div class="mb-2">
                <?php
                    $imgPath = $product['image'];
                    $imgSrc = (strpos($imgPath, 'assets/images/') === 0) ? '../' . $imgPath : '../uploads/' . $imgPath;
                ?>
                <img src="<?= htmlspecialchars($imgSrc) ?>" class="w-24 h-24 object-cover rounded-md">
            </div>
            <label>Change Image:</label>
            <input type="file" name="image" accept="image/*" class="w-full border px-2 py-1">
        </div>
        <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-600">Update Product</button>
    </form>
</main>
<?php include __DIR__ . "/../includes/footer.php"; ?>