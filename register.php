<?php
require_once __DIR__ . '/includes/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Safe access
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pw    = $_POST['password'] ?? '';
    $role  = $_POST['role'] ?? 'customer'; // default role

    // Basic validations
    if (!$name || !$email || !$pw) {
        $errors[] = 'Fill all fields';
    }
    if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email';
    }

    // Duplicate email check
    if (!$errors) {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'Email already registered. Please login or use another email.';
        }
    }

    // If no errors → insert new user
    if (!$errors) {
        $hash = password_hash($pw, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare(
                'INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)'
            );
            $stmt->execute([$name, $email, $hash, $role]);

            // Redirect to login page after success
            header('Location: login.php');
            exit;
        } catch (Exception $e) {
            $errors[] = 'Error: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - The Tuber Cart</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .registration-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 480px;
            position: relative;
            overflow: hidden;
        }

        .registration-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2, #f093fb);
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

      
        .logo h1 {
            color: #333;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .logo p {
            color: #666;
            font-size: 14px;
        }

        .error-container {
            background: #fee;
            border: 1px solid #fcc;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            animation: shake 0.5s ease-in-out;
        }

        .error-container i {
            color: #e74c3c;
            margin-right: 8px;
        }

        .error-container p {
            color: #c0392b;
            margin: 5px 0;
            font-size: 14px;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            transition: color 0.3s ease;
        }

        .form-control {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 2px solid #e1e8ed;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #fff;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-control:focus + i,
        .form-control:not(:placeholder-shown) + i {
            color: #667eea;
        }

        select.form-control {
            padding-left: 45px;
            cursor: pointer;
        }

        .role-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 15px;
            margin-top: 10px;
        }

        .role-option {
            position: relative;
        }

        .role-option input[type="radio"] {
            position: absolute;
            opacity: 0;
        }

        .role-option label {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px 15px;
            border: 2px solid #e1e8ed;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #fff;
            text-align: center;
        }

        .role-option input[type="radio"]:checked + label {
            border-color: #667eea;
            background: #f8f9ff;
            color: #667eea;
        }

        .role-option label i {
            font-size: 24px;
            margin-bottom: 8px;
            transition: transform 0.3s ease;
        }

        .role-option input[type="radio"]:checked + label i {
            transform: scale(1.1);
        }

        .role-option label span {
            font-size: 12px;
            font-weight: 600;
        }

        .btn-register {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
        }

        .btn-register::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-register:hover::before {
            left: 100%;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .login-link {
            text-align: center;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #e1e8ed;
        }

        .login-link p {
            color: #666;
            font-size: 14px;
        }

        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .login-link a:hover {
            color: #764ba2;
        }

        @media (max-width: 480px) {
            .registration-container {
                padding: 30px 20px;
                margin: 10px;
            }
            
            .logo h1 {
                font-size: 24px;
            }
            
            .role-options {
                grid-template-columns: 1fr;
            }
        }

        .strength-meter {
            height: 4px;
            background: #e1e8ed;
            border-radius: 2px;
            margin-top: 8px;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .strength-weak { background: #e74c3c; width: 25%; }
        .strength-fair { background: #f39c12; width: 50%; }
        .strength-good { background: #2ecc71; width: 75%; }
        .strength-strong { background: #27ae60; width: 100%; }
    </style>
</head>
<body>
    <div class="registration-container">
        <div class="logo">
            <img src="assets/images/logo.png" alt="Logo" style="width:60px;height:60px;display:block;margin:0 auto 10px auto;">
            <i class="fas fa-seedling"></i>
            <h1>The Tuber Cart</h1>
            <p>Join our agricultural community</p>
        </div>

        <?php if ($errors): ?>
            <div class="error-container">
                <i class="fas fa-exclamation-triangle"></i>
                <?php foreach ($errors as $e): ?>
                    <p><?= htmlspecialchars($e) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" id="registrationForm">
            <div class="form-group">
                <label for="name">Full Name</label>
                <div class="input-wrapper">
                    <input type="text" name="name" id="name" class="form-control" 
                           placeholder="Enter your full name" required
                           value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                    <i class="fas fa-user"></i>
                </div>
            </div>

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
                           placeholder="Create a strong password" required>
                    <i class="fas fa-lock"></i>
                </div>
                <div class="strength-meter">
                    <div class="strength-fill" id="strengthFill"></div>
                </div>
            </div>

            <div class="form-group">
                <label>I am a:</label>
                <div class="role-options">
                    <div class="role-option">
                        <input type="radio" name="role" value="customer" id="customer" 
                               <?= ($_POST['role'] ?? 'customer') === 'customer' ? 'checked' : '' ?>>
                        <label for="customer">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Customer</span>
                        </label>
                    </div>
                    <div class="role-option">
                        <input type="radio" name="role" value="farmer" id="farmer"
                               <?= ($_POST['role'] ?? '') === 'farmer' ? 'checked' : '' ?>>
                        <label for="farmer">
                            <i class="fas fa-tractor"></i>
                            <span>Farmer</span>
                        </label>
                    </div>
                    <div class="role-option">
                        <input type="radio" name="role" value="wholesaler" id="wholesaler"
                               <?= ($_POST['role'] ?? '') === 'wholesaler' ? 'checked' : '' ?>>
                        <label for="wholesaler">
                            <i class="fas fa-warehouse"></i>
                            <span>Wholesaler</span>
                        </label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-register">
                <i class="fas fa-user-plus"></i> Create Account
            </button>
        </form>

        <div class="login-link">
            <p>Already have an account? <a href="login.php">Sign in here</a></p>
        </div>
    </div>

    <script>
        // Password strength indicator
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthFill = document.getElementById('strengthFill');
            let strength = 0;
            
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if (/\d/.test(password)) strength++;
            if (/[^a-zA-Z\d]/.test(password)) strength++;
            
            strengthFill.className = 'strength-fill';
            if (strength === 1) strengthFill.classList.add('strength-weak');
            else if (strength === 2) strengthFill.classList.add('strength-fair');
            else if (strength === 3) strengthFill.classList.add('strength-good');
            else if (strength === 4) strengthFill.classList.add('strength-strong');
        });

        // Form validation
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            
            if (!name || !email || !password) {
                e.preventDefault();
                alert('Please fill in all fields');
                return;
            }
            
            if (password.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters long');
                return;
            }
        });
    </script>
</body>
</html>