<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Нэвтэрсэн эсэхийг шалгах
if(!isLoggedIn()) {
    redirect('../login.php?redirect=user/dashboard.php');
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Хэрэглэгчийн статистик
$sql = "SELECT COUNT(*) as total_orders FROM orders WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$total_orders = $stmt->get_result()->fetch_assoc()['total_orders'];

// Pending захиалга
$sql = "SELECT COUNT(*) as pending FROM orders WHERE user_id = ? AND status = 'pending'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$pending_orders = $stmt->get_result()->fetch_assoc()['pending'];

// Paid захиалга
$sql = "SELECT COUNT(*) as paid FROM orders WHERE user_id = ? AND status IN ('paid', 'delivered')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$paid_orders = $stmt->get_result()->fetch_assoc()['paid'];

$page_title = "Миний хуудас";
include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container" style="margin-top: 40px; margin-bottom: 60px;">
    
    <?php showAlert(); ?>
    
    <h1 style="margin-bottom: 30px;">Сайн байна уу, <?php echo htmlspecialchars($user_name); ?>! 👋</h1>
    
    <!-- Статистик картууд -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 40px;">
        
        <!-- Нийт захиалга -->
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 10px; text-align: center;">
            <h3 style="font-size: 36px; margin-bottom: 10px;"><?php echo $total_orders; ?></h3>
            <p style="font-size: 18px;">Нийт захиалга</p>
        </div>
        
        <!-- Хүлээгдэж байгаа -->
        <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 30px; border-radius: 10px; text-align: center;">
            <h3 style="font-size: 36px; margin-bottom: 10px;"><?php echo $pending_orders; ?></h3>
            <p style="font-size: 18px;">Хүлээгдэж байгаа</p>
        </div>
        
        <!-- Бэлэн -->
        <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 30px; border-radius: 10px; text-align: center;">
            <h3 style="font-size: 36px; margin-bottom: 10px;"><?php echo $paid_orders; ?></h3>
            <p style="font-size: 18px;">Бэлэн template</p>
        </div>
        
    </div>
    
    <!-- Товчлол линкүүд -->
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
        
        <a href="my-templates.php" style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-decoration: none; color: #333; text-align: center; transition: transform 0.3s;">
            <h3 style="font-size: 24px; margin-bottom: 10px;">📦 Миний Template-үүд</h3>
            <p style="color: #6b7280;">Худалдаж авсан template-үүдээ харах</p>
        </a>
        
        <a href="my-orders.php" style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-decoration: none; color: #333; text-align: center; transition: transform 0.3s;">
            <h3 style="font-size: 24px; margin-bottom: 10px;">📋 Миний Захиалгууд</h3>
            <p style="color: #6b7280;">Захиалгын түүх харах</p>
        </a>
        
        <a href="profile.php" style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-decoration: none; color: #333; text-align: center; transition: transform 0.3s;">
            <h3 style="font-size: 24px; margin-bottom: 10px;">👤 Профайл</h3>
            <p style="color: #6b7280;">Мэдээллээ засах</p>
        </a>
        
        <a href="../templates.php" style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-decoration: none; color: #333; text-align: center; transition: transform 0.3s;">
            <h3 style="font-size: 24px; margin-bottom: 10px;">🛍️ Template авах</h3>
            <p style="color: #6b7280;">Шинэ template үзэх</p>
        </a>
        
    </div>
    
</div>

<?php include '../includes/footer.php'; ?>