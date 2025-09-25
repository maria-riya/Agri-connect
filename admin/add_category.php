<?php
require_once __DIR__ . "/../includes/db.php";
require_once __DIR__ . "/../includes/auth.php";

include __DIR__ . "/../includes/header.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];

    $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
    if ($stmt->execute([$name])) {
        header("Location: categories.php");
        exit;
    } else {
        $error = "Failed to add category.";
    }
}
?>

<main class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Add New Category</h2>
    <?php if (isset($error)) echo "<p class='text-red-600'>$error</p>"; ?>
    <form method="post" class="space-y-4 max-w-md">
        <div>
            <label class="block mb-1">Category Name:</label>
            <input type="text" name="name" required class="w-full border px-2 py-1">
        </div>
        <button type="submit" class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-600">Add Category</button>
    </form>
</main>

<?php include __DIR__ . "/../includes/footer.php"; ?>
