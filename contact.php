<?php 
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth.php"; // Include auth.php to start the session, but don't call a redirect function
include __DIR__ . "/includes/header.php";

// A simple example for form submission feedback (no backend logic)
$status = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // In a real application, you would process the form data here
    // and send an email or save to a database.
    $status = 'success'; // or 'error' based on processing result
}
?>

<main class="container mx-auto p-4 py-12">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-green-700 mb-4">Get in Touch</h1>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto">
            We're here to help! Whether you have questions about our products, need support, or want to discuss a partnership, feel free to contact us.
        </p>
    </div>

    <?php if ($status === 'success'): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-8 max-w-xl mx-auto" role="alert">
            <strong class="font-bold">Thank you!</strong>
            <span class="block sm:inline">Your message has been sent successfully. We will get back to you shortly.</span>
        </div>
    <?php elseif ($status === 'error'): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-8 max-w-xl mx-auto" role="alert">
            <strong class="font-bold">Oops!</strong>
            <span class="block sm:inline">There was an error sending your message. Please try again later.</span>
        </div>
    <?php endif; ?>

    <section class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-lg">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Contact Form</h2>
        <form action="contact.php" method="POST" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-gray-700 font-medium mb-1">Name</label>
                    <input type="text" id="name" name="name" required class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-600">
                </div>
                <div>
                    <label for="email" class="block text-gray-700 font-medium mb-1">Email</label>
                    <input type="email" id="email" name="email" required class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-600">
                </div>
            </div>
            <div>
                <label for="subject" class="block text-gray-700 font-medium mb-1">Subject</label>
                <input type="text" id="subject" name="subject" required class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-600">
            </div>
            <div>
                <label for="message" class="block text-gray-700 font-medium mb-1">Message</label>
                <textarea id="message" name="message" rows="5" required class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-600"></textarea>
            </div>
            <div class="text-center">
                <button type="submit" class="bg-green-700 text-white font-semibold py-3 px-8 rounded-full hover:bg-green-600 transition-colors duration-300">
                    Send Message
                </button>
            </div>
        </form>
    </section>

    <section class="mt-12 text-center">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Other Ways to Connect</h2>
        <p class="text-gray-600 mb-4">
            You can also reach us via phone or email for direct inquiries.
        </p>
        <div class="flex flex-col md:flex-row justify-center gap-8 text-lg">
            <div class="flex items-center space-x-2">
                <i class="fas fa-phone-alt text-green-700"></i>
                <span class="text-gray-700">+91 123 456 7890</span>
            </div>
            <div class="flex items-center space-x-2">
                <i class="fas fa-envelope text-green-700"></i>
                <span class="text-gray-700">support@tuber.market</span>
            </div>
        </div>
    </section>
</main>

<?php include __DIR__ . "/includes/footer.php"; ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        console.log("Contact page loaded successfully!");
    });
</script>