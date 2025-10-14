<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Хэрэв аль хэдийн нэвтэрсэн бол dashboard руу шилжүүлэх
if(isLoggedIn()) {
    redirect('user/dashboard.php');
}

// Verified code байгаа эсэхийг шалгах
if(!isset($_SESSION['verified_code_id']) || !isset($_SESSION['reset_email'])) {
    redirect('forgot-password.php');
}

$error = '';
$success = '';
$code_id = $_SESSION['verified_code_id'];
$email = $_SESSION['reset_email'];

// Код баталгаажсан эсэхийг дахин шалгах
$sql = "SELECT prc.*, u.id as user_id, u.name, u.email
        FROM password_reset_codes prc
        JOIN users u ON prc.user_id = u.id
        WHERE prc.id = ? AND prc.verified = 1 AND prc.expires_at > NOW()";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $code_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0) {
    // Код хүчингүй
    unset($_SESSION['verified_code_id']);
    unset($_SESSION['reset_email']);
    unset($_SESSION['reset_user_id']);
    redirect('forgot-password.php');
}

$user_data = $result->fetch_assoc();

// Нууц үг шинэчлэх форм submit
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation
    if(empty($password) || empty($confirm_password)) {
        $error = "Бүх талбарыг бөглөнө үү";
    } elseif(strlen($password) < 6) {
        $error = "Нууц үг багадаа 6 тэмдэгт байх ёстой";
    } elseif($password !== $confirm_password) {
        $error = "Нууц үг таарахгүй байна";
    } else {
        $user_id = $user_data['user_id'];

        // Нууц үг hash хийх
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Нууц үг шинэчлэх
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $password_hash, $user_id);

        if($stmt->execute()) {
            // Бүх код устгах (энэ хэрэглэгчийн)
            $sql = "DELETE FROM password_reset_codes WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();

            // Session цэвэрлэх
            unset($_SESSION['verified_code_id']);
            unset($_SESSION['reset_email']);
            unset($_SESSION['reset_user_id']);

            // Хэрэглэгчид баталгаажуулах email илгээх
            $content = "
            <p>Сайн байна уу <strong>" . htmlspecialchars($user_data['name']) . "</strong>,</p>

            <div class='success-box'>
                <h3>✅ Нууц үг амжилттай солигдлоо</h3>
                <p>Таны нууц үг амжилттай шинэчлэгдлээ.</p>
            </div>

            <div class='info-box'>
                <p><strong>Хугацаа:</strong> " . date('Y-m-d H:i:s') . "</p>
                <p>Одоо шинэ нууц үгээ ашиглан нэвтэрч болно.</p>
            </div>

            <a href='" . SITE_URL . "/login.php' class='button'>Нэвтрэх</a>

            <div class='warning-box'>
                <p><strong>⚠️ Анхаар:</strong> Хэрэв та өөрөө нууц үгээ солиогүй бол нэн даруй бидэнтэй холбогдоно уу!</p>
            </div>

            <p>Баярлалаа!<br><strong>" . SITE_NAME . " баг</strong></p>
            ";

            $subject = "Нууц үг амжилттай солигдлоо - " . SITE_NAME;
            $htmlMessage = getEmailTemplate("Нууц үг солигдлоо", $content);
            sendEmail($user_data['email'], $subject, $htmlMessage);

            $success = true;
        } else {
            $error = "Алдаа гарлаа. Дахин оролдоно уу.";
        }
    }
}

$page_title = "Нууц үг шинэчлэх";
include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container" style="max-width: 500px; margin-top: 50px; margin-bottom: 50px;">
    <div style="background: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <?php if($success): ?>
            <div style="text-align: center;">
                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center;">
                    <span style="font-size: 40px;">✅</span>
                </div>
                <h2 style="margin: 0; color: #059669;">Амжилттай!</h2>
                <p style="color: #6b7280; margin-top: 10px;">
                    Нууц үг амжилттай солигдлоо
                </p>

                <div style="background: #d1fae5; border: 1px solid #10b981; padding: 20px; border-radius: 5px; margin: 20px 0;">
                    <p style="color: #065f46; margin: 0;">
                        Одоо шинэ нууц үгээрээ нэвтэрч болно
                    </p>
                </div>

                <a href="login.php" class="btn btn-primary" style="font-size: 16px; padding: 12px 30px;">
                    Нэвтрэх хуудас руу очих
                </a>
            </div>
        <?php else: ?>
            <div style="text-align: center; margin-bottom: 20px;">
                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center;">
                    <span style="font-size: 40px;">🔑</span>
                </div>
                <h2 style="margin: 0;">Шинэ нууц үг үүсгэх</h2>
                <p style="color: #6b7280; margin-top: 10px; font-size: 14px;">
                    Сайн байна уу, <strong><?php echo htmlspecialchars($user_data['name']); ?></strong><br>
                    Шинэ нууц үгээ оруулна уу
                </p>
            </div>

            <?php if($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label>Шинэ нууц үг (багадаа 6 тэмдэгт)</label>
                    <input type="password" name="password" placeholder="••••••••" required minlength="6"
                           style="font-size: 16px;">
                </div>

                <div class="form-group">
                    <label>Шинэ нууц үг баталгаажуулах</label>
                    <input type="password" name="confirm_password" placeholder="••••••••" required minlength="6"
                           style="font-size: 16px;">
                </div>

                <div style="background: #f9fafb; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                    <p style="margin: 0; font-size: 13px; color: #6b7280;">
                        <strong>Нууц үгийн шаардлага:</strong>
                    </p>
                    <ul style="margin: 10px 0 0 20px; font-size: 13px; color: #6b7280;">
                        <li>Багадаа 6 тэмдэгт</li>
                        <li>Амархан таах боломжгүй байх</li>
                        <li>Өмнөх нууц үгтэй ялгаатай байх</li>
                    </ul>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; font-size: 16px; padding: 12px;">
                    Нууц үг солих
                </button>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
