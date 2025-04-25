<?php
include('../config/database.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
    if ($conn->query($sql) === TRUE) {
        echo "تم التسجيل بنجاح!";
    } else {
        echo "خطأ: " . $conn->error;
    }
}
?>

<form method="POST">
    <input type="text" name="name" placeholder="الاسم" required><br>
    <input type="email" name="email" placeholder="البريد الإلكتروني" required><br>
    <input type="password" name="password" placeholder="كلمة المرور" required><br>
    <button type="submit">تسجيل</button>
</form>

