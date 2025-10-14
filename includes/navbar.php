<nav class="navbar">
    <div class="container">
        <a href="<?php echo SITE_URL; ?>" class="logo">
            <img src="images/logo.png" alt="Logo" class="logo-img">
            <?php echo SITE_NAME; ?>
        </a>
        
        <ul class="nav-menu">
            <li><a href="<?php echo SITE_URL; ?>">Нүүр</a></li>
            <li><a href="<?php echo SITE_URL; ?>/templates.php">Template-үүд</a></li>
            
            <?php if(isLoggedIn()): ?>
                <li><a href="<?php echo SITE_URL; ?>/user/dashboard.php">Миний хуудас</a></li>
                <li><a href="<?php echo SITE_URL; ?>/logout.php">Гарах</a></li>
            <?php else: ?>
                <li><a href="<?php echo SITE_URL; ?>/login.php">Нэвтрэх</a></li>
                <li><a href="<?php echo SITE_URL; ?>/register.php">Бүртгүүлэх</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>