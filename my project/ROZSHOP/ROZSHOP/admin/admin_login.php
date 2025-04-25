<?php
session_start();
require_once '../config/database.php'; // Database connection

// Redirect if already logged in
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: dashboard.php");
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validate inputs
    if (empty($username) || empty($password)) {
        $_SESSION['error_message'] = "يرجى إدخال اسم المستخدم وكلمة المرور";
        header("Location: admin_login.php");
        exit;
    }

    // Prepare SQL with error handling
    try {
        $stmt = $db->prepare("SELECT id, username, password FROM admins WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $admin = $result->fetch_assoc();
            
            if (password_verify($password, $admin['password'])) {
                // Successful login
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['welcome_message'] = 'مرحباً بك في لوحة التحكم!';

                // Handle "Remember me"
                if (isset($_POST['remember'])) {
                    setcookie('admin_remember', $admin['username'], time() + (86400 * 30), "/", "", true, true);
                } else {
                    setcookie('admin_remember', '', time() - 3600, "/");
                }

                // Regenerate session ID for security
                session_regenerate_id(true);
                
                header("Location: dashboard.php");
                exit;
            }
        }
        
        // Generic error message for security (don't reveal which was wrong)
        $_SESSION['error_message'] = "بيانات الاعتماد غير صحيحة";
        header("Location: admin_login.php");
        exit;
        
    } catch (Exception $e) {
        error_log("Login error: " . $e->getMessage());
        $_SESSION['error_message'] = "حدث خطأ أثناء محاولة تسجيل الدخول";
        header("Location: admin_login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل دخول الإدارة - ROSE SHOP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #d63384;
            --primary-hover: #c82373;
            --secondary-color: #6c757d;
        }
        
        body {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            font-family: 'Tajawal', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .login-container {
            max-width: 100%;
            width: 400px;
        }
        
        .login-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .login-header {
            background-color: var(--primary-color);
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .login-body {
            padding: 30px;
        }
        
        .login-title {
            font-weight: 700;
            margin-bottom: 25px;
        }
        
        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #ced4da;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(214, 51, 132, 0.25);
        }
        
        .btn-login {
            background-color: var(--primary-color);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            background-color: var(--primary-hover);
        }
        
        .password-toggle {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--secondary-color);
        }
        
        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .forgot-password {
            color: var(--secondary-color);
            transition: color 0.3s;
        }
        
        .forgot-password:hover {
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .alert {
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="login-container">
                <div class="login-card">
                    <div class="login-header">
                        <h4 class="mb-0">ROSE SHOP</h4>
                        <p class="mb-0">نظام إدارة المتجر</p>
                    </div>
                    
                    <div class="login-body">
                        <h5 class="login-title text-center">تسجيل دخول الإدارة</h5>
                        
                        <?php if (isset($_SESSION['error_message'])): ?>
                            <div class="alert alert-danger text-center">
                                <?= htmlspecialchars($_SESSION['error_message']) ?>
                            </div>
                            <?php unset($_SESSION['error_message']); ?>
                        <?php endif; ?>
                        
                        <form method="post" autocomplete="off">
                            <div class="mb-3">
                                <label for="username" class="form-label">اسم المستخدم</label>
                                <input type="text" class="form-control" id="username" name="username" 
                                    value="<?= isset($_COOKIE['admin_remember']) ? htmlspecialchars($_COOKIE['admin_remember']) : '' ?>" 
                                    required autofocus>
                            </div>
                            
                            <div class="mb-3 position-relative">
                                <label for="password" class="form-label">كلمة المرور</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <i class="bi bi-eye-slash password-toggle" id="togglePassword"></i>
                            </div>
                            
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember"
                                    <?= isset($_COOKIE['admin_remember']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="remember">تذكرني</label>
                            </div>
                            
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-login btn-primary">
                                    <i class="bi bi-box-arrow-in-left"></i> تسجيل الدخول
                                </button>
                            </div>
                            
                            <div class="text-center">
                                <a href="forgot_password.php" class="forgot-password">نسيت كلمة المرور؟</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        
        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });
        
        // Focus on username field if empty, otherwise password
        document.addEventListener('DOMContentLoaded', function() {
            const username = document.getElementById('username');
            if (username.value === '') {
                username.focus();
            } else {
                document.getElementById('password').focus();
            }
        });
    </script>
</body>
</html>