<?php
// Database тохиргоо
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'MNAng3l_112');
define('DB_NAME', 'mystore');

// Database холболт
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Холболт шалгах
if (!$conn) {
    die("Database холболт амжилтгүй: " . mysqli_connect_error());
}

// UTF-8 тохиргоо
mysqli_set_charset($conn, "utf8mb4");

// Сайтын тохиргоо
define('SITE_NAME', 'Template Store');
define('SITE_URL', 'http://localhost/mystore-v1');
define('ADMIN_EMAIL', 'admin@yoursite.com');

// Session эхлүүлэх
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>