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
    
    // Имэйл агуулга бэлтгэх
    $content = "
        <p>Сайн байна уу <strong>" . htmlspecialchars($order['user_name']) . "</strong>,</p>
        
        <p>Таны төлбөр амжилттай баталгаажлаа! 🎉</p>
        
        <div class='success-box'>
            <h3>✅ Төлбөр хүлээн авлаа!</h3>
            <p>Захиалгын дугаар: <strong>#" . $order['order_number'] . "</strong></p>
            <p>Template: <strong>" . htmlspecialchars($order['template_name']) . "</strong></p>
        </div>
        
        <div class='info-box'>
            <h3>📦 Дараагийн алхам</h3>
            <p>Template татах линк удахгүй имэйлээр ирнэ (24 цагийн дотор).</p>
            <p>Эсвэл <a href='" . SITE_URL . "/user/my-templates.php'>Миний Template-үүд</a> хуудаснаас шууд татаж авна уу.</p>
        </div>
        
        <a href='" . SITE_URL . "/user/my-templates.php' class='button'>Миний Template-үүд харах</a>
        
        <p>Баярлалаа!<br><strong>" . SITE_NAME . " баг</strong></p>
    ";
    
    $subject = "✅ Төлбөр баталгаажлаа - #" . $order['order_number'];
    $htmlMessage = getEmailTemplate("Төлбөр баталгаажлаа", $content);
    
    sendEmail($order['email'], $subject, $htmlMessage);
    
    setAlert("Захиалга #" . $order['order_number'] . " амжилттай 'Paid' болгогдлоо", 'success');
} else {
    setAlert("Алдаа гарлаа. Дахин оролдоно уу.", 'error');
}

redirect('orders.php');
?>