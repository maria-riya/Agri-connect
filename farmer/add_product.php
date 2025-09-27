<?php
require_once __DIR__ . "/../includes/db.php";
require_once __DIR__ . "/../includes/auth.php";
requireSeller();
include __DIR__ . "/../includes/header.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $seller_id = $_SESSION['user_id'];
    $category_id = $_POST['category_id'];
    $title = $_POST['title'];
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'];
    $stock = $_POST['stock'] ?? 0;
    
    // Set a default image value and error variable
    $image = null;
    $error = null;

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . "/../assets/images/";
        
        // Ensure the directory exists and is writable
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $fileName = uniqid("prod_", true) . "." . strtolower($ext);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $image = "assets/images/" . $fileName; // Path to store in DB
        } else {
            $error = "Image upload failed. Please check folder permissions.";
        }
    }

    if (!isset($error)) {
        $stmt = $pdo->prepare("INSERT INTO products (seller_id, category_id, title, description, price, stock, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$seller_id, $category_id, $title, $description, $price, $stock, $image])) {
            header("Location: products.php");
            exit;
        } else {
            $error = "Failed to add product.";
        }
    }
}

// Fetch categories
$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Add New Tuber</h2>
    <?php if (isset($error)) echo "<p class='text-red-600'>$error</p>"; ?>
    <form method="post" enctype="multipart/form-data" class="space-y-4 max-w-md">
        <div>
            <label>Title:</label>
            <input type="text" name="title" required class="w-full border px-2 py-1">
        </div>
        <div>
            <label>Price:</label>
            <input type="number" step="0.01" name="price" required class="w-full border px-2 py-1">
        </div>
        <div>
            <label>Stock:</label>
            <input type="number" name="stock" value="0" class="w-full border px-2 py-1">
        </div>
        <div>
            <label>Category:</label>
            <select name="category_id" required class="w-full border px-2 py-1">
                <option value="">Select Category</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label>Description:</label>
            <textarea name="description" class="w-full border px-2 py-1"></textarea>
        </div>
        <div>
            <label>Choose Image:</label>
            <input type="file" name="image" accept="image/*" class="w-full border px-2 py-1">
        </div>
        <button type="submit" class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-600">Add Product</button>
    </form>
</main>

<?php include __DIR__ . "/../includes/footer.php"; ?>