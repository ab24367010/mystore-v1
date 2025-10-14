<nav class="navbar" role="navigation" aria-label="Main navigation">
    <div class="container">
        <a href="<?php echo SITE_URL; ?>" class="logo" aria-label="Home">
            <img src="<?php echo SITE_URL; ?>/images/logo.webp" alt="<?php echo SITE_NAME; ?> Logo" class="logo-img">
            <?php echo SITE_NAME; ?>
        </a>

        <ul class="nav-menu" role="menubar">
            <li role="none"><a href="<?php echo SITE_URL; ?>" role="menuitem">Нүүр</a></li>
            <li role="none"><a href="<?php echo SITE_URL; ?>/templates.php" role="menuitem">Template-үүд</a></li>

            <?php if(isLoggedIn()): ?>
                <li role="none"><a href="<?php echo SITE_URL; ?>/user/dashboard.php" role="menuitem" aria-label="User dashboard">Миний хуудас</a></li>
                <li role="none"><a href="<?php echo SITE_URL; ?>/logout.php" role="menuitem" aria-label="Logout">Гарах</a></li>
            <?php else: ?>
                <li role="none"><a href="<?php echo SITE_URL; ?>/login.php" role="menuitem" aria-label="Login">Нэвтрэх</a></li>
                <li role="none"><a href="<?php echo SITE_URL; ?>/register.php" role="menuitem" aria-label="Register">Бүртгүүлэх</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>