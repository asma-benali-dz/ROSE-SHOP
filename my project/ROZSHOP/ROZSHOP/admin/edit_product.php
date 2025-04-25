<?php
require_once ('../config/database.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: products_list.php");
    exit();
}

$id = intval($_GET['id']);
// جلب بيانات المنتج الحالي
$stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    header("Location: products_list.php");
    exit();
}

$product = $result->fetch_assoc();

// معالجة التحديث
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = floatval($_POST['price']);

    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target = "../images/" . basename($image);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            // حذف الصورة القديمة إذا تم تحميل صورة جديدة بنجاح
            if (!empty($product['image'])) {
                $oldImagePath = "../images/" . $product['image'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $stmt = $db->prepare("UPDATE products SET name=?, description=?, price=?, image=? WHERE id=?");
            $stmt->bind_param("ssdsi", $name, $desc, $price, $image, $id);
        } else {
            $message = "<div class='alert alert-danger'>فشل في تحميل الصورة الجديدة</div>";
        }
    } else {
        $stmt = $db->prepare("UPDATE products SET name=?, description=?, price=? WHERE id=?");
        $stmt->bind_param("ssdi", $name, $desc, $price, $id);
    }

    if (!isset($message) && $stmt->execute()) {
        $message = "<div class='alert alert-success'>تم تحديث المنتج بنجاح!</div>";
        // إعادة تحميل البيانات المحدثة
        $stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $product = $stmt->get_result()->fetch_assoc();
    } else if (!isset($message)) {
        $message = "<div class='alert alert-danger'>فشل التحديث</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل منتج - ROSE SHOP</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Tajawal', sans-serif;
        }
        .product-form {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 30px;
            margin-top: 30px;
        }
        .form-title {
            color: #e91e63;
            border-bottom: 2px solid #e91e63;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .current-image {
            max-width: 200px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .btn-action {
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-back {
            background: linear-gradient(135deg, #6c757d, #495057);
            color: white;
        }
        .btn-back:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="product-form">
            <h2 class="form-title text-center">تعديل المنتج</h2>
            
            <?php if (isset($message)) echo $message; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">اسم المنتج:</label>
                    <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">الوصف:</label>
                    <textarea class="form-control" name="description" rows="4" required><?= htmlspecialchars($product['description']) ?></textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">السعر:</label>
                    <input type="number" step="0.01" class="form-control" name="price" value="<?= $product['price'] ?>" required>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">صورة المنتج:</label>
                    <input type="file" class="form-control" name="image" accept="image/*">
                    <?php if (!empty($product['image'])): ?>
                        <div class="mt-3 text-center">
                            <img src="../images/<?= htmlspecialchars($product['image']) ?>" class="current-image img-thumbnail">
                            <p class="text-muted mt-2">الصورة الحالية</p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary btn-action">حفظ التعديلات</button>
                    <a href="products_list.php" class="btn btn-back btn-action">
                        <i class="bi bi-arrow-left"></i> العودة للقائمة
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>