<?php
// Нэвтрээгүй бол login руу шилжүүлэх
if(!isAdmin()) {
    redirect('login.php');
}
?>
<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Админ Панел</title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/css/style.css">
    <style>
        /* Админ navbar загвар */
        .admin-navbar {
            background: #1f2937;
            padding: 15px 0;
            margin-bottom: 30px;
        }
        .admin-navbar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .admin-navbar .logo {
            color: white;
            font-size: 20px;
            font-weight: bold;
            text-decoration: none;
        }
        .admin-navbar .nav-menu {
            display: flex;
            list-style: none;
            gap: 15px;
            margin: 0;
            padding: 0;
        }
        .admin-navbar .nav-menu a {
            color: #d1d5db;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
            transition: all 0.3s;
        }
        .admin-navbar .nav-menu a:hover,
        .admin-navbar .nav-menu a.active {
            background: #374151;
            color: white;
        }
    </style>
</head>
<body style="background: #f3f4f6;">

<nav class="admin-navbar">
    <div class="container">
        <a href="dashboard.php" class="logo">
            🔐 Админ Панел
        </a>
        
        <ul class="nav-menu">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="orders.php">Захиалгууд</a></li>
            <li><a href="templates.php">Template-үүд</a></li>
            <li><a href="users.php">Хэрэглэгчид</a></li>
            <li><a href="<?php echo SITE_URL; ?>" target="_blank">Сайт үзэх</a></li>
            <li><a href="logout.php" style="color: #ef4444;">Гарах</a></li>
        </ul>
    </div>
</nav>