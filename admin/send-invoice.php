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

// Invoice имэйл илгээх
$to = $order['email'];
$subject = "💳 Төлбөрийн мэдээлэл - #" . $order['order_number'];

$message = "
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #2563eb; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border: 1px solid #e5e7eb; }
        .info-box { background: white; padding: 20px; margin: 20px 0; border-radius: 5px; }
        .bank-info { background: #fef3c7; padding: 20px; border-left: 4px solid #f59e0b; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #6b7280; font-size: 14px; }
        .highlight { color: #2563eb; font-weight: bold; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>Төлбөрийн мэдээлэл</h1>
        </div>
        
        <div class='content'>
            <p>Сайн байна уу <strong>" . htmlspecialchars($order['user_name']) . "</strong>,</p>
            
            <p>Таны захиалгын төлбөрийн мэдээлэл доор байна:</p>
            
            <div class='info-box'>
                <h3>Захиалгын мэдээлэл</h3>
                <p><strong>Захиалгын дугаар:</strong> <span class='highlight'>#" . $order['order_number'] . "</span></p>
                <p><strong>Template:</strong> " . htmlspecialchars($order['template_name']) . "</p>
                <p><strong>Үнэ:</strong> <span class='highlight'>" . formatPrice($order['price']) . "</span></p>
                <p><strong>Огноо:</strong> " . formatDate($order['created_at']) . "</p>
            </div>
            
            <div class='bank-info'>
                <h3>💳 Банкны мэдээлэл</h3>
                <p><strong>Банк:</strong> Хаан Банк</p>
                <p><strong>Дансны дугаар:</strong> 5123456789</p>
                <p><strong>Нэр:</strong> " . SITE_NAME . "</p>
                <p><strong>Дүн:</strong> " . formatPrice($order['price']) . "</p>
                <p><strong>Утга:</strong> " . $order['order_number'] . "</p>
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
        </div>
        
        <div class='footer'>
            <p>&copy; 2025 " . SITE_NAME . ". Бүх эрх хуулиар хамгаалагдсан.</p>
        </div>
    </div>
</body>
</html>
";

// Имэйл илгээх
if(sendEmail($to, $subject, $message)) {
    setAlert("Invoice амжилттай илгээлээ: " . $order['email'], 'success');
} else {
    setAlert("Invoice илгээхэд алдаа гарлаа", 'error');
}

redirect('orders.php');
?>