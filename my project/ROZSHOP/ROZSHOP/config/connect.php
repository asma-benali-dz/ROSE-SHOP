<?php

$servername = "localhost";      // غالبًا يكون localhost في بيئة XAMPP
$username   = "root";           // اسم المستخدم الافتراضي
$password   = "";               // كلمة المرور (غالبًا فارغة)
$dbname     = "ecommerce_bd";      // اسم قاعدة البيانات (غيّره إذا مختلف)

$db= new mysqli($servername, $username, $password, $dbname);
if ($db->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $db->connect_error);
}
 echo "تم الاتصال بقاعدة البيانات بنجاح!";
?>



  // دالة لتحميل الصور
 function uploadImage($file) {
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }
    
    $imageFileType = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $target_file = $target_dir . uniqid() . '.' . $imageFileType;
    
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $target_file;
    }
    return false;
 }