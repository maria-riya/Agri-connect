<?php
require_once __DIR__ . '/includes/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pw    = $_POST['password'] ?? '';

    // Basic validations
    if (!$email || !$pw) {
        $errors[] = 'Email and password are required.';
    }

    if (!$errors) {
        try {
            $stmt = $pdo->prepare("SELECT id, name, email, password_hash, role FROM users WHERE email = ? LIMIT 1");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($pw, $user['password_hash'])) {
                // ✅ Correct login
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];

                // Redirect based on role
                switch ($user['role']) {
                    case 'admin':
                        header("Location: admin/dashboard.php");
                        break;
                    case 'farmer':
                        header("Location: farmer/dashboard.php");
                        break;
                    case 'wholesaler':
                        header("Location: wholesaler/dashboard.php");
                        break;
                    default: // customer
                        header("Location: index.php");
                        break;
                }
                exit;
            } else {
                $errors[] = "Invalid email or password.";
            }
        } catch (Exception $e) {
            $errors[] = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AgriConnect</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #22c55e;
            --primary-dark: #16a34a;
            --primary-light: #dcfce7;
            --secondary-color: #0ea5e9;
            --secondary-dark: #0284c7;
            --accent-color: #f59e0b;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --text-light: #9ca3af;
            --background-primary: #ffffff;
            --background-secondary: #f8fafc;
            --border-light: #e5e7eb;
            --border-focus: #22c55e;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            --gradient-primary: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            --gradient-background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 50%, #d1fae5 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: var(--gradient-background);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Background Pattern */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(34, 197, 94, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(14, 165, 233, 0.1) 0%, transparent 50%);
            pointer-events: none;
            z-index: -1;
        }

        .login-container {
            background: var(--background-primary);
            border-radius: 24px;
            box-shadow: var(--shadow-xl);
            padding: 48px;
            width: 100%;
            max-width: 460px;
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(20px);
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: var(--gradient-primary);
            border-radius: 24px 24px 0 0;
        }

        .logo {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo-icon {
            width: 64px;
            height: 64px;
            background: var(--gradient-primary);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            box-shadow: var(--shadow-lg);
            transform: rotate(-5deg);
            transition: transform 0.3s ease;
        }

        .logo-icon:hover {
            transform: rotate(0deg) scale(1.05);
        }

        .logo-icon i {
            font-size: 28px;
            color: white;
        }

        .logo h1 {
            color: var(--text-primary);
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .logo p {
            color: var(--text-secondary);
            font-size: 16px;
            font-weight: 400;
        }

        .welcome-text {
            text-align: center;
            margin-bottom: 32px;
            padding: 20px;
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.05) 0%, rgba(14, 165, 233, 0.05) 100%);
            border-radius: 16px;
            border: 1px solid rgba(34, 197, 94, 0.1);
        }

        .welcome-text h2 {
            color: var(--text-primary);
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .welcome-text p {
            color: var(--text-secondary);
            font-size: 14px;
        }

        .error-container {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border: 1px solid #fecaca;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 24px;
            animation: slideInDown 0.4s ease-out;
            position: relative;
        }

        .error-container::before {
            content: '';
            position: absolute;
            left: 20px;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #ef4444;
            border-radius: 2px;
        }

        .error-container .error-header {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
        }

        .error-container .error-header i {
            color: #ef4444;
            font-size: 20px;
            margin-right: 12px;
        }

        .error-container .error-header span {
            color: #dc2626;
            font-weight: 600;
            font-size: 14px;
        }

        .error-container p {
            color: #dc2626;
            margin: 8px 0 8px 32px;
            font-size: 14px;
            line-height: 1.5;
        }

        @keyframes slideInDown {
            from {
                transform: translateY(-10px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .form-group {
            margin-bottom: 28px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            color: var(--text-primary);
            font-weight: 600;
            font-size: 14px;
            letter-spacing: 0.025em;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            transition: all 0.3s ease;
            font-size: 16px;
        }

        .form-control {
            width: 100%;
            padding: 16px 16px 16px 48px;
            border: 2px solid var(--border-light);
            border-radius: 12px;
            font-size: 16px;
            font-weight: 400;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: var(--background-primary);
            color: var(--text-primary);
        }

        .form-control::placeholder {
            color: var(--text-light);
            font-weight: 400;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--border-focus);
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1);
            transform: translateY(-1px);
        }

        .form-control:focus + i {
            color: var(--primary-color);
            transform: translateY(-50%) scale(1.1);
        }

        .forgot-password {
            text-align: right;
            margin-top: -20px;
            margin-bottom: 24px;
        }

        .forgot-password a {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .forgot-password a:hover {
            color: var(--primary-color);
        }

        .btn-login {
            width: 100%;
            padding: 18px;
            background: var(--gradient-primary);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-md);
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.6s ease;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .divider {
            position: relative;
            text-align: center;
            margin: 32px 0;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--border-light);
        }

        .divider span {
            background: var(--background-primary);
            color: var(--text-secondary);
            padding: 0 16px;
            font-size: 14px;
            font-weight: 500;
        }

        .register-link {
            text-align: center;
            margin-top: 24px;
            padding: 24px;
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.03) 0%, rgba(14, 165, 233, 0.03) 100%);
            border-radius: 16px;
            border: 1px solid rgba(34, 197, 94, 0.1);
        }

        .register-link p {
            color: var(--text-secondary);
            font-size: 15px;
            font-weight: 400;
            margin-bottom: 12px;
        }

        .register-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .register-link a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 50%;
            background: var(--primary-color);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .register-link a:hover::after {
            width: 100%;
        }

        .register-link a:hover {
            color: var(--primary-dark);
            transform: translateY(-1px);
        }

        .features-list {
            display: flex;
            justify-content: center;
            gap: 24px;
            margin-top: 16px;
            flex-wrap: wrap;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--text-secondary);
            font-size: 12px;
            font-weight: 500;
        }

        .feature-item i {
            color: var(--primary-color);
            font-size: 14px;
        }

        /* Loading state */
        .btn-login.loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .btn-login.loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
            border: 2px solid transparent;
            border-top-color: white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive Design */
        @media (max-width: 640px) {
            body {
                padding: 16px;
            }
            
            .login-container {
                padding: 32px 24px;
                margin: 0;
                border-radius: 20px;
            }
            
            .logo h1 {
                font-size: 28px;
            }
            
            .welcome-text h2 {
                font-size: 20px;
            }

            .form-group {
                margin-bottom: 24px;
            }

            .features-list {
                flex-direction: column;
                gap: 8px;
                align-items: center;
            }
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 28px 20px;
            }
            
            .logo-icon {
                width: 56px;
                height: 56px;
            }
            
            .logo-icon i {
                font-size: 24px;
            }
            
            .logo h1 {
                font-size: 24px;
            }
        }

        /* Focus trap for accessibility */
        .login-container:focus-within {
            box-shadow: var(--shadow-xl), 0 0 0 4px rgba(34, 197, 94, 0.1);
        }

        /* Password visibility toggle */
        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-light);
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .password-toggle:hover {
            color: var(--primary-color);
            background: rgba(34, 197, 94, 0.1);
        }

        .password-toggle:focus {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <div class="logo-icon">
                <i class="fas fa-seedling"></i>
            </div>
            <h1>The Tuber Cart</h1>
            <p>Agricultural Community Platform</p>
        </div>

        <div class="welcome-text">
            <h2>Welcome Back!</h2>
            <p>Sign in to access your dashboard</p>
        </div>

        <?php if ($errors): ?>
            <div class="error-container">
                <div class="error-header">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Authentication Error:</span>
                </div>
                <?php foreach ($errors as $e): ?>
                    <p><?= htmlspecialchars($e) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" id="loginForm">
            <div class="form-group">
                <label for="email">Email Address</label>
                <div class="input-wrapper">
                    <input type="email" name="email" id="email" class="form-control" 
                           placeholder="Enter your email address" required
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    <i class="fas fa-envelope"></i>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <input type="password" name="password" id="password" class="form-control" 
                           placeholder="Enter your password" required>
                    <i class="fas fa-lock"></i>
                    <button type="button" class="password-toggle" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="forgot-password">
                <a href="#" onclick="showForgotPasswordModal()">Forgot your password?</a>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </button>
        </form>

        <div class="divider">
            <span>New to Tuber Cart?</span>
        </div>

        <div class="register-link">
            <p>Join our growing agricultural community</p>
            <a href="register.php">
                <i class="fas fa-user-plus"></i>
                Create Account
            </a>
            <div class="features-list">
                <div class="feature-item">
                    <i class="fas fa-check-circle"></i>
                    <span>Free to join</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-users"></i>
                    <span>Growing community</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-leaf"></i>
                    <span>Sustainable farming</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Forgot Password Modal -->
    <div id="forgotPasswordModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; backdrop-filter: blur(4px);">
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 32px; border-radius: 16px; max-width: 400px; width: 90%; box-shadow: var(--shadow-xl);">
            <h3 style="color: var(--text-primary); font-size: 20px; font-weight: 600; margin-bottom: 16px; text-align: center;">Reset Password</h3>
            <p style="color: var(--text-secondary); font-size: 14px; text-align: center; margin-bottom: 24px;">Enter your email address and we'll send you a link to reset your password.</p>
            
            <div style="margin-bottom: 20px;">
                <input type="email" placeholder="Enter your email address" style="width: 100%; padding: 12px; border: 2px solid var(--border-light); border-radius: 8px; font-size: 14px;">
            </div>
            
            <div style="display: flex; gap: 12px;">
                <button onclick="closeForgotPasswordModal()" style="flex: 1; padding: 12px; border: 2px solid var(--border-light); background: white; color: var(--text-secondary); border-radius: 8px; cursor: pointer; font-weight: 500;">Cancel</button>
                <button style="flex: 1; padding: 12px; background: var(--gradient-primary); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Send Reset Link</button>
            </div>
        </div>
    </div>

    <script>
        // Password visibility toggle
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        });

        // Enhanced form validation with loading state
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('.btn-login');
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            
            if (!email || !password) {
                e.preventDefault();
                showError('Please fill in all fields');
                return;
            }
            
            // Add loading state
            submitBtn.classList.add('loading');
            submitBtn.innerHTML = 'Signing In...';
        });

        // Enhanced error display function
        function showError(message) {
            const existingError = document.querySelector('.error-container');
            if (existingError) {
                existingError.remove();
            }
            
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-container';
            errorDiv.innerHTML = `
                <div class="error-header">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Validation Error:</span>
                </div>
                <p>${message}</p>
            `;
            
            const form = document.getElementById('loginForm');
            form.parentNode.insertBefore(errorDiv, form);
            
            // Scroll to error
            errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            // Remove error after 5 seconds
            setTimeout(() => {
                errorDiv.remove();
            }, 5000);
        }

        // Add smooth transitions for form interactions
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentNode.parentNode.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentNode.parentNode.style.transform = 'scale(1)';
            });
        });

        // Forgot password modal functions
        function showForgotPasswordModal() {
            document.getElementById('forgotPasswordModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeForgotPasswordModal() {
            document.getElementById('forgotPasswordModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Close modal on outside click
        document.getElementById('forgotPasswordModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeForgotPasswordModal();
            }
        });

        // Add keyboard navigation improvements
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Tab') {
                document.body.classList.add('keyboard-navigation');
            }
            
            // Close modal on Escape
            if (e.key === 'Escape') {
                closeForgotPasswordModal();
            }
        });

        document.addEventListener('mousedown', function() {
            document.body.classList.remove('keyboard-navigation');
        });

        // Auto-focus email field on page load
        window.addEventListener('load', function() {
            setTimeout(() => {
                document.getElementById('email').focus();
            }, 500);
        });

        // Add enter key handling for password field
        document.getElementById('password').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('loginForm').dispatchEvent(new Event('submit'));
            }
        });
    </script>

    <style>
        /* Keyboard navigation styles */
        .keyboard-navigation .form-control:focus,
        .keyboard-navigation .btn-login:focus,
        .keyboard-navigation .password-toggle:focus {
            outline: 3px solid rgba(34, 197, 94, 0.5);
            outline-offset: 2px;
        }

        /* Input animation on focus */
        .form-control:focus {
            animation: inputFocus 0.3s ease;
        }

        @keyframes inputFocus {
            0% { transform: scale(1) translateY(0); }
            50% { transform: scale(1.01) translateY(-1px); }
            100% { transform: scale(1) translateY(-1px); }
        }

        /* Success animation for future use */
        @keyframes successPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .success-animation {
            animation: successPulse 0.6s ease;
        }
    </style>
</body>
</html>