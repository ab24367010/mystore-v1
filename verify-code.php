<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if(isLoggedIn()) {
    redirect('user/dashboard.php');
}

if(!isset($_SESSION['reset_email'])) {
    redirect('forgot-password.php');
}

$email = $_SESSION['reset_email'];
$error = '';
$resend_message = '';
$is_unknown = isset($_GET['unknown']) && $_GET['unknown'] == '1';

// Код дахин илгээх
if(isset($_GET['resend']) && $_GET['resend'] == '1' && !$is_unknown) {
    $sql = "SELECT id, name FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        $sql = "DELETE FROM password_reset_codes WHERE user_id = ? AND verified = 0";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user['id']);
        $stmt->execute();

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expires_at = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        $sql = "INSERT INTO password_reset_codes (user_id, code, expires_at) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $user['id'], $code, $expires_at);
        $stmt->execute();

        $content = "
        <p>Сайн байна уу <strong>" . htmlspecialchars($user['name']) . "</strong>,</p>
        <p>Таны хүсэлтээр шинэ баталгаажуулах код илгээж байна.</p>
        <div class='success-box'>
            <h3>🔐 Шинэ баталгаажуулах код</h3>
            <p style='text-align: center;'>
                <span style='font-size: 36px; font-weight: bold; letter-spacing: 8px; color: #2563eb; font-family: monospace;'>
                    $code
                </span>
            </p>
        </div>
        <div class='warning-box'>
            <p>Энэ код <strong>15 минутын</strong> дараа хүчингүй болно.</p>
        </div>
        <p>Баярлалаа!<br><strong>" . SITE_NAME . " баг</strong></p>
        ";

        $subject = "Шинэ баталгаажуулах код - " . SITE_NAME;
        $htmlMessage = getEmailTemplate("Шинэ код", $content);
        sendEmail($email, $subject, $htmlMessage);

        $resend_message = "Шинэ код таны имэйл хаяг руу илгээгдлээ!";
    }
}

// Код баталгаажуулах - ЗАСВАРЛАСАН
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Кодыг цэвэрлэх - зөвхөн тоо авах
    $code = preg_replace('/[^0-9]/', '', trim($_POST['code']));

    if(empty($code)) {
        $error = "Кодоо оруулна уу";
    } elseif(strlen($code) != 6) {
        $error = "Код 6 оронтой байх ёстой (одоо: " . strlen($code) . " тэмдэгт)";
    } elseif($is_unknown) {
        $error = "Имэйл хаяг олдсонгүй. Дахин оролдоно уу.";
    } else {
        // Хэрэглэгч хайх
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $user_id = $user['id'];

            // Код шалгах
            $sql = "SELECT * FROM password_reset_codes
                    WHERE user_id = ? 
                    AND code = ? 
                    AND verified = 0 
                    AND expires_at > NOW()
                    ORDER BY created_at DESC 
                    LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $user_id, $code);
            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows > 0) {
                $code_data = $result->fetch_assoc();
                $attempts = $code_data['attempts'] + 1;

                if($attempts > 5) {
                    $sql = "DELETE FROM password_reset_codes WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $code_data['id']);
                    $stmt->execute();

                    $error = "Хэт олон удаа буруу код оруулсан. Шинэ код авна уу.";
                    unset($_SESSION['reset_email']);
                    unset($_SESSION['reset_user_id']);
                } else {
                    // Код баталгаажсан ✅
                    $sql = "UPDATE password_reset_codes SET verified = 1, attempts = ? WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ii", $attempts, $code_data['id']);
                    $stmt->execute();

                    $_SESSION['verified_code_id'] = $code_data['id'];
                    redirect('reset-password.php');
                }
            } else {
                // DEBUG: Яагаад олдоогүй байгааг олох
                $debug_sql = "SELECT code, verified, expires_at, 
                             CASE 
                                WHEN expires_at < NOW() THEN 'Хугацаа дууссан'
                                WHEN verified = 1 THEN 'Аль хэдийн баталгаажсан'
                                ELSE 'Код таарахгүй'
                             END as reason
                             FROM password_reset_codes 
                             WHERE user_id = ? 
                             ORDER BY created_at DESC LIMIT 1";
                $debug_stmt = $conn->prepare($debug_sql);
                $debug_stmt->bind_param("i", $user_id);
                $debug_stmt->execute();
                $debug_result = $debug_stmt->get_result();
                
                if($debug_result->num_rows > 0) {
                    $debug = $debug_result->fetch_assoc();
                    $error = "Код буруу байна. Шалтгаан: " . $debug['reason'] . 
                            " (Оруулсан: " . $code . ", Database: " . $debug['code'] . ")";
                } else {
                    $error = "Код олдсонгүй. Шинэ код авна уу.";
                }
                
                // Оролдлого нэмэгдүүлэх
                $sql = "UPDATE password_reset_codes
                        SET attempts = attempts + 1
                        WHERE user_id = ? AND verified = 0 AND expires_at > NOW()
                        ORDER BY created_at DESC LIMIT 1";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
            }
        } else {
            $error = "Имэйл хаяг олдсонгүй";
        }
    }
}

