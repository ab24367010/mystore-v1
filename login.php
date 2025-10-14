<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Хэрэв аль хэдийн нэвтэрсэн бол dashboard руу шилжүүлэх
if(isLoggedIn()) {
    redirect('user/dashboard.php');
}

// Redirect параметр авах (хаашаа буцах гэж байсныг)
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'user/dashboard.php';

$error = '';

// Форм submit хийхэд
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // CSRF validation
    if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
        $error = "Invalid request. Please try again.";
        logError('CSRF validation failed', ['ip' => $_SERVER['REMOTE_ADDR'], 'action' => 'login']);
    }
    // Rate limiting
    elseif (!checkRateLimit('login', 5, 900)) {
        $error = "Хэт олон удаа оролдлоо. 15 минутын дараа дахин оролдоно уу.";
        logError('Rate limit exceeded', ['ip' => $_SERVER['REMOTE_ADDR'], 'action' => 'login']);
    } else {
        $email = clean($_POST['email']);
        $password = $_POST['password'];

        // Validation
        if(empty($email) || empty($password)) {
            $error = "Бүх талбарыг бөглөнө үү";
        } else {
        // Database-аас хэрэглэгч хайх
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Нууц үг шалгах
            if(password_verify($password, $user['password'])) {
                // Амжилттай нэвтэрсэн
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                
                setAlert("Амжилттай нэвтэрлээ! Тавтай морил, " . $user['name'], 'success');
                
                // Буцаж явах хуудас руу redirect
                redirect($redirect);
            } else {
                $error = "Нууц үг буруу байна";
                logError('Login failed - wrong password', ['email' => $email]);
            }
        } else {
            $error = "Имэйл хаяг олдсонгүй";
            logError('Login failed - email not found', ['email' => $email]);
        }
        }
    }
}

$page_title = "Нэвтрэх";
include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container" style="max-width: 500px; margin-top: 50px; margin-bottom: 50px;">
    <div style="background: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <h2 style="text-align: center; margin-bottom: 30px;">Нэвтрэх</h2>
        
        <?php if($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <?php echo getCSRFField(); ?>

            <div class="form-group">
                <label>Имэйл хаяг</label>
                <input type="email" name="email" placeholder="your@email.com" required
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label>Нууц үг</label>
                <input type="password" name="password" placeholder="••••••••" required>
                <div style="text-align: right; margin-top: 5px;">
                    <a href="forgot-password.php" style="color: #2563eb; text-decoration: none; font-size: 14px;">
                        Нууц үг мартсан уу?
                    </a>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">
                Нэвтрэх
            </button>
        </form>
        
        <p style="text-align: center; margin-top: 20px; color: #6b7280;">
            Бүртгэл байхгүй юу? 
            <a href="register.php?redirect=<?php echo urlencode($redirect); ?>" style="color: #2563eb; text-decoration: none;">
                Бүртгүүлэх
            </a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>