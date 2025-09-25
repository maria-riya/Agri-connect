<?php
require_once __DIR__ . "/includes/db.php"; // this creates $pdo
include __DIR__ . "/includes/header.php";

// Sample products query with PDO
$sql = "SELECT * FROM products LIMIT 6";
$stmt = $pdo->query($sql);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fresh Tubers - The Tuber Cart</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #8B4513;
            --primary-light: #D2B48C;
            --secondary-color: #228B22;
            --secondary-light: #90EE90;
            --accent-color: #FF6B35;
            --text-primary: #2C3E50;
            --text-secondary: #7F8C8D;
            --text-light: #BDC3C7;
            --background-primary: #FFFFFF;
            --background-secondary: #F8F9FA;
            --background-accent: #FFF8DC;
            --border-light: #E5E7EB;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            --gradient-primary: linear-gradient(135deg, #8B4513 0%, #A0522D 50%, #CD853F 100%);
            --gradient-secondary: linear-gradient(135deg, #228B22 0%, #32CD32 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--background-secondary);
            color: var(--text-primary);
            line-height: 1.6;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, rgba(139, 69, 19, 0.9) 0%, rgba(160, 82, 45, 0.8) 100%), 
                        url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="%23D2B48C" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            color: white;
            padding: 80px 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 30% 40%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            letter-spacing: -1px;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            font-weight: 400;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .hero-stats {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-top: 2rem;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            display: block;
            color: var(--secondary-light);
        }

        .stat-label {
            font-size: 0.875rem;
            opacity: 0.8;
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Products Section */
        .products-section {
            padding: 80px 0;
            background: var(--background-primary);
        }

        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1rem;
            position: relative;
            display: inline-block;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--gradient-secondary);
            border-radius: 2px;
        }

        .section-description {
            font-size: 1.125rem;
            color: var(--text-secondary);
            max-width: 600px;
            margin: 0 auto;
        }

        /* Products Grid */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }

        .product-card {
            background: var(--background-primary);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow-md);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            border: 1px solid var(--border-light);
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
        }

        .product-image {
            position: relative;
            overflow: hidden;
            height: 240px;
            background: var(--background-accent);
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-card:hover .product-image img {
            transform: scale(1.05);
        }

        .product-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: var(--gradient-secondary);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .product-favorite {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .product-favorite:hover {
            background: var(--accent-color);
            color: white;
            transform: scale(1.1);
        }

        .product-content {
            padding: 24px;
        }

        .product-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
            line-height: 1.4;
        }

        .product-description {
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin-bottom: 16px;
            line-height: 1.5;
        }

        .product-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .product-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .product-unit {
            font-size: 0.875rem;
            color: var(--text-secondary);
            font-weight: 400;
        }

        .product-rating {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .rating-stars {
            display: flex;
            gap: 2px;
        }

        .star {
            color: #FFC107;
            font-size: 0.875rem;
        }

        .rating-count {
            font-size: 0.75rem;
            color: var(--text-light);
            margin-left: 4px;
        }

        .product-actions {
            display: flex;
            gap: 12px;
        }

        .btn-view {
            flex: 1;
            padding: 12px 20px;
            background: var(--gradient-primary);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-view:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            background: linear-gradient(135deg, #A0522D 0%, #8B4513 100%);
        }

        .btn-add-cart {
            width: 50px;
            height: 44px;
            background: var(--secondary-color);
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-add-cart:hover {
            background: var(--secondary-light);
            transform: scale(1.05);
        }

        /* No Products */
        .no-products {
            text-align: center;
            padding: 80px 20px;
            color: var(--text-secondary);
        }

        .no-products i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .no-products h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        /* Loading Animation */
        .loading {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 200px;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid var(--border-light);
            border-top-color: var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Filter Section */
        .filter-section {
            background: var(--background-primary);
            padding: 20px 0;
            border-bottom: 1px solid var(--border-light);
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(10px);
        }

        .filter-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .filter-select {
            padding: 8px 12px;
            border: 2px solid var(--border-light);
            border-radius: 8px;
            background: var(--background-primary);
            font-size: 0.875rem;
            min-width: 120px;
        }

        .view-toggle {
            display: flex;
            background: var(--background-secondary);
            border-radius: 8px;
            padding: 4px;
        }

        .view-btn {
            padding: 8px 12px;
            background: none;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .view-btn.active {
            background: var(--primary-color);
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-stats {
                flex-direction: column;
                gap: 20px;
            }

            .products-grid {
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 20px;
            }

            .section-title {
                font-size: 2rem;
            }

            .filter-controls {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-group {
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .hero-section {
                padding: 60px 0;
            }

            .hero-title {
                font-size: 2rem;
            }

            .products-grid {
                grid-template-columns: 1fr;
            }

            .product-content {
                padding: 20px;
            }
        }

        /* Animation for loading products */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .product-card {
            animation: fadeInUp 0.6s ease forwards;
        }

        .product-card:nth-child(1) { animation-delay: 0.1s; }
        .product-card:nth-child(2) { animation-delay: 0.2s; }
        .product-card:nth-child(3) { animation-delay: 0.3s; }
        .product-card:nth-child(4) { animation-delay: 0.4s; }
        .product-card:nth-child(5) { animation-delay: 0.5s; }
        .product-card:nth-child(6) { animation-delay: 0.6s; }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title">Fresh Premium Tubers</h1>
            <p class="hero-subtitle">Farm-fresh tubers delivered straight to your doorstep. Quality guaranteed, nutrition preserved.</p>
            
            <div class="hero-stats">
                <div class="stat-item">
                    <span class="stat-number">500+</span>
                    <span class="stat-label">Happy Customers</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">50+</span>
                    <span class="stat-label">Tuber Varieties</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">24h</span>
                    <span class="stat-label">Fresh Delivery</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="filter-section">
        <div class="container">
            <div class="filter-controls">
                <div class="filter-group">
                    <select class="filter-select" id="categoryFilter">
                        <option value="">All Categories</option>
                        <option value="potato">Potatoes</option>
                        <option value="sweet-potato">Sweet Potatoes</option>
                        <option value="yam">Yams</option>
                        <option value="cassava">Cassava</option>
                    </select>
                    
                    <select class="filter-select" id="sortFilter">
                        <option value="name">Sort by Name</option>
                        <option value="price-low">Price: Low to High</option>
                        <option value="price-high">Price: High to Low</option>
                        <option value="rating">Rating</option>
                    </select>
                </div>

                <div class="view-toggle">
                    <button class="view-btn active" data-view="grid">
                        <i class="fas fa-th"></i>
                    </button>
                    <button class="view-btn" data-view="list">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="products-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Available Products</h2>
                <p class="section-description">Discover our premium selection of fresh tubers, carefully sourced from trusted local farms and delivered with care.</p>
            </div>

            <div class="products-grid" id="productsGrid">
                <?php if ($rows): ?>
                    <?php foreach ($rows as $index => $row): ?>
                        <div class="product-card" style="animation-delay: <?= ($index * 0.1) ?>s;">
                            <div class="product-image">
                                <img src="uploads/products/<?= htmlspecialchars($row['image']) ?>" 
                                     alt="<?= htmlspecialchars($row['title']) ?>"
                                     onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 300 200%22><rect width=%22300%22 height=%22200%22 fill=%22%23f0f0f0%22/><text x=%22150%22 y=%22100%22 text-anchor=%22middle%22 dy=%22.3em%22 font-family=%22Arial%22 font-size=%2216%22 fill=%22%23999%22>No Image</text></svg>'">
                                <div class="product-badge">Fresh</div>
                                <div class="product-favorite">
                                    <i class="far fa-heart"></i>
                                </div>
                            </div>
                            
                            <div class="product-content">
                                <h3 class="product-title"><?= htmlspecialchars($row['title']) ?></h3>
                                <p class="product-description">Fresh, organic <?= strtolower(htmlspecialchars($row['title'])) ?> sourced directly from local farms. Perfect for cooking and rich in nutrients.</p>
                                
                                <div class="product-meta">
                                    <div class="product-pricing">
                                        <span class="product-price">₹<?= number_format($row['price'], 2) ?></span>
                                        <span class="product-unit">/kg</span>
                                    </div>
                                    
                                    <div class="product-rating">
                                        <div class="rating-stars">
                                            <i class="fas fa-star star"></i>
                                            <i class="fas fa-star star"></i>
                                            <i class="fas fa-star star"></i>
                                            <i class="fas fa-star star"></i>
                                            <i class="fas fa-star star"></i>
                                        </div>
                                        <span class="rating-count">(<?= rand(15, 89) ?>)</span>
                                    </div>
                                </div>
                                
                                <div class="product-actions">
                                    <a href="product.php?id=<?= $row['id'] ?>" class="btn-view">
                                        <i class="fas fa-eye"></i>
                                        View Details
                                    </a>
                                    <button class="btn-add-cart" onclick="addToCart(<?= $row['id'] ?>)">
                                        <i class="fas fa-shopping-cart"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-products">
                        <i class="fas fa-seedling"></i>
                        <h3>No Products Found</h3>
                        <p>We're working hard to stock fresh tubers. Please check back soon!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <script>
        // Add to cart functionality
        function addToCart(productId) {
            // Add animation feedback
            event.target.innerHTML = '<i class="fas fa-check"></i>';
            event.target.style.background = '#28a745';
            
            // Here you would typically make an AJAX call to add to cart
            console.log('Adding product', productId, 'to cart');
            
            // Reset button after 2 seconds
            setTimeout(() => {
                event.target.innerHTML = '<i class="fas fa-shopping-cart"></i>';
                event.target.style.background = '';
            }, 2000);
        }

        // Favorite toggle functionality
        document.querySelectorAll('.product-favorite').forEach(btn => {
            btn.addEventListener('click', function() {
                const icon = this.querySelector('i');
                if (icon.classList.contains('far')) {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    this.style.background = '#ff6b6b';
                    this.style.color = 'white';
                } else {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    this.style.background = '';
                    this.style.color = '';
                }
            });
        });

        // Filter functionality
        document.getElementById('categoryFilter').addEventListener('change', function() {
            // Implement category filtering
            console.log('Filter by category:', this.value);
        });

        document.getElementById('sortFilter').addEventListener('change', function() {
            // Implement sorting
            console.log('Sort by:', this.value);
        });

        // View toggle functionality
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                const view = this.dataset.view;
                const grid = document.getElementById('productsGrid');
                
                if (view === 'list') {
                    grid.style.gridTemplateColumns = '1fr';
                    grid.querySelectorAll('.product-card').forEach(card => {
                        card.style.display = 'flex';
                        card.style.height = '200px';
                    });
                } else {
                    grid.style.gridTemplateColumns = '';
                    grid.querySelectorAll('.product-card').forEach(card => {
                        card.style.display = '';
                        card.style.height = '';
                    });
                }
            });
        });

        // Smooth scroll for better UX
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Loading state simulation (for future AJAX calls)
        function showLoading() {
            document.getElementById('productsGrid').innerHTML = `
                <div class="loading">
                    <div class="spinner"></div>
                </div>
            `;
        }

        // Add intersection observer for scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe all product cards for scroll animation
        document.querySelectorAll('.product-card').forEach(card => {
            observer.observe(card);
        });
    </script>
</body>
</html>

<?php include __DIR__ . "/includes/footer.php"; ?>