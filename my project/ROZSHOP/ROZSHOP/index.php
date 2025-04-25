<?php
// index.php - الصفحة الرئيسية لعرض المنتجات
require_once 'config.php';

// جلب جميع المنتجات من قاعدة البيانات
$stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 1");
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    $product = [
        'name' => 'لا يوجد منتجات متاحة حالياً',
        'price' => '0.00',
        'image_path' => 'placeholder.png'
    ];
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>موقع تسوق</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 300px;
            border-radius: 10px;
        }
        .product-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .product-info {
            margin-bottom: 15px;
        }
        .product-name {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 5px;
        }
        .product-price {
            color: #d63384;
            font-size: 20px;
            font-weight: bold;
        }
        .old-price {
            text-decoration: line-through;
            color: #999;
            font-size: 16px;
            margin-left: 10px;
        }
        button {
            background-color: #d63384;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            width: 100%;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #c2185b;
        }
        .change-product {
            background-color: #6c757d;
            margin-top: 10px;
        }
        .change-product:hover {
            background-color: #5a6268;
        }
        .admin-link {
            display: block;
            margin-top: 15px;
            color: #6c757d;
            text-decoration: none;
            font-size: 14px;
        }
        .admin-link:hover {
            color: #d63384;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>متجر الكتروني للمكياج والعناية بالبشرة</h2>
        <img src="<?= htmlspecialchars($product['image_path']) ?>" alt="صورة المنتج" class="product-image">
        <div class="product-info">
            <div class="product-name"><?= htmlspecialchars($product['name']) ?></div>
            <div>
                <span class="product-price"><?= number_format($product['price'], 2) ?> ر.س</span>
                <?php if (!empty($product['old_price'])): ?>
                    <span class="old-price"><?= number_format($product['old_price'], 2) ?> ر.س</span>
                <?php endif; ?>
            </div>
        </div>
        <button>شراء</button>
        <button class="change-product">تغيير المنتج</button>
        <a href="admin_login.php" class="admin-link">الدخول لوحة التحكم</a>
    </div>
</body>
</html>