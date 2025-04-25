<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    $stmt = $db->prepare("UPDATE admins SET password = ? WHERE username = ?");
    $stmt->bind_param("ss", $new_password, $email);
    $stmt->execute();

    $_SESSION['success_message'] = "<div class='alert alert-success text-center'><i class='bi bi-check-circle-fill'></i> تم تحديث كلمة المرور بنجاح!</div>";
    header("Location: admin_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>استعادة كلمة المرور - لوحة التحكم</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/style3.css">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #ffcdd2, #f8bbd0); /* تدرج لوني وردي ناعم */
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        .container {
            background-color: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            width: 90%;
            max-width: 500px;
            text-align: center;
        }
        h2 {
            color: #e91e63; /* لون وردي جذاب */
            margin-bottom: 30px;
            font-size: 2.5rem;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .form-label {
            font-weight: 600;
            color: #555;
            margin-bottom: 10px;
            display: block;
            text-align: right;
        }
        .form-control {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 14px;
            margin-bottom: 25px;
            width: calc(100% - 28px);
            box-sizing: border-box;
            font-size: 1rem;
            direction: rtl;
        }
        .form-control:focus {
            border-color: #e91e63;
            box-shadow: 0 0 0 0.2rem rgba(233, 30, 99, 0.25);
        }
        .btn-primary {
            background-color: #e91e63; /* لون وردي جذاب */
            border-color: #e91e63;
            border-radius: 10px;
            padding: 14px 30px;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .btn-primary:hover {
            background-color: #d81b60;
            border-color: #d81b60;
            transform: scale(1.02);
            box-shadow: 0 4px 7px rgba(0, 0, 0, 0.15);
        }
        .alert {
            margin-top: 20px;
            border-radius: 10px;
            padding: 15px;
            font-weight: 500;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        .alert-success {
            background-color: #e8f5e9;
            border-color: #c8e6c9;
            color: #1b5e20;
        }
        .form-container {
            background-color: #fdecea; /* خلفية فاتحة للنموذج */
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="bi bi-key-fill text-danger"></i> استعادة كلمة المرور</h2>
        <div class="form-container">
            <form method="post">
                <div class="mb-3">
                    <label for="email" class="form-label">البريد الإلكتروني أو اسم المستخدم:</label>
                    <input type="text" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="new_password" class="form-label">كلمة المرور الجديدة:</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                </div>
                <button type="submit" class="btn btn-primary"><i class="bi bi-arrow-clockwise me-2"></i> تحديث كلمة المرور</button>
            </form>
        </div>
        <p class="mt-3"><a href="admin_login.php" class="text-decoration-none"><i class="bi bi-arrow-right-short"></i> العودة إلى صفحة تسجيل الدخول</a></p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>