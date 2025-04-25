<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'ecommerce_db';

$db = new mysqli($host, $user, $password, $database);

if ($db->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $db->connect_error);
}
?>
