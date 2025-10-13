<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

echo "<h1>Тест хуудас</h1>";

// Database холболт
if($conn) {
    echo "<p style='color: green;'>✅ Database холболт амжилттай!</p>";
} else {
    echo "<p style='color: red;'>❌ Database холболт амжилтгүй!</p>";
}

// Template тоо
$sql = "SELECT COUNT(*) as count FROM templates";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

echo "<p>Template тоо: <strong>" . $row['count'] . "</strong></p>";

// Admin тоо
$sql = "SELECT COUNT(*) as count FROM admins";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

echo "<p>Admin тоо: <strong>" . $row['count'] . "</strong></p>";

echo "<hr>";
echo "<p>Хэрэв бүгд ажиллаж байвал бэлэн боллоо! 🎉</p>";
?>