<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

// معالجة إضافة المنتج
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // هنا يجب إضافة الكود لحفظ المنتج في قاعدة البيانات
    $success = "تمت إضافة المنتج بنجاح!";
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إضافة منتج جديد - ROSE SHOP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #d63384;
            --secondary-color: #ff99cc;
            --light-pink: #ffe6f2;
            --dark-pink: #c2185b;
        }
        
        body {
            background-color: #fff5f9;
            font-family: 'Tajawal', sans-serif;
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--dark-pink));
            box-shadow: 0 4px 12px rgba(214, 51, 132, 0.2);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--dark-pink);
            border-color: var(--dark-pink);
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
            background-color: white;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px rgba(214, 51, 132, 0.15);
        }
        
        .form-control, .form-select {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #f0c1d5;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.25rem rgba(214, 51, 132, 0.25);
        }
        
        h2 {
            color: var(--primary-color);
            position: relative;
            padding-bottom: 15px;
        }
        
        h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            border-radius: 3px;
        }
        
        .file-upload {
            position: relative;
            overflow: hidden;
            border: 2px dashed #f0c1d5;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            background-color: #fff9fc;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .file-upload:hover {
            border-color: var(--secondary-color);
            background-color: #fff0f5;
        }
        
        .file-upload input {
            position: absolute;
            top: 0;
            right: 0;
            margin: 0;
            padding: 0;
            font-size: 20px;
            cursor: pointer;
            opacity: 0;
            filter: alpha(opacity=0);
            width: 100%;
            height: 100%;
        }
        
        .file-upload-icon {
            font-size: 48px;
            color: var(--secondary-color);
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="admin_dashboard.php">
                <i class="fas fa-rose me-2"></i>ROSE SHOP
            </a>
            <a href="dashboard.php" class="btn btn-outline-light">
                <i class="fas fa-arrow-right me-1"></i> العودة للوحة التحكم
            </a>
        </div>
    </nav>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card p-4">
                    <div class="card-body">
                        <h2 class="text-center mb-4">
                            <i class="fas fa-plus-circle me-2"></i>إضافة منتج جديد
                        </h2>
                        
                        <?php if (isset($success)): ?>
                            <div class="alert alert-success text-center">
                                <i class="fas fa-check-circle me-2"></i><?= $success ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                            <div class="mb-4">
                                <label for="name" class="form-label fw-bold">
                                    <i class="fas fa-tag me-2 text-pink"></i>اسم المنتج
                                </label>
                                <input type="text" class="form-control" id="name" name="name" required>
                                <div class="invalid-feedback">يرجى إدخال اسم المنتج</div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="category" class="form-label fw-bold">
                                    <i class="fas fa-list-alt me-2 text-pink"></i>الفئة
                                </label>
                                <select class="form-select" id="category" name="category" required>
                                    <option value="" selected disabled>اختر فئة المنتج</option>
                                    <option value="makeup">منتجات المكياج</option>
                                    <option value="skincare">منتجات العناية بالبشرة</option>
                                </select>
                                <div class="invalid-feedback">يرجى اختيار فئة المنتج</div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="description" class="form-label fw-bold">
                                    <i class="fas fa-align-left me-2 text-pink"></i>الوصف
                                </label>
                                <textarea class="form-control" id="description" name="description" rows="4" style="resize: none;"></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="price" class="form-label fw-bold">
                                        <i class="fas fa-tag me-2 text-pink"></i>السعر
                                    </label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                                        <span class="input-group-text">$</span>
                                        <div class="invalid-feedback">يرجى إدخال سعر المنتج</div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-4">
                                    <label for="old_price" class="form-label fw-bold">
                                        <i class="fas fa-tags me-2 text-pink"></i>السعر القديم (اختياري)
                                    </label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" class="form-control" id="old_price" name="old_price">
                                        <span class="input-group-text">$</span>
                                    </div>
                                </div>
                                </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold d-block">
                                    <i class="fas fa-image me-2 text-pink"></i>صورة المنتج
                                </label>
                                <div class="file-upload">
                                    <div class="file-upload-icon">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                    </div>
                                    <h5 class="text-muted">اسحب وأسقط الصورة هنا أو انقر للاختيار</h5>
                                    <p class="small text-muted">(JPEG, PNG, GIF بأي حجم)</p>
                                    <input type="file" id="image" name="image" accept="image/*" required>
                                </div>
                                <div class="invalid-feedback d-block">يرجى اختيار صورة للمنتج</div>
                            </div>
                            
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="fas fa-plus-circle me-2"></i>إضافة المنتج
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function () {
            'use strict'
            
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation')
            
            // Loop over them and prevent submission
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
        
        // Show file name when selected
        document.getElementById('image').addEventListener('change', function(e) {
            if (this.files.length > 0) {
                const fileName = this.files[0].name;
                const uploadText = document.querySelector('.file-upload h5');
                uploadText.textContent = تم اختيار: ${fileName};
                uploadText.classList.remove('text-muted');
                uploadText.classList.add('text-success');
            }
        });
    </script>
</body>
</html>