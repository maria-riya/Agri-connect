<?php
require_once __DIR__ . "/../includes/db.php";
require_once __DIR__ . "/../includes/auth.php";
requireAdmin();
include __DIR__ . "/../includes/header.php";

$id = $_GET['id'] ?? null;
if (!$id) { header("Location: fertilizers.php"); exit; }

$stmt = $pdo->prepare("SELECT * FROM fertilizers WHERE id = ?");
$stmt->execute([$id]);
$fertilizer = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$fertilizer) { header("Location: fertilizers.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $slug = $_POST['slug'];
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'];
    $stock = $_POST['stock'] ?? 0;
    $image = $fertilizer['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . "/../assets/images/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $fileName = uniqid("fertilizer_", true) . "." . strtolower($ext);
        $targetPath = $uploadDir . $fileName;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $image = "assets/images/" . $fileName;
            @unlink(__DIR__ . "/../" . $fertilizer['image']);
        }
    }
    $stmt = $pdo->prepare("UPDATE fertilizers SET title = ?, slug = ?, description = ?, price = ?, stock = ?, image = ? WHERE id = ?");
    $stmt->execute([$title, $slug, $description, $price, $stock, $image, $id]);
    header("Location: fertilizers.php");
    exit;
}
?>
<main class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Edit Fertilizer/Tool</h2>
    <form method="post" enctype="multipart/form-data" class="space-y-4 max-w-md">
        <div>
            <label>Title:</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($fertilizer['title']); ?>" required class="w-full border px-2 py-1">
        </div>
        <div>
            <label>Slug:</label>
            <input type="text" name="slug" value="<?php echo htmlspecialchars($fertilizer['slug']); ?>" required class="w-full border px-2 py-1">
        </div>
        <div>
            <label>Description:</label>
            <textarea name="description" class="w-full border px-2 py-1"><?php echo htmlspecialchars($fertilizer['description']); ?></textarea>
        </div>
        <div>
            <label>Price:</label>
            <input type="number" step="0.01" name="price" value="<?php echo $fertilizer['price']; ?>" required class="w-full border px-2 py-1">
        </div>
        <div>
            <label>Stock:</label>
            <input type="number" name="stock" value="<?php echo $fertilizer['stock']; ?>" class="w-full border px-2 py-1">
        </div>
        <div>
            <label>Image:</label><br>
            <?php if ($fertilizer['image']): ?>
                <img src="../<?php echo htmlspecialchars($fertilizer['image']); ?>" alt="Current Image" class="w-16 h-16 object-cover mb-2">
            <?php endif; ?>
            <input type="file" name="image" accept="image/*">
        </div>
        <button type="submit" class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-600">Update Fertilizer</button>
    </form>
</main>
<?php include __DIR__ . "/../includes/footer.php"; ?>
