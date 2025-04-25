<?php
require_once ('../config/database.php');

// جلب جميع المنتجات
$stmt = $db->prepare("SELECT id, name, price, image FROM products");
$stmt->execute();
$products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$db->close();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>قائمة المنتجات - ROSE SHOP</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Tajawal', sans-serif;
        }
        .products-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 30px;
            margin-top: 30px;
        }
        .page-title {
            color: #e91e63;
            border-bottom: 2px solid #e91e63;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        .product-card {
            transition: all 0.3s;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .product-img {
            height: 180px;
            object-fit: cover;
        }
        .product-price {
            color: #e91e63;
            font-weight: bold;
        }
        .btn-edit {
            background: linear-gradient(135deg, #e91e63, #c2185b);
            color: white;
            border-radius: 50px;
        }
        .btn-add {
            background: linear-gradient(135deg, #4CAF50, #2E7D32);
            color: white;
            border-radius: 50px;
        }
    </style>
</head>
<!-- أضف هذا الزر في الـ header بعد العنوان -->
<header>
ROSE SHOP
    <a href="dashboard.php" style="float:left; color:white; text-decoration:none; background:#a12d5e; padding:5px 10px; border-radius:5px;">
        <i class="fas fa-cog"></i> لوحة التحكم
    </a>
</header>
<body>
    <div class="container">
        <div class="products-container">
            <h1 class="page-title text-center">قائمة المنتجات</h1>
            
            <div class="text-end mb-4">
                <a href="add_product.php" class="btn btn-add">
                    <i class="bi bi-plus-circle"></i> إضافة منتج جديد
                </a>
            </div>
            
            <?php if (count($products) > 0): ?>
                <div class="row">
                    <?php foreach ($products as $product): ?>
                        <div class="col-md-4">
                            <div class="product-card">
                                <?php if (!empty($product['image'])): ?>
                                    <img src="../images/<?= htmlspecialchars($product['image']) ?>" class="product-img w-100">
                                <?php else: ?>
                                    <div class="product-img bg-light d-flex align-items-center justify-content-center">
                                        <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="p-3">
                                    <h5><?= htmlspecialchars($product['name']) ?></h5>
                                    <p class="product-price"><?= $product['price'] ?> د.ج</p>
                                    <div class="d-flex justify-content-between">
                                        <a href="edit_product.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-edit">
                                            <i class="bi bi-pencil"></i> تعديل</a>
                                        <a href="product_details.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> عرض
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle"></i> لا توجد منتجات متاحة حالياً
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>