<?php
require_once __DIR__ . "/../includes/db.php";
require_once __DIR__ . "/../includes/auth.php";

requireAdmin();

$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];

    // slug generate
    $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $title));

    // image upload
    $imageName = null;
    if (!empty($_FILES['image']['name'])) {
        $targetDir = __DIR__ . "/../uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $imageName = time() . "_" . basename($_FILES['image']['name']);
        $targetFile = $targetDir . $imageName;

        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);
        } else {
            $msg = "❌ Invalid image file.";
        }
    }

    if (empty($msg)) {
        $stmt = $pdo->prepare("INSERT INTO fertilizers (title, slug, description, price, stock, image) VALUES (?,?,?,?,?,?)");
        $stmt->execute([$title, $slug, $description, $price, $stock, $imageName]);
        $msg = "✅ Fertilizer added successfully!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Fertilizer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f6f8fa;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 500px;
            margin: 40px auto;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        h2 {
            margin-bottom: 20px;
            text-align: center;
            color: #333;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
            color: #444;
        }
        input[type="text"],
        input[type="number"],
        input[type="file"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }
        textarea {
            resize: vertical;
            min-height: 80px;
        }
        button {
            margin-top: 20px;
            width: 100%;
            padding: 12px;
            background: #28a745;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        button:hover {
            background: #218838;
        }
        .msg {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
            text-align: center;
        }
        .msg.success {
            background: #d4edda;
            color: #155724;
        }
        .msg.error {
            background: #f8d7da;
            color: #721c24;
        }
        .back-link {
            display: block;
            margin-top: 15px;
            text-align: center;
            text-decoration: none;
            color: #007bff;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Add Fertilizer</h2>
    <?php if($msg): ?>
        <div class="msg <?= strpos($msg, '✅') !== false ? 'success' : 'error' ?>">
            <?= htmlspecialchars($msg) ?>
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <label>Title:</label>
        <input type="text" name="title" required>

        <label>Description:</label>
        <textarea name="description"></textarea>

        <label>Price:</label>
        <input type="number" step="0.01" name="price" required>

        <label>Stock:</label>
        <input type="number" name="stock" required>

        <label>Image:</label>
        <input type="file" name="image">

        <button type="submit">Add Fertilizer</button>
    </form>

    <a class="back-link" href="fertilizers_list.php">⬅ Back to Fertilizers List</a>
</div>
</body>
</html>
