<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$page_title = "Нууц үг солих";
include 'header.php';

$error = '';
$success = '';

// Форм submit хийхэд
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation
    if(empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error = "Бүх талбарыг бөглөнө үү";
    } elseif($new_password !== $confirm_password) {
        $error = "Шинэ нууц үг таарахгүй байна";
    } elseif(strlen($new_password) < 6) {
        $error = "Шинэ нууц үг дор хаяж 6 тэмдэгт байх ёстой";
    } else {
        // Одоогийн админ мэдээлэл авах
        $admin_id = $_SESSION['admin_id'];
        $sql = "SELECT * FROM admins WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $admin_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0) {
            $admin = $result->fetch_assoc();

            // Одоогийн нууц үг шалгах
            if(password_verify($current_password, $admin['password'])) {
                // Шинэ нууц үг hash хийх
                $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

                // Database update хийх
                $update_sql = "UPDATE admins SET password = ? WHERE id = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("si", $new_password_hash, $admin_id);

                if($update_stmt->execute()) {
                    $success = "Нууц үг амжилттай солигдлоо!";

                    // Формыг цэвэрлэх
                    $_POST = array();
                } else {
                    $error = "Нууц үг солих явцад алдаа гарлаа";
                }
            } else {
                $error = "Одоогийн нууц үг буруу байна";
            }
        } else {
            $error = "Админ олдсонгүй";
        }
    }
}
?>

<div class="container" style="margin-bottom: 60px;">

    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 30px;">
        <a href="dashboard.php" style="text-decoration: none; color: #2563eb; font-size: 20px;">←</a>
        <h1 style="margin: 0;">Нууц үг солих</h1>
    </div>

    <div style="max-width: 600px;">
        <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">

            <?php if($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST" action="">

                <div class="form-group">
                    <label>Одоогийн нууц үг *</label>
                    <input type="password" name="current_password" required>
                </div>

                <div class="form-group">
                    <label>Шинэ нууц үг *</label>
                    <input type="password" name="new_password" minlength="6" required>
                    <small style="color: #6b7280; display: block; margin-top: 5px;">Дор хаяж 6 тэмдэгт байх ёстой</small>
                </div>

                <div class="form-group">
                    <label>Шинэ нууц үг (Баталгаажуулах) *</label>
                    <input type="password" name="confirm_password" minlength="6" required>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-primary">
                        Нууц үг солих
                    </button>
                    <a href="dashboard.php" class="btn" style="background: #6b7280; color: white; text-decoration: none; display: inline-block;">
                        Болих
                    </a>
                </div>

            </form>

        </div>

        <!-- Анхааруулга -->
        <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin-top: 20px; border-radius: 5px;">
            <p style="color: #92400e; margin: 0; font-size: 14px;">
                ⚠️ <strong>Анхаар:</strong> Нууц үгээ солиход дараагийн нэвтрэх үедээ шинэ нууц үгээ ашиглана уу.
            </p>
        </div>
    </div>

</div>

<?php include 'footer.php'; ?>
