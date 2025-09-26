
// main.js - project scripts
document.addEventListener("DOMContentLoaded", function() {
    console.log("Tuber Market loaded successfully!");

    // Add to cart functionality
    window.addToCart = function(productId) {
        // Add animation feedback
        event.target.innerHTML = '<i class="fas fa-check"></i>';
        event.target.style.background = '#28a745';
        
        // You would typically make an AJAX call here to add to cart
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
        console.log('Filter by category:', this.value);
    });

    document.getElementById('sortFilter').addEventListener('change', function() {
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
});