$page_title = "Код баталгаажуулах";
include 'includes/header.php';
include 'includes/navbar.php';
?>

<!-- HTML хэсэг өмнөх хэвээрээ -->
<div class="container" style="max-width: 500px; margin-top: 50px; margin-bottom: 50px;">
    <div style="background: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div style="text-align: center; margin-bottom: 20px;">
            <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center;">
                <span style="font-size: 40px;">✉️</span>
            </div>
            <h2 style="margin: 0;">Код баталгаажуулах</h2>
            <p style="color: #6b7280; margin-top: 10px; font-size: 14px;">
                <strong><?php echo htmlspecialchars($email); ?></strong> хаяг руу<br>
                6 оронтой код илгээлээ
            </p>
        </div>

        <?php if($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if($resend_message): ?>
            <div class="alert alert-success" style="background: #d1fae5; color: #065f46; border: 1px solid #10b981;">
                <?php echo $resend_message; ?>
            </div>
        <?php endif; ?>

        <?php if(!$is_unknown): ?>
            <form method="POST" action="" data-loading="Код баталгаажуулж байна..." data-loading-overlay>
                <div class="form-group">
                    <label>Баталгаажуулах код</label>
                    <input type="text" name="code" placeholder="000000" required maxlength="6" pattern="[0-9]{6}"
                           style="font-size: 24px; text-align: center; letter-spacing: 8px; font-family: monospace; font-weight: bold;"
                           autocomplete="off" autofocus>
                    <small style="color: #6b7280; display: block; margin-top: 5px;">
                        Email-с ирсэн 6 оронтой кодыг оруулна уу
                    </small>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; font-size: 16px; padding: 12px;">
                    Баталгаажуулах
                </button>
            </form>

            <div style="text-align: center; margin-top: 20px;">
                <p style="color: #6b7280; font-size: 14px;">Email ирээгүй юу?</p>
                <a href="?resend=1" style="color: #2563eb; text-decoration: none; font-weight: 500;">
                    Дахин илгээх
                </a>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 20px;">
                <p style="color: #6b7280;">Имэйл хаяг олдсонгүй.</p>
                <a href="forgot-password.php" class="btn btn-primary" style="margin-top: 20px;">
                    Дахин оролдох
                </a>
            </div>
        <?php endif; ?>

        <p style="text-align: center; margin-top: 20px; color: #6b7280; font-size: 14px;">
            <a href="forgot-password.php" style="color: #2563eb; text-decoration: none;">
                ← Буцах
            </a>
        </p>

        <div style="background: #f9fafb; padding: 15px; border-radius: 5px; margin-top: 20px;">
            <p style="margin: 0; font-size: 12px; color: #6b7280; text-align: center;">
                🔒 Код 15 минутын дараа хүчингүй болно<br>
                Код хүлээн авсан эсвэл spam хавтсыг шалгана уу
            </p>
        </div>
    </div>
</div>

<script>
// Зөвхөн тоо оруулах боломжтой
document.querySelector('input[name="code"]').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '');
});
</script>

<?php include 'includes/footer.php'; ?>