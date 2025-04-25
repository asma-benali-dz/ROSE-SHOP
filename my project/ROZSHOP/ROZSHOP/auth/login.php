<?php
include('../config/database.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        echo "تم تسجيل الدخول!";
    } else {
        echo "البريد الإلكتروني أو كلمة المرور غير صحيحة.";
    }
}
?>

<form method="POST">
    <input type="email" name="email" placeholder="البريد الإلكتروني" required><br>
    <input type="password" name="password" placeholder="كلمة المرور" required><br>
    <button type="submit">دخول</button>
</form>
