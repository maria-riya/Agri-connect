<?php
require_once __DIR__ . "/../includes/db.php";
require_once __DIR__ . "/../includes/auth.php";

requireAdmin();

$stmt = $pdo->query("SELECT * FROM fertilizers ORDER BY created_at DESC");
$fertilizers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Fertilizers List</title>
</head>
<body>
    <h2>Fertilizers List</h2>
    <a href="add_fertilizer.php">+ Add Fertilizer</a>
    <br><br>
    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Image</th>
            <th>Created At</th>
        </tr>
        <?php foreach($fertilizers as $f): ?>
        <tr>
            <td><?= $f['id'] ?></td>
            <td><?= htmlspecialchars($f['title']) ?></td>
            <td><?= $f['price'] ?></td>
            <td><?= $f['stock'] ?></td>
            <td>
                <?php if($f['image']): ?>
                    <img src="../uploads/<?= $f['image'] ?>" width="80">
                <?php endif; ?>
            </td>
            <td><?= $f['created_at'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
