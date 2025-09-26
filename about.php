<?php 
require_once __DIR__ . "/includes/db.php";
include __DIR__ . "/includes/header.php";
?>

<main class="container mx-auto p-4 py-12">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-green-700 mb-4">Our Story at The Tuber Cart</h1>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto">
            Connecting communities, from the soil to your table.
        </p>
    </div>

    <section class="mb-12">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="md:col-span-1">
                <img src="assets/images/about-farm.jpg" alt="A photo of a farm" class="rounded-3xl shadow-xl transform hover:scale-105 transition-transform duration-300">
            </div>
            <div class="md:col-span-1">
                <h2 class="text-3xl font-semibold text-gray-800 mb-4">Our Mission</h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    The Tuber Cart is a platform built to foster a thriving agricultural community. Our mission is to directly connect local farmers and wholesalers with customers, ensuring everyone gets the best value and quality. By cutting out the middlemen, we bring farm-fresh tubers directly to your doorstep.
                </p>
                <p class="text-gray-700 leading-relaxed">
                    We believe in fair trade, sustainability, and transparency. Our platform is designed to empower farmers, provide wholesalers with an efficient way to source products, and give customers access to the freshest produce.
                </p>
            </div>
        </div>
    </section>

    <section class="mb-12">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-semibold text-gray-800">How It Works</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div class="p-6 bg-green-50 rounded-2xl shadow-md transform hover:translate-y-[-8px] transition-transform duration-300">
                <div class="text-4xl text-green-700 mb-4">
                    <i class="fas fa-tractor"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">For Farmers</h3>
                <p class="text-gray-600">
                    List your fresh produce directly on our platform and reach thousands of customers. Get fair prices for your hard work with a simple, transparent process.
                </p>
            </div>
            <div class="p-6 bg-yellow-50 rounded-2xl shadow-md transform hover:translate-y-[-8px] transition-transform duration-300">
                <div class="text-4xl text-yellow-600 mb-4">
                    <i class="fas fa-warehouse"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">For Wholesalers</h3>
                <p class="text-gray-600">
                    Source bulk quantities of tubers directly from trusted farmers. Streamline your supply chain and ensure product consistency and quality.
                </p>
            </div>
            <div class="p-6 bg-blue-50 rounded-2xl shadow-md transform hover:translate-y-[-8px] transition-transform duration-300">
                <div class="text-4xl text-blue-700 mb-4">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">For Customers</h3>
                <p class="text-gray-600">
                    Browse and buy a wide variety of tubers. Enjoy the convenience of having the freshest products delivered directly to your home.
                </p>
            </div>
        </div>
    </section>

    <section class="text-center">
        <h2 class="text-3xl font-semibold text-gray-800 mb-4">Join Our Community</h2>
        <p class="text-gray-700 max-w-xl mx-auto mb-6">
            Whether you are a farmer, a wholesaler, or someone who loves fresh, healthy food, there is a place for you in our growing community.
        </p>
        <a href="register.php" class="inline-block bg-green-700 text-white font-semibold py-3 px-8 rounded-full hover:bg-green-600 transition-colors duration-300">
            Create an Account
        </a>
    </section>
</main>

<?php include __DIR__ . "/includes/footer.php"; ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        console.log("About page loaded successfully!");
    });
</script>