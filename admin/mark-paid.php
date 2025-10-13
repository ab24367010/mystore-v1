<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Админ эсэхийг шалгах
if(!isAdmin()) {
    redirect('login.php');
}

// Order ID авах
if(!isset($_GET['order_id']) || empty($_GET['order_id'])) {
    setAlert("Захиалга олдсонгүй", 'error');
    redirect('orders.php');
}

$order_id = (int)$_GET['order_id'];

// Төлөв paid болгох
$sql = "UPDATE orders SET status = 'paid' WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);

if($stmt->execute()) {
    // Хэрэглэгчид мэдэгдэх имэйл илгээх
    $order_sql = "SELECT o.*, u.email, u.name as user_name, t.name as template_name 
                  FROM orders o 
                  JOIN users u ON o.user_id = u.id 
                  JOIN templates t ON o.template_id = t.id 
                  WHERE o.id = ?";
    $stmt2 = $conn->prepare($order_sql);
    $stmt2->bind_param("i", $order_id);
    $stmt2->execute();
    $order = $stmt2->get_result()->fetch_assoc();
    
    // Имэйл илгээх
    $subject = "✅ Төлбөр баталгаажлаа - #" . $order['order_number'];
    $message = "
    <html>
    <body style='font-family: Arial, sans-serif; line-height: 1.6;'>
        <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
            <div style='background: #10b981; color: white; padding: 20px; text-align: center; border-radius: 5px;'>
                <h1>✅ Төлбөр хүлээн авлаа!</h1>
            </div>
            
            <div style='background: #f9f9f9; padding: 30px; margin-top: 20px; border: 1px solid #e5e7eb;'>
                <p>Сайн байна уу <strong>" . htmlspecialchars($order['user_name']) . "</strong>,</p>
                
                <p>Таны төлбөр амжилттай баталгаажлаа! 🎉</p>
                
                <div style='background: white; padding: 20px; margin: 20px 0; border-radius: 5px;'>
                    <p><strong>Захиалгын дугаар:</strong> #" . $order['order_number'] . "</p>
                    <p><strong>Template:</strong> " . htmlspecialchars($order['template_name']) . "</p>
                </div>
                
                <div style='background: #d1fae5; padding: 20px; margin: 20px 0; border-radius: 5px; border-left: 4px solid #10b981;'>
                    <h3 style='margin-top: 0;'>📦 Дараагийн алхам</h3>
                    <p>Template татах линк удахгүй имэйлээр ирнэ (24 цагийн дотор).</p>
                    <p>Эсвэл <a href='" . SITE_URL . "/user/my-templates.php'>Миний Template-үүд</a> хуудаснаас шууд татаж авна уу.</p>
                </div>
                
                <p>Баярлалаа!<br><strong>" . SITE_NAME . "</strong></p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    sendEmail($order['email'], $subject, $message);
    
    setAlert("Захиалга #" . $order['order_number'] . " амжилттай 'Paid' болгогдлоо", 'success');
} else {
    setAlert("Алдаа гарлаа. Дахин оролдоно уу.", 'error');
}

redirect('orders.php');
?>