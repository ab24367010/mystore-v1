<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Хэрэв аль хэдийн нэвтэрсэн бол dashboard руу шилжүүлэх
if(isLoggedIn()) {
    redirect('user/dashboard.php');
}

$error = '';
$success = '';

// Форм submit хийхэд
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = clean($_POST['email']);

    // Validation
    if(empty($email)) {
        $error = "Имэйл хаягаа оруулна уу";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Имэйл хаяг буруу байна";
    } else {
        // Database-аас хэрэглэгч хайх
        $sql = "SELECT id, name, email FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Хуучин идэвхтэй code-уудыг устгах (энэ хэрэглэгчийн)
            $sql = "DELETE FROM password_reset_codes WHERE user_id = ? AND verified = 0";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user['id']);
            $stmt->execute();

            // 6 оронтой санамсаргүй код үүсгэх
            $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            // Code дуусах хугацаа (15 минутын дараа)
            $expires_at = date('Y-m-d H:i:s', strtotime('+15 minutes'));

            // Code database-д хадгалах
            $sql = "INSERT INTO password_reset_codes (user_id, code, expires_at) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iss", $user['id'], $code, $expires_at);

            if($stmt->execute()) {
                // Email илгээх
                $content = "
                <p>Сайн байна уу <strong>" . htmlspecialchars($user['name']) . "</strong>,</p>

                <p>Та нууц үгээ сэргээх хүсэлт илгээсэн байна.</p>

                <div class='success-box'>
                    <h3>🔐 Баталгаажуулах код</h3>
                    <p style='text-align: center;'>
                        <span style='font-size: 36px; font-weight: bold; letter-spacing: 8px; color: #2563eb; font-family: monospace;'>
                            $code
                        </span>
                    </p>
                    <p style='text-align: center; font-size: 14px; color: #666; margin-top: 10px;'>
                        Энэ кодыг баталгаажуулах хуудсанд оруулна уу
                    </p>
                </div>

                <div class='warning-box'>
                    <p><strong>⚠️ Анхаар:</strong></p>
                    <ul style='margin: 10px 0; padding-left: 20px;'>
                        <li>Энэ код <strong>15 минутын</strong> дараа хүчингүй болно</li>
                        <li>Код нэг удаа ашиглагдсаны дараа дахин ашиглах боломжгүй</li>
                        <li>Хэрэв та энэ хүсэлтийг илгээгээгүй бол энэ email-г үл тоомсорлоорой</li>
                        <li>Энэ кодыг хэнд ч битгий өгөөрэй</li>
                    </ul>
                </div>

                <p>Баярлалаа!<br><strong>" . SITE_NAME . " баг</strong></p>
                ";

                $subject = "Нууц үг сэргээх код - " . SITE_NAME;
                $htmlMessage = getEmailTemplate("Баталгаажуулах код", $content);

                if(sendEmail($email, $subject, $htmlMessage)) {
                    // Session-д email хадгалах (дараагийн хуудсанд ашиглах)
                    $_SESSION['reset_email'] = $email;
                    $_SESSION['reset_user_id'] = $user['id'];

                    // Verify code хуудас руу шилжүүлэх
                    redirect('verify-code.php');
                } else {
                    $error = "Email илгээхэд алдаа гарлаа. Дахин оролдоно уу.";
                }
            } else {
                $error = "Алдаа гарлаа. Дахин оролдоно уу.";
            }
        } else {
            // Security: Хэрэглэгч олдоогүй ч ижил мессеж харуулна (email harvesting-аас сэргийлэх)
            $_SESSION['reset_email'] = $email;
            redirect('verify-code.php?unknown=1');
        }
    }
}

$page_title = "Нууц үг мартсан";
include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container" style="max-width: 500px; margin-top: 50px; margin-bottom: 50px;">
    <div style="background: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div style="text-align: center; margin-bottom: 20px;">
            <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center;">
                <span style="font-size: 40px;">🔒</span>
            </div>
            <h2 style="margin: 0;">Нууц үг мартсан</h2>
            <p style="color: #6b7280; margin-top: 10px; font-size: 14px;">
                Бүртгэлтэй имэйл хаягаа оруулна уу
            </p>
        </div>

        <?php if($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Имэйл хаяг</label>
                <input type="email" name="email" placeholder="your@email.com" required
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                       style="font-size: 16px;">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; font-size: 16px; padding: 12px;">
                Баталгаажуулах код илгээх
            </button>
        </form>

        <p style="text-align: center; margin-top: 20px; color: #6b7280;">
            <a href="login.php" style="color: #2563eb; text-decoration: none;">
                ← Нэвтрэх хуудас руу буцах
            </a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
