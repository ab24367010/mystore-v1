<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Нэвтэрсэн эсэхийг шалгах
if(!isLoggedIn()) {
    redirect('../login.php?redirect=user/profile.php');
}

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Хэрэглэгчийн мэдээлэл татах
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Форм submit хийхэд
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    if(isset($_POST['update_profile'])) {
        // Профайл шинэчлэх
        $name = clean($_POST['name']);
        $email = clean($_POST['email']);
        
        // Validation
        if(empty($name) || empty($email)) {
            $error = "Бүх талбарыг бөглөнө үү";
        } 
        elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Имэйл хаяг буруу байна";
        }
        else {
            // Имэйл өөрчлөгдсөн бол давхцаж байгаа эсэхийг шалгах
            if($email != $user['email']) {
                $check_sql = "SELECT id FROM users WHERE email = ? AND id != ?";
                $stmt = $conn->prepare($check_sql);
                $stmt->bind_param("si", $email, $user_id);
                $stmt->execute();
                
                if($stmt->get_result()->num_rows > 0) {
                    $error = "Энэ имэйл хаяг аль хэдийн бүртгэлтэй байна";
                }
            }
            
            if(empty($error)) {
                // Шинэчлэх
                $sql = "UPDATE users SET name = ?, email = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssi", $name, $email, $user_id);
                
                if($stmt->execute()) {
                    $_SESSION['user_name'] = $name;
                    $_SESSION['user_email'] = $email;
                    $success = "Мэдээлэл амжилттай шинэчлэгдлээ!";
                    
                    // Шинэчлэгдсэн мэдээлэл дахин татах
                    $user['name'] = $name;
                    $user['email'] = $email;
                } else {
                    $error = "Алдаа гарлаа. Дахин оролдоно уу.";
                }
            }
        }
    }
    
    elseif(isset($_POST['change_password'])) {
        // Нууц үг солих
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        // Validation
        if(empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $error = "Бүх талбарыг бөглөнө үү";
        }
        elseif(!password_verify($current_password, $user['password'])) {
            $error = "Одоогийн нууц үг буруу байна";
        }
        elseif(strlen($new_password) < 6) {
            $error = "Шинэ нууц үг багадаа 6 тэмдэгт байх ёстой";
        }
        elseif($new_password !== $confirm_password) {
            $error = "Нууц үг таарахгүй байна";
        }
        else {
            // Нууц үг солих
            $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET password = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $password_hash, $user_id);
            
            if($stmt->execute()) {
                $success = "Нууц үг амжилттай солигдлоо!";
            } else {
                $error = "Алдаа гарлаа. Дахин оролдоно уу.";
            }
        }
    }
}

$page_title = "Профайл";
include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container" style="max-width: 800px; margin-top: 40px; margin-bottom: 60px;">
    
    <h1 style="margin-bottom: 30px;">👤 Профайл тохиргоо</h1>
    
    <?php if($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <?php if($error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <!-- Профайл мэдээлэл -->
    <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px;">
        <h2 style="margin-bottom: 20px;">Миний мэдээлэл</h2>
        
        <form method="POST" action="" data-loading="Мэдээлэл шинэчилж байна..." data-loading-overlay>
            <div class="form-group">
                <label>Нэр</label>
                <input type="text" name="name" required value="<?php echo htmlspecialchars($user['name']); ?>">
            </div>
            
            <div class="form-group">
                <label>Имэйл хаяг</label>
                <input type="email" name="email" required value="<?php echo htmlspecialchars($user['email']); ?>">
            </div>
            
            <div class="form-group">
                <label style="color: #6b7280;">Бүртгүүлсэн огноо</label>
                <input type="text" value="<?php echo formatDate($user['created_at']); ?>" disabled
                       style="background: #f3f4f6; color: #6b7280;">
            </div>
            
            <button type="submit" name="update_profile" class="btn btn-primary">
                💾 Хадгалах
            </button>
        </form>
    </div>
    
    <!-- Нууц үг солих -->
    <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <h2 style="margin-bottom: 20px;">Нууц үг солих</h2>
        
        <form method="POST" action="" data-loading="Нууц үг солиж байна..." data-loading-overlay>
            <div class="form-group">
                <label>Одоогийн нууц үг</label>
                <input type="password" name="current_password" required placeholder="••••••••">
            </div>
            
            <div class="form-group">
                <label>Шинэ нууц үг (багадаа 6 тэмдэгт)</label>
                <input type="password" name="new_password" required minlength="6" placeholder="••••••••">
            </div>
            
            <div class="form-group">
                <label>Шинэ нууц үг баталгаажуулах</label>
                <input type="password" name="confirm_password" required minlength="6" placeholder="••••••••">
            </div>
            
            <button type="submit" name="change_password" class="btn btn-primary">
                🔒 Нууц үг солих
            </button>
        </form>
    </div>
    
</div>

<?php include '../includes/footer.php'; ?>