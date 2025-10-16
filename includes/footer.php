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

<!-- Cookie Consent Banner - Compact Bottom Right -->
<div id="cookie-consent-banner">
    <button class="cookie-consent-close" onclick="document.getElementById('cookie-consent-banner').classList.remove('show')">&times;</button>
    <div class="cookie-consent-container">
        <div class="cookie-consent-header">
            <span class="cookie-icon">🍪</span>
            <h3>Cookie зөвшөөрөл</h3>
        </div>
        <div class="cookie-consent-content">
            <p>Бид таны туршлагыг сайжруулах зорилгоор cookie ашигладаг. <a href="<?php echo htmlspecialchars(SITE_URL); ?>/privacy-policy.php">Дэлгэрэнгүй</a></p>
        </div>
        <div class="cookie-consent-buttons">
            <button id="cookie-accept-all" class="cookie-consent-btn btn-accept-all">✓ Бүгдийг зөвшөөрөх</button>
            <div class="cookie-consent-secondary">
                <button id="cookie-reject-all" class="cookie-consent-btn btn-reject-all">Татгалзах</button>
                <button id="cookie-customize" class="cookie-consent-btn btn-customize">Тохиргоо</button>
            </div>
        </div>
    </div>
</div>

<!-- Cookie Settings Modal -->
<div id="cookie-settings-modal">
    <div class="cookie-settings-content">
        <div class="cookie-settings-header">
            <h2>Cookie тохиргоо</h2>
            <button class="cookie-settings-close">&times;</button>
        </div>

        <div class="cookie-settings-body">
            <!-- Essential Cookies -->
            <div class="cookie-category essential">
                <div class="cookie-category-header">
                    <h3>
                        🔒 Зайлшгүй шаардлагатай
                        <span class="cookie-badge required">Шаардлагатай</span>
                    </h3>
                    <label class="cookie-toggle">
                        <input type="checkbox" id="cookie-essential" checked disabled>
                        <span class="cookie-toggle-slider"></span>
                    </label>
                </div>
                <p class="cookie-category-description">
                    Эдгээр cookie-ууд нь вэбсайтын үндсэн функцуудад зайлшгүй шаардлагатай бөгөөд идэвхгүй болгох боломжгүй. Үүнд нэвтрэх систем, аюулгүй байдал, хуудас дахь тохиргоо хамаарна.
                </p>
            </div>

            <!-- Functional Cookies -->
            <div class="cookie-category functional">
                <div class="cookie-category-header">
                    <h3>
                        ⚙️ Функциональ
                        <span class="cookie-badge optional">Сонголттой</span>
                    </h3>
                    <label class="cookie-toggle">
                        <input type="checkbox" id="cookie-functional">
                        <span class="cookie-toggle-slider"></span>
                    </label>
                </div>
                <p class="cookie-category-description">
                    Эдгээр cookie-ууд нь нэмэлт функц болон таны сонголтыг санах боломжийг олгодог. Үүнд Google Maps, хэл тохируулга, байршил хамаарна.
                </p>
            </div>

            <!-- Analytics Cookies -->
            <div class="cookie-category analytics">
                <div class="cookie-category-header">
                    <h3>
                        📊 Шинжилгээ
                        <span class="cookie-badge optional">Сонголттой</span>
                    </h3>
                    <label class="cookie-toggle">
                        <input type="checkbox" id="cookie-analytics">
                        <span class="cookie-toggle-slider"></span>
                    </label>
                </div>
                <p class="cookie-category-description">
                    Эдгээр cookie-ууд нь вэбсайтын ашиглалтын талаар мэдээлэл цуглуулж, манай үйлчилгээг сайжруулахад тусалдаг. Бүх мэдээлэл anonymous байх болно.
                </p>
            </div>

            <!-- Marketing Cookies -->
            <div class="cookie-category marketing">
                <div class="cookie-category-header">
                    <h3>
                        📢 Маркетинг
                        <span class="cookie-badge optional">Сонголттой</span>
                    </h3>
                    <label class="cookie-toggle">
                        <input type="checkbox" id="cookie-marketing">
                        <span class="cookie-toggle-slider"></span>
                    </label>
                </div>
                <p class="cookie-category-description">
                    Эдгээр cookie-ууд нь танд хамааралтай зар сурталчилгаа харуулахад ашиглагдана. Тэдгээр нь таны вэбсайт дахь үйл ажиллагааг хянах боломжтой.
                </p>
            </div>
        </div>

        <div class="cookie-settings-footer">
            <button id="cookie-save-preferences" class="cookie-consent-btn btn-save-preferences">Хадгалах</button>
        </div>
    </div>
</div>

<script>
    // Site configuration for JavaScript
    const SITE_CONFIG = {
        siteUrl: '<?php echo htmlspecialchars(SITE_URL); ?>',
        siteName: '<?php echo htmlspecialchars(SITE_NAME); ?>'
    };
</script>
<script src="<?php echo htmlspecialchars(SITE_URL); ?>/js/main.js"></script>
<script src="<?php echo htmlspecialchars(SITE_URL); ?>/js/cookie-consent.js"></script>
</body>
</html>