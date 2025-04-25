<?php
session_start();
require_once '../config/database.php';

try {
    $session_id = session_id();
    $sql = "DELETE FROM cart_items WHERE session_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $session_id);
    $stmt->execute();
} catch (Exception $e) {
    // Log error and show user-friendly message
    error_log("Purchase error: " . $e->getMessage());
    die("حدث خطأ أثناء معالجة عملية الشراء. يرجى المحاولة لاحقًا.");
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تم الشراء</title>
    <!-- إعادة توجيه تلقائي بعد 5 ثواني -->
    <meta http-equiv="refresh" content="5;url=../index.php">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-light">
    <div class="container py-5 text-center">
        <div class="card shadow p-5">
            <h1 class="text-success mb-4">✅ تم إتمام عملية الشراء بنجاح!</h1>
            <p class="lead">شكراً لتسوقك معنا في ROSE SHOP 🌹</p>
            <p>سيتم شحن طلبك خلال 2-3 أيام عمل</p>
            <a href="../index.php" class="btn btn-primary mt-3 px-4 py-2">العودة إلى المتجر الآن</a>
            <p class="text-muted mt-2" style="font-size: 14px;">سيتم تحويلك تلقائيًا بعد 5 ثوانٍ...</p>
        </div>
    </div>
</body>
</html>
