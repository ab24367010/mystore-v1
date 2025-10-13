<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Session устгах
unset($_SESSION['user_id']);
unset($_SESSION['user_name']);
unset($_SESSION['user_email']);

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

setAlert("Амжилттай гарлаа. Дахин уулзацгаая!", 'success');

// Redirect
redirect('index.php');
?>