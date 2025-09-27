<?php
require_once __DIR__ . "/../includes/db.php";  // correct relative path

// all fertilizers fetch
$stmt = $pdo->query("SELECT * FROM fertilizers ORDER BY created_at DESC");
$fertilizers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Fertilizers</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f6f8fa;
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            max-width: 1100px;
            margin: 0 auto;
        }
        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform 0.2s ease;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        .card-body {
            padding: 15px;
            flex-grow: 1;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #222;
        }
        .price {
            font-size: 16px;
            color: #28a745;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .stock {
            font-size: 14px;
            color: #555;
            margin-bottom: 12px;
        }
        .desc {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
        }
        .btn {
            display: inline-block;
            padding: 10px;
            text-align: center;
            background: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            transition: background 0.3s ease;
        }
        .btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<h2>Available Fertilizers</h2>
<div class="grid">
    <?php foreach($fertilizers as $f): ?>
        <div class="card">
            <?php if($f['image']): ?>
                <img src="../uploads/<?= htmlspecialchars($f['image']) ?>" alt="<?= htmlspecialchars($f['title']) ?>">
            <?php else: ?>
                <img src="https://via.placeholder.com/300x180?text=No+Image" alt="No Image">
            <?php endif; ?>
            <div class="card-body">
                <div class="title"><?= htmlspecialchars($f['title']) ?></div>
                <div class="price">₹<?= number_format($f['price'], 2) ?></div>
                <div class="stock">Stock: <?= $f['stock'] ?></div>
                <div class="desc">
                    <?= htmlspecialchars(substr($f['description'], 0, 60)) ?>...
                </div>
                <a href="#" class="btn">Add to Cart</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>
