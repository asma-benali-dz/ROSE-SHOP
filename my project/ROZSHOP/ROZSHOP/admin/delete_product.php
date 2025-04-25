<?php
session_start();
require_once('../config/database.php'); // استخدم ملف اتصال واحد فقط

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

$message = ""; // تهيئة متغير الرسالة

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = (int)$_GET['id'];
    
    try {
        // التحقق من وجود المنتج أولاً
        $check_stmt = $db->prepare("SELECT id FROM products WHERE id = ?");
        $check_stmt->bind_param("i", $product_id);
        $check_stmt->execute();
        $check_stmt->store_result();
        
        if ($check_stmt->num_rows > 0) {
            // حذف المنتج
            $delete_stmt = $db->prepare("DELETE FROM products WHERE id = ?");
            $delete_stmt->bind_param("i", $product_id);
            
            if ($delete_stmt->execute()) {
                $_SESSION['success_message'] = "تم حذف المنتج بنجاح!";
                header('Location: dashboard.php');
                exit;
            } else {
                $message = "حدث خطأ أثناء حذف المنتج: " . $db->error;
            }
            
            $delete_stmt->close();
        } else {
            $message = "المنتج غير موجود أو تم حذفه مسبقاً";
        }
        
        $check_stmt->close();
    } catch (Exception $e) {
        $message = "حدث خطأ: " . $e->getMessage();
    }
} else {
    $message = "معرّف المنتج غير صالح";
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>حذف منتج - ROSE SHOP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #d63384;
            --secondary-color: #f8f9fa;
            --danger-color: #dc3545;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Tajawal', sans-serif;
        }
        
        .delete-container {
            max-width: 600px;
            margin: 100px auto;
            animation: fadeIn 0.5s ease-in-out;
        }
        
        .delete-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .delete-header {
            background-color: var(--danger-color);
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .delete-body {
            padding: 30px;
            background-color: white;
        }
        
        .btn-back {
            background-color: var(--primary-color);
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .btn-back:hover {
            background-color: #c82373;
            transform: translateY(-2px);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="delete-container">
        <div class="delete-card">
            <div class="delete-header">
                <h3><i class="fas fa-trash-alt"></i> حذف المنتج</h3>
            </div>
            <div class="delete-body text-center">
                <?php if (!empty($message)): ?>
                    <div class="alert <?= strpos($message, 'نجاح') !== false ? 'alert-success' : 'alert-danger' ?>">
                        <i class="fas <?= strpos($message, 'نجاح') !== false ? 'fa-check-circle' : 'fa-exclamation-circle' ?>"></i>
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endif; ?>
                
                <div class="mt-4">
                    <a href="dashboard.php" class="btn btn-back text-white">
                        <i class="fas fa-arrow-right"></i> العودة إلى لوحة التحكم
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>   