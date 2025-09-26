<?php
require_once __DIR__ . "/../includes/db.php";
require_once __DIR__ . "/../includes/auth.php";
requireSeller();
include __DIR__ . "/../includes/header.php";

// Fetch products for the logged-in seller
$seller_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.seller_id = ? ORDER BY p.id DESC");
$stmt->execute([$seller_id]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Seller - Manage Products</h2>
    <p class="mb-6">You can add, update, and delete your own products.</p>
    <a href="add_product.php" class="text-white bg-green-700 px-4 py-2 rounded hover:bg-green-600">Add New Product</a>

    <table class="w-full mt-4 border-collapse border border-gray-300">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-4 py-2">ID</th>
                <th class="border px-4 py-2">Title</th>
                <th class="border px-4 py-2">Price</th>
                <th class="border px-4 py-2">Stock</th>
                <th class="border px-4 py-2">Category</th>
                <th class="border px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($products)): ?>
                <tr>
                    <td colspan="6" class="border px-4 py-2 text-center text-gray-500">No products found. Add your first product!</td>
                </tr>
            <?php else: ?>
                <?php foreach($products as $product): ?>
                <tr>
                    <td class="border px-4 py-2"><?php echo $product['id']; ?></td>
                    <td class="border px-4 py-2"><?php echo htmlspecialchars($product['title']); ?></td>
                    <td class="border px-4 py-2">₹<?php echo $product['price']; ?></td>
                    <td class="border px-4 py-2"><?php echo $product['stock']; ?></td>
                    <td class="border px-4 py-2"><?php echo htmlspecialchars($product['category_name']); ?></td>
                    <td class="border px-4 py-2">
                        <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="text-blue-600 hover:underline">Edit</a> |
                        <a href="delete_product.php?id=<?php echo $product['id']; ?>" class="text-red-600 hover:underline" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<?php include __DIR__ . "/../includes/footer.php"; ?>