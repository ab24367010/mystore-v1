<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if(!isAdmin()) {
    redirect('login.php');
}

if(!isset($_GET['order_id']) || empty($_GET['order_id'])) {
    setAlert("Захиалга олдсонгүй", 'error');
    redirect('orders.php');
}

$order_id = (int)$_GET['order_id'];

// Захиалгын мэдээлэл татах
$sql = "SELECT o.*, u.name as user_name, u.email, t.name as template_name, t.price 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        JOIN templates t ON o.template_id = t.id 
        WHERE o.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0) {
    setAlert("Захиалга олдсонгүй", 'error');
    redirect('orders.php');
}

$order = $result->fetch_assoc();

// Invoice агуулга бэлтгэх
$content = "
    <p>Сайн байна уу <strong>" . htmlspecialchars($order['user_name']) . "</strong>,</p>
    
    <p>Таны захиалгын төлбөрийн мэдээлэл доор байна:</p>
    
    <div class='info-box'>
        <h3>Захиалгын мэдээлэл</h3>
        <p><strong>Захиалгын дугаар:</strong> #" . $order['order_number'] . "</p>
        <p><strong>Template:</strong> " . htmlspecialchars($order['template_name']) . "</p>
        <p><strong>Үнэ:</strong> " . formatPrice($order['price']) . "</p>
        <p><strong>Огноо:</strong> " . formatDate($order['created_at']) . "</p>
    </div>
    
    <div class='warning-box'>
        <h3>💳 Банкны мэдээлэл</h3>
        <p><strong>Банк:</strong> Хаан Банк</p>
        <p><strong>Дансны дугаар:</strong> 5123456789</p>
        <p><strong>Нэр:</strong> " . SITE_NAME . "</p>
        <p><strong>Дүн:</strong> " . formatPrice($order['price']) . "</p>
        <p><strong>Утга:</strong> <span style='background: #fef3c7; padding: 3px 8px; border-radius: 3px; font-weight: bold;'>" . $order['order_number'] . "</span></p>
    </div>
    
    <div class='info-box'>
        <h3>📋 Дараагийн алхмууд:</h3>
        <ol>
            <li>Дээрх данс руу шилжүүлэг хийнэ</li>
            <li>Утга хэсэгт заавал <strong>" . $order['order_number'] . "</strong> гэж бичнэ үү</li>
            <li>Төлбөр хийсний дараа 24 цагийн дотор template илгээх болно</li>
        </ol>
    </div>
    
    <p>Асуулт байвал <a href='mailto:" . ADMIN_EMAIL . "'>" . ADMIN_EMAIL . "</a> хаяг руу холбогдоно уу.</p>
    
    <p>Баярлалаа!<br><strong>" . SITE_NAME . "</strong></p>
";

// Email илгээх
$subject = "💳 Төлбөрийн мэдээлэл - #" . $order['order_number'];
$htmlMessage = getEmailTemplate("Төлбөрийн мэдээлэл", $content);

if(sendEmail($order['email'], $subject, $htmlMessage)) {
    setAlert("Invoice амжилттай илгээлээ: " . $order['email'], 'success');
} else {
    setAlert("Invoice илгээхэд алдаа гарлаа. SMTP тохиргоогоо шалгана уу.", 'error');
}

redirect('orders.php');
?>