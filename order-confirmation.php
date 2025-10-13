<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Заавал нэвтэрсэн байх
if(!isLoggedIn()) {
    redirect('login.php');
}

// Order ID шалгах
if(!isset($_GET['order_id']) || empty($_GET['order_id'])) {
    redirect('user/dashboard.php');
}

$order_id = (int)$_GET['order_id'];
$user_id = $_SESSION['user_id'];

// Захиалгын мэдээлэл татах
$sql = "SELECT o.*, t.name as template_name, t.price 
        FROM orders o 
        JOIN templates t ON o.template_id = t.id 
        WHERE o.id = ? AND o.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0) {
    redirect('user/dashboard.php');
}

$order = $result->fetch_assoc();

$page_title = "Захиалга баталгаажлаа";
include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container" style="max-width: 700px; margin-top: 50px; margin-bottom: 60px; text-align: center;">
    
    <!-- Амжилттай icon -->
    <div style="background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%); width: 100px; height: 100px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
        <span style="font-size: 50px;">✅</span>
    </div>
    
    <h1 style="font-size: 36px; margin-bottom: 20px;">Амжилттай!</h1>
    <p style="font-size: 18px; color: #6b7280; margin-bottom: 40px;">
        Таны захиалгыг хүлээн авлаа
    </p>
    
    <div style="background: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: left;">
        
        <h2 style="margin-bottom: 20px; text-align: center;">Захиалгын мэдээлэл</h2>
        
        <table style="width: 100%; margin-bottom: 30px;">
            <tr style="border-bottom: 1px solid #e5e7eb;">
                <td style="padding: 15px 0; color: #6b7280;">Захиалгын дугаар:</td>
                <td style="padding: 15px 0; text-align: right; font-weight: bold;">#<?php echo $order['order_number']; ?></td>
            </tr>
            <tr style="border-bottom: 1px solid #e5e7eb;">
                <td style="padding: 15px 0; color: #6b7280;">Template:</td>
                <td style="padding: 15px 0; text-align: right; font-weight: bold;"><?php echo htmlspecialchars($order['template_name']); ?></td>
            </tr>
            <tr style="border-bottom: 1px solid #e5e7eb;">
                <td style="padding: 15px 0; color: #6b7280;">Үнэ:</td>
                <td style="padding: 15px 0; text-align: right; font-weight: bold; color: #2563eb;"><?php echo formatPrice($order['price']); ?></td>
            </tr>
            <tr>
                <td style="padding: 15px 0; color: #6b7280;">Огноо:</td>
                <td style="padding: 15px 0; text-align: right;"><?php echo formatDate($order['created_at']); ?></td>
            </tr>
        </table>
        
        <div style="background: #fef3c7; padding: 20px; border-radius: 5px; border-left: 4px solid #f59e0b; margin-bottom: 30px;">
            <h3 style="color: #92400e; margin-bottom: 10px;">📧 Имэйл илгээлээ</h3>
            <p style="color: #78350f; margin: 0;">
                Төлбөрийн мэдээлэл таны имэйл хаяг руу илгээсэн. 
                Имэйл ирэхгүй бол spam folder-оо шалгаарай.
            </p>
        </div>
        
        <h3 style="margin-bottom: 15px;">Дараагийн алхмууд:</h3>
        <ol style="color: #374151; line-height: 2; padding-left: 20px;">
            <li>📧 Имэйлээ шалгаарай (төлбөрийн мэдээлэл ирнэ)</li>
            <li>💳 Дансанд шилжүүлэг хийнэ</li>
            <li>⏳ Төлбөр баталгаажих хүртэл хүлээнэ (24 цагийн дотор)</li>
            <li>📦 Template татах линк имэйлээр ирнэ</li>
        </ol>
        
        <div style="margin-top: 30px; text-align: center;">
            <a href="user/my-orders.php" class="btn btn-primary" style="margin: 5px;">
                Миний захиалгууд харах
            </a>
            <a href="templates.php" class="btn" style="margin: 5px; background: #6b7280; color: white;">
                Бусад template үзэх
            </a>
        </div>
        
    </div>
    
</div>

<?php include 'includes/footer.php'; ?>