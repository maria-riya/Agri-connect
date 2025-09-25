<?php
require_once __DIR__ . "/../includes/db.php";
require_once __DIR__ . "/../includes/auth.php";
include __DIR__ . "/../includes/header.php";

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: users.php");
    exit;
}

// Fetch user
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    header("Location: users.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // If password is not empty, update it
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, password = ?, role = ? WHERE id = ?");
        $stmt->execute([$name, $email, $password, $role, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
        $stmt->execute([$name, $email, $role, $id]);
    }

    header("Location: users.php");
    exit;
}
?>

<main class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Edit User</h2>
    <form method="post" class="space-y-4 max-w-md">
        <div>
            <label class="block mb-1">Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required class="w-full border px-2 py-1">
        </div>
        <div>
            <label class="block mb-1">Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required class="w-full border px-2 py-1">
        </div>
        <div>
            <label class="block mb-1">Password: <small>(Leave blank to keep current)</small></label>
            <input type="password" name="password" class="w-full border px-2 py-1">
        </div>
        <div>
            <label class="block mb-1">Role:</label>
            <select name="role" required class="w-full border px-2 py-1">
                <option value="admin" <?php if($user['role']=='admin') echo "selected"; ?>>Admin</option>
                <option value="seller" <?php if($user['role']=='seller') echo "selected"; ?>>Seller</option>
                <option value="customer" <?php if($user['role']=='customer') echo "selected"; ?>>Customer</option>
            </select>
        </div>
        <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-600">Update User</button>
    </form>
</main>

<?php include __DIR__ . "/../includes/footer.php"; ?>
