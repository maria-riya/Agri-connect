<?php
require_once __DIR__ . "/../includes/db.php";
require_once __DIR__ . "/../includes/auth.php";
include __DIR__ . "/../includes/header.php";
?>

<main class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Manage Categories</h2>
    <p class="mb-6">Add, edit, or delete product categories.</p>
    <a href="add_category.php" class="text-white bg-green-700 px-4 py-2 rounded hover:bg-green-600">Add New Category</a>

    <!-- Categories Table -->
    <table class="w-full mt-4 border-collapse border border-gray-300">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-4 py-2">ID</th>
                <th class="border px-4 py-2">Name</th>
                <th class="border px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $pdo->query("SELECT * FROM categories");
            while ($category = $stmt->fetch(PDO::FETCH_ASSOC)):
            ?>
            <tr>
                <td class="border px-4 py-2"><?php echo $category['id']; ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($category['name']); ?></td>
                <td class="border px-4 py-2">
                    <a href="edit_category.php?id=<?php echo $category['id']; ?>" class="text-blue-600 hover:underline">Edit</a> |
                    <a href="delete_category.php?id=<?php echo $category['id']; ?>" class="text-red-600 hover:underline" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</main>

<?php include __DIR__ . "/../includes/footer.php"; ?>
