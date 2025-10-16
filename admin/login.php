<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Хэрэв аль хэдийн нэвтэрсэн бол dashboard руу
if(isAdmin()) {
    redirect('dashboard.php');
}

$error = '';

// Форм submit хийхэд
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = clean($_POST['username']);
    $password = $_POST['password'];
    
    // Validation
    if(empty($username) || empty($password)) {
        $error = "Бүх талбарыг бөглөнө үү";
    } else {
        // Database-аас админ хайх
        $sql = "SELECT * FROM admins WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            
            // Нууц үг шалгах
            if(password_verify($password, $admin['password'])) {
                // Session fixation халдлагаас хамгаалах
                session_regenerate_id(true);

                // Амжилттай нэвтэрсэн
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];

                redirect('dashboard.php');
            } else {
                // Security: User enumeration-аас хамгаалах нэгдсэн мессеж
                $error = "Нэвтрэх нэр эсвэл нууц үг буруу байна";
            }
        } else {
            // Security: User enumeration-аас хамгаалах нэгдсэн мессеж
            $error = "Нэвтрэх нэр эсвэл нууц үг буруу байна";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ нэвтрэх - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/css/style.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/css/responsive.css">
</head>
<body style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center;">

<div style="max-width: 400px; width: 90%;">
    <div style="background: white; padding: 40px; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
        
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: #2563eb; margin-bottom: 10px;">🔐 Админ Панел</h1>
            <p style="color: #6b7280;">Нэвтрэх эрхээ оруулна уу</p>
        </div>
        
        <?php if($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="" data-loading="Нэвтрэх..." data-loading-overlay>
            <div class="form-group">
                <label>Нэвтрэх нэр</label>
                <input type="text" name="username" placeholder="admin" required autofocus
                       value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label>Нууц үг</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%;">
                Нэвтрэх
            </button>
        </form>
        
        <p style="text-align: center; margin-top: 20px; color: #6b7280; font-size: 14px;">
            <a href="<?php echo SITE_URL; ?>" style="color: #2563eb; text-decoration: none;">← Нүүр хуудас руу буцах</a>
        </p>
        
    </div>
    
    <p style="text-align: center; margin-top: 20px; color: white; font-size: 12px;">
        Анхдагч: admin / admin123
    </p>
</div>

</body>
</html>