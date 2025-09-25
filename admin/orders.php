<?php
require_once __DIR__ . "/../includes/db.php";
require_once __DIR__ . "/../includes/auth.php";
include __DIR__ . "/../includes/header.php";
?>

<main class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">View Orders</h2>
    <p class="mb-6">See all customer orders.</p>

    <table class="w-full mt-4 border-collapse border border-gray-300">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-4 py-2">Order ID</th>
                <th class="border px-4 py-2">Customer</th>
                <th class="border px-4 py-2">Total</th>
                <th class="border px-4 py-2">Status</th>
                <th class="border px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $pdo->query("SELECT * FROM orders");
            while ($order = $stmt->fetch(PDO::FETCH_ASSOC)):
            ?>
            <tr>
                <td class="border px-4 py-2"><?php echo $order['id']; ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($order['customer_name']); ?></td>
                <td class="border px-4 py-2"><?php echo $order['total']; ?></td>
                <td class="border px-4 py-2"><?php echo $order['status']; ?></td>
                <td class="border px-4 py-2">
                    <a href="view_order.php?id=<?php echo $order['id']; ?>" class="text-blue-600 hover:underline">View</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</main>

<?php include __DIR__ . "/../includes/footer.php"; ?>
