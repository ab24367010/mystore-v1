<?php
require_once '../includes/config.php';

// Admin session устгах
unset($_SESSION['admin_id']);
unset($_SESSION['admin_username']);

// Session бүрэн устгах
session_destroy();

// Browser cache устгах
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

// Шинэ session эхлүүлэх
session_start();
session_regenerate_id(true);

// Нэвтрэх хуудас руу буцаах
header("Location: login.php");
exit();
?>