<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}
?>
<?php if (isset($_SESSION['welcome_message'])): ?>
    <div class="alert alert-success text-center">
        <?= $_SESSION['welcome_message'] ?>
    </div>
    <?php unset($_SESSION['welcome_message']); ?>
<?php endif; ?>


<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة التحكم - ROSE SHOP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    
    <!-- أيقونات Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- style.css -->
    <link rel="stylesheet" href="../css/style3.css">
    <style>
    .fade-in-up {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.6s ease-out, transform 0.6s ease-out;
    }

    .fade-in-up.visible {
        opacity: 1;
        transform: translateY(0);
    }
</style>

</head>
<body class="bg-light">

<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">لوحة التحكم - ROSE SHOP</a>
        <a href="add_product.php" class="btn btn-success">➕ إضافة منتج</a>
        <a href="edit_product.php?id=123" class="btn btn-warning">✏️ تعديل</a>
        <a href="delete_product.php?id=123" class="btn btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">🗑️ حذف</a>
        <a href="view_orders.php" class="btn btn-info">📋 عرض الطلبات</a>
        <a href="../index.php" class="btn btn-secondary">🏪 العودة للمتجر</a>
        <div class="d-flex ms-auto align-items-center">
            <h5 class="text-white mb-0 me-3">أهلاً، <?= $_SESSION['admin_username'] ?> 🌹</h5>
            <a href="logout.php" class="btn btn-outline-light"><i class="fas fa-sign-out-alt"></i> تسجيل الخروج</a>
        </div>
    </div>
</nav>

<div class="container mt-5 pt-5">
    <h2 class="mb-4 text-center">👩‍💼 مرحباً بك في لوحة الإدارة</h2>
    
    <div class="row g-4">

        <!-- إضافة منتج -->
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card text-bg-success h-100 text-center">
                <div class="card-body">
                    <i class="fas fa-plus fa-3x mb-3"></i>
                    <h5 class="card-title">إضافة منتج</h5>
                    <p class="card-text">أضف منتجات جديدة إلى المتجر</p>
                    <a href="add_product.php" class="btn btn-light">انتقال</a>
                </div>
            </div>
        </div>

        <!-- تعديل منتج -->
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card text-bg-warning h-100 text-center">
                <div class="card-body">
                    <i class="fas fa-edit fa-3x mb-3"></i>
                    <h5 class="card-title">تعديل منتج</h5>
                    <p class="card-text">تعديل بيانات المنتجات الحالية</p>
                    <a href="edit_product.php" class="btn btn-light">انتقال</a>
                </div>
            </div>
        </div>

        <!-- عرض المنتجات -->
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card text-bg-primary h-100 text-center">
                <div class="card-body">
                    <i class="fas fa-box fa-3x mb-3"></i>
                    <h5 class="card-title">عرض المنتجات</h5>
                    <p class="card-text">عرض جميع المنتجات في المتجر</p>
                    <a href="view_products.php" class="btn btn-light">انتقال</a>
                </div>
            </div>
        </div>

        <!-- حذف منتج -->
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card text-bg-danger h-100 text-center">
                <div class="card-body">
                    <i class="fas fa-trash fa-3x mb-3"></i>
                    <h5 class="card-title">حذف منتج</h5>
                    <p class="card-text">حذف المنتجات من قاعدة البيانات</p>
                    <a href="delete_product.php" class="btn btn-light">انتقال</a>
                </div>
            </div>
        </div>

        <!-- عرض الطلبات -->
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card text-bg-info h-100 text-center">
                <div class="card-body">
                    <i class="fas fa-clipboard-list fa-3x mb-3"></i>
                    <h5 class="card-title">عرض الطلبات</h5>
                    <p class="card-text">عرض طلبات العملاء ومعالجتها</p>
                    <a href="view_orders.php" class="btn btn-light">انتقال</a>
                </div>
            </div>
        </div>

        <!-- العودة للمتجر -->
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card text-bg-secondary h-100 text-center">
                <div class="card-body">
                    <i class="fas fa-store fa-3x mb-3"></i>
                    <h5 class="card-title">العودة للمتجر</h5>
                    <p class="card-text">العودة إلى الصفحة الرئيسية للمتجر</p>
                    <a href="../index.php" class="btn btn-light">العودة</a>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const cards = document.querySelectorAll('.fade-in-up');

        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, {
            threshold: 0.1
        });

        cards.forEach(card => {
            observer.observe(card);
        });
    });
</script>
</body>
</html>
