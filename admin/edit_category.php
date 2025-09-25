<?php
require_once __DIR__ . "/../includes/db.php";
require_once __DIR__ . "/../includes/auth.php";
include __DIR__ . "/../includes/header.php";

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: categories.php");
    exit;
}

// Fetch category
$stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->execute([$id]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$category) {
    header("Location: categories.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $stmt = $pdo->prepare("UPDATE categories SET name = ? WHERE id = ?");
    if ($stmt->execute([$name, $id])) {
        header("Location: categories.php");
        exit;
    } else {
        $error = "Failed to update category.";
    }
}
?>

<main class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Edit Category</h2>
    <?php if (isset($error)) echo "<p class='text-red-600'>$error</p>"; ?>
    <form method="post" class="space-y-4 max-w-md">
        <div>
            <label class="block mb-1">Category Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($category['name']); ?>" required class="w-full border px-2 py-1">
        </div>
        <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-600">Update Category</button>
    </form>
</main>

<?php include __DIR__ . "/../includes/footer.php"; ?>

