<?php
require_once __DIR__ . "/../includes/db.php";
require_once __DIR__ . "/../includes/auth.php";
requireAdmin();
include __DIR__ . "/../includes/header.php";

// Fetch fertilizers
$stmt = $pdo->query("SELECT id, title, slug, description, price, stock, image, created_at FROM fertilizers ORDER BY id DESC");
$fertilizers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Manage Fertilizers & Tools</h2>
    <p class="mb-6">Add, edit, or delete products sold to farmers.</p>
    <a href="add_fertilizer.php" class="text-white bg-green-700 px-4 py-2 rounded hover:bg-green-600">Add New Product</a>

    <table class="w-full mt-4 border-collapse border border-gray-300">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-4 py-2">ID</th>
                <th class="border px-4 py-2">Image</th>
                <th class="border px-4 py-2">Title</th>
                <th class="border px-4 py-2">Slug</th>
                <th class="border px-4 py-2">Description</th>
                <th class="border px-4 py-2">Price</th>
                <th class="border px-4 py-2">Stock</th>
                <th class="border px-4 py-2">Created At</th>
                <th class="border px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($fertilizers as $fertilizer): ?>
            <tr>
                <td class="border px-4 py-2"><?php echo $fertilizer['id']; ?></td>
                <td class="border px-4 py-2">
                    <?php
                        $imgPath = $fertilizer['image'];
                        $imgSrc = (strpos($imgPath, 'assets/images/') === 0) ? '../' . $imgPath : '../uploads/' . $imgPath;
                    ?>
                    <img src="<?= htmlspecialchars($imgSrc) ?>" alt="<?= htmlspecialchars($fertilizer['title']); ?>" class="w-16 h-16 object-cover rounded">
                </td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($fertilizer['title']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($fertilizer['slug']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($fertilizer['description']); ?></td>
                <td class="border px-4 py-2">₹<?php echo $fertilizer['price']; ?></td>
                <td class="border px-4 py-2"><?php echo $fertilizer['stock']; ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($fertilizer['created_at']); ?></td>
                <td class="border px-4 py-2">
                    <!-- Update these links to point to fertilizer edit/delete pages if needed -->
                    <a href="edit_fertilizer.php?id=<?php echo $fertilizer['id']; ?>" class="text-blue-600 hover:underline">Edit</a> |
                    <a href="delete_fertilizer.php?id=<?php echo $fertilizer['id']; ?>" class="text-red-600 hover:underline" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>
<?php include __DIR__ . "/../includes/footer.php"; ?>
