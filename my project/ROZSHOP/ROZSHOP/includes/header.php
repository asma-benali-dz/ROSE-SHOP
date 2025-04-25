<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>متجري الإلكتروني</title>
    <link rel="stylesheet" href="/ecommerce/style.css"> <!-- لو كان عندك CSS -->
</head>
<body>
    <header>
        <h1>مرحبا بك في متجري</h1>
        <nav>
            <a href="/ecommerce/index.php">الرئيسية</a> |
            <a href="/ecommerce/cart.php">سلة التسوق</a> |
            <a href="/ecommerce/checkout.php">إتمام الشراء</a> |
            <a href="/ecommerce/auth/login.php">تسجيل الدخول</a> |
            <a href="/ecommerce/auth/register.php">تسجيل</a>
        </nav>
        <!-- index.php -->
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>Rose Shop</title>
    <link rel="stylesheet" href="css/style3.css"> <!-- أو أي ملف تنسيق تفضل -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
    <a class="navbar-brand" href="index.php">🌹 Rose Shop</a>
    <ul class="navbar-nav ms-auto">
      <li class="nav-item">
        <a class="nav-link" href="php/cart.php">🛒 السلة</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="login.php">تسجيل الدخول</a>
      </li>
    </ul>
  </div>
</nav>

        <hr>
    </header>
</body>
</html>