<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Хэрэв аль хэдийн нэвтэрсэн бол dashboard руу шилжүүлэх
if (isLoggedIn()) {
    redirect('user/dashboard.php');
}

// Redirect параметр авах
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'user/dashboard.php';

$error = '';
$success = '';

// Форм submit хийхэд
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // CSRF validation
    if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
        $error = "Invalid request. Please try again.";
        logError('CSRF validation failed', ['ip' => $_SERVER['REMOTE_ADDR'], 'action' => 'register']);
    }
    // Rate limiting
    elseif (!checkRateLimit('register', REGISTER_MAX_ATTEMPTS, REGISTER_TIME_WINDOW)) {
        $error = "Хэт олон удаа бүртгэл үүсгэх оролдлоо хийлээ. " . (REGISTER_TIME_WINDOW / 3600) . " цагийн дараа дахин оролдоно уу.";
        logError('Rate limit exceeded', ['ip' => $_SERVER['REMOTE_ADDR'], 'action' => 'register']);
    } else {
        $name = clean($_POST['name']);
        $email = clean($_POST['email']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Validation
        if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
            $error = "Бүх талбарыг бөглөнө үү";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Имэйл хаяг буруу байна";
    } elseif (strlen($password) < MIN_PASSWORD_LENGTH) {
        $error = "Нууц үг багадаа " . MIN_PASSWORD_LENGTH . " тэмдэгт байх ёстой";
    } elseif ($password !== $confirm_password) {
        $error = "Нууц үг таарахгүй байна";
    } else {
        // Имэйл давхцаж байгаа эсэхийг шалгах
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Энэ имэйл хаяг аль хэдийн бүртгэлтэй байна";
        } else {
            // Нууц үг hash хийх
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // Шинэ хэрэглэгч үүсгэх
            $sql = "INSERT INTO users (name, email, password, created_at) VALUES (?, ?, ?, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $name, $email, $password_hash);

            if ($stmt->execute()) {
                // Автоматаар нэвтрүүлэх
                $user_id = $stmt->insert_id;
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;

                // ========================================
                // БАТАЛГААЖУУЛАХ ИМЭЙЛ ИЛГЭЭХ
                // ========================================
                $content = "
        <p>Сайн байна уу <strong>" . htmlspecialchars($name) . "</strong>,</p>
        
        <p>Тавтай морилно уу! " . SITE_NAME . " дээр амжилттай бүртгүүллээ! 🎉</p>
        
        <div class='success-box'>
            <h3>✅ Бүртгэл амжилттай</h3>
            <p><strong>Нэр:</strong> " . htmlspecialchars($name) . "</p>
            <p><strong>Имэйл:</strong> " . htmlspecialchars($email) . "</p>
        </div>
        
        <div class='info-box'>
            <h3>🚀 Одоо та эдгээр боломжтой боллоо:</h3>
            <ul style='margin: 10px 0; padding-left: 20px;'>
                <li>Мэргэжлийн template-үүд авах</li>
                <li>Татах линк хадгалах</li>
                <li>Захиалгын түүх харах</li>
                <li>24/7 дэмжлэг авах</li>
            </ul>
        </div>
        
        <a href='" . SITE_URL . "/templates.php' class='button'>Template-үүд үзэх</a>
        
        <p>Асуулт байвал <a href='mailto:" . ADMIN_EMAIL . "'>" . ADMIN_EMAIL . "</a> хаяг руу холбогдоно уу.</p>
        
        <p>Баярлалаа!<br><strong>" . SITE_NAME . " баг</strong></p>
    ";

                $subject = "Тавтай морилно уу - " . SITE_NAME;
                $htmlMessage = getEmailTemplate("Тавтай морилно уу", $content);
                sendEmail($email, $subject, $htmlMessage);

                setAlert("Амжилттай бүртгэгдлээ! Тавтай морил, " . $name, 'success');
                redirect($redirect);
            }
        }
    }
    }
}

$page_title = "Бүртгүүлэх";
include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container" style="max-width: 500px; margin-top: 50px; margin-bottom: 50px;">
    <div style="background: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <h2 style="text-align: center; margin-bottom: 30px;">Бүртгүүлэх</h2>

        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="" data-loading="Бүртгэл үүсгэж байна..." data-loading-overlay>
            <?php echo getCSRFField(); ?>

            <div class="form-group">
                <label>Нэр</label>
                <input type="text" name="name" placeholder="Таны нэр" required
                    value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
            </div>

            <div class="form-group">
                <label>Имэйл хаяг</label>
                <input type="email" name="email" placeholder="your@email.com" required
                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>

            <div class="form-group">
                <label>Нууц үг (багадаа <?php echo MIN_PASSWORD_LENGTH; ?> тэмдэгт)</label>
                <input type="password" name="password" placeholder="••••••••" required minlength="<?php echo MIN_PASSWORD_LENGTH; ?>">
            </div>

            <div class="form-group">
                <label>Нууц үг баталгаажуулах</label>
                <input type="password" name="confirm_password" placeholder="••••••••" required minlength="<?php echo MIN_PASSWORD_LENGTH; ?>">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">
                Бүртгүүлэх
            </button>
        </form>

        <p style="text-align: center; margin-top: 20px; color: #6b7280;">
            Аль хэдийн бүртгэлтэй юу?
            <a href="login.php?redirect=<?php echo urlencode($redirect); ?>" style="color: #2563eb; text-decoration: none;">
                Нэвтрэх
            </a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>