<?php
require_once __DIR__ . '/includes/db.php';
if(session_status()===PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/includes/header.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])){
    header('Location:/login.php');
    exit;
}

// This page simulates a payment gateway.
// In a real application, this would be where you integrate
// with a service like Razorpay, Stripe, etc.

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['payment_method'] === 'online') {
    // For this mockup, we'll just show a success message
    // and then redirect to the final checkout script.
    // The final checkout script will receive 'online' as the method.
    ?>
    <main class="container mx-auto p-4 py-8">
        <div class="max-w-xl mx-auto text-center">
            <h2 class="text-3xl font-bold text-blue-600 mb-4">Processing Online Payment...</h2>
            <div class="p-8 rounded-lg shadow bg-white">
                <i class="fas fa-spinner fa-spin text-5xl text-blue-500 mb-4"></i>
                <p class="text-gray-600">Your payment is being processed. Please do not close this window.</p>
                <p class="text-sm mt-4 text-gray-400">Redirecting to order confirmation page in a moment...</p>
            </div>
        </div>
    </main>
    <script>
        // Use JavaScript to redirect after a delay
        setTimeout(function() {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = 'checkout.php';
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'payment_method';
            input.value = 'online';
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }, 2000); // Redirect after 2 seconds
    </script>
    <?php
} else {
    // If accessed directly, redirect
    header('Location: /project/cart.php');
    exit;
}
require_once __DIR__ . '/includes/footer.php';
?>