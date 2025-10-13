<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="<?php echo htmlspecialchars(SITE_URL); ?>/images/favicon.ico">
    <link rel="stylesheet" href="<?php echo htmlspecialchars(SITE_URL); ?>/css/style.css">
    <link rel="stylesheet" href="<?php echo htmlspecialchars(SITE_URL); ?>/css/responsive.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <title><?php echo htmlspecialchars($page_title); ?></title>
</head>
<footer class="footer">
    <div class="footer-container">
        <div class="footer-section about">
            <h3><?php echo htmlspecialchars(SITE_NAME); ?></h3>
            <p>Бид чанартай, мэргэжлийн веб template болон шийдлүүдийг санал болгож байна.
            Таны бизнесийг дараагийн түвшинд хүргэнэ.</p>
        </div>

        <div class="footer-section links">
            <h4>Холбоосууд</h4>
            <ul>
                <li><a href="<?php echo htmlspecialchars(SITE_URL); ?>/index.php">Нүүр</a></li>
                <li><a href="<?php echo htmlspecialchars(SITE_URL); ?>/templates.php">Templates</a></li>
                <li><a href="<?php echo htmlspecialchars(SITE_URL); ?>/about.php">About</a></li>
                <li><a href="<?php echo htmlspecialchars(SITE_URL); ?>/contact.php">Contact</a></li>
                <li><a href="<?php echo htmlspecialchars(SITE_URL); ?>/faq.php">FAQ</a></li>
                <li><a href="<?php echo htmlspecialchars(SITE_URL); ?>/privacy-policy.php">Privacy Policy</a></li>
            </ul>
        </div>

        <div class="footer-section contact">
            <h4>Холбоо барих</h4>
            <p><i class="fas fa-envelope"></i> info@<?php echo strtolower(htmlspecialchars(SITE_NAME)); ?>.com</p>
            <p><i class="fas fa-phone"></i> +81 80 9053 6482</p>
            <p><i class="fas fa-map-marker-alt"></i> Tokyo, Japan</p>
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; 2025 <?php echo htmlspecialchars(SITE_NAME); ?>. Бүх эрх хуулиар хамгаалагдсан.</p>
    </div>
</footer>

<script src="<?php echo htmlspecialchars(SITE_URL); ?>/js/main.js"></script>
</body>
</html>