<?php
require_once __DIR__ . "/../includes/db.php";
require_once __DIR__ . "/../includes/auth.php";
include __DIR__ . "/../includes/header.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$name, $email, $password, $role])) {
        header("Location: users.php");
        exit;
    } else {
        $error = "Failed to add user.";
    }
}
?>

<main class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Add New User</h2>
    <?php if (isset($error)) echo "<p class='text-red-600'>$error</p>"; ?>
    <form method="post" class="space-y-4 max-w-md">
        <div>
            <label class="block mb-1">Name:</label>
            <input type="text" name="name" required class="w-full border px-2 py-1">
        </div>
        <div>
            <label class="block mb-1">Email:</label>
            <input type="email" name="email" required class="w-full border px-2 py-1">
        </div>
        <div>
            <label class="block mb-1">Password:</label>
            <input type="password" name="password" required class="w-full border px-2 py-1">
        </div>
        <div>
            <label class="block mb-1">Role:</label>
            <select name="role" required class="w-full border px-2 py-1">
                <option value="">Select Role</option>
                <option value="admin">Admin</option>
                <option value="seller">Seller</option>
                <option value="customer">Customer</option>
            </select>
        </div>
        <button type="submit" class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-600">Add User</button>
    </form>
</main>

<?php include __DIR__ . "/../includes/footer.php"; ?>

