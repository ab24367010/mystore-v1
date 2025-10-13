<?php
require_once '../includes/config.php';

// Admin session устгах
unset($_SESSION['admin_id']);
unset($_SESSION['admin_username']);

// Нэвтрэх хуудас руу буцаах
header("Location: login.php");
exit();
?>