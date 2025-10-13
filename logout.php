<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Session устгах
unset($_SESSION['user_id']);
unset($_SESSION['user_name']);
unset($_SESSION['user_email']);

// Session бүрэн устгах
session_destroy();

// Мессеж харуулахын тулд шинэ session эхлүүлэх
session_start();
setAlert("Амжилттай гарлаа. Дахин уулзацгаая!", 'success');

// Нүүр хуудас руу буцаах
redirect('index.php');
?>