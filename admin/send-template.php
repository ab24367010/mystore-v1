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
$sql = "SELECT o.*, u.name as user_name, u.email, t.name as template_name, t.file_path 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        JOIN templates t ON o.template_id = t.id 
        WHERE o.id = ? AND o.status = 'paid'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0) {
    setAlert("Захиалга олдсонгүй эсвэл төлөв буруу байна", 'error');
    redirect('orders.php');
}

$order = $result->fetch_assoc();

// Төлөв delivered болгох
$update_sql = "UPDATE orders SET status = 'delivered' WHERE id = ?";
$stmt = $conn->prepare($update_sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();

// Татах линк үүсгэх
$download_link = SITE_URL . "/user/download.php?token=" . $order['download_token'];

// Хэрэглэгчид template илгээх имэйл
$subject = "🎉 Таны Template бэлэн боллоо! - #" . $order['order_number'];

$message = "
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border: 1px solid #e5e7eb; }
        .download-box { background: #dbeafe; padding: 25px; margin: 20px 0; border-radius: 5px; text-align: center; border-left: 4px solid #2563eb; }
        .download-btn { display: inline-block; background: #2563eb; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; margin: 10px 0; }
        .info-box { background: white; padding: 20px; margin: 20px 0; border-radius: 5px; }
        .warning-box { background: #fef3c7; padding: 20px; margin: 20px 0; border-radius: 5px; border-left: 4px solid #f59e0b; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>🎉 Template бэлэн боллоо!</h1>
            <p>Төлбөр баталгаажсанд баярлалаа</p>
        </div>
        
        <div class='content'>
            <p>Сайн байна уу <strong>" . htmlspecialchars($order['user_name']) . "</strong>,</p>
            
            <p>Таны template-ийг татахад бэлэн боллоо! 🚀</p>
            
            <div class='info-box'>
                <p><strong>Template:</strong> " . htmlspecialchars($order['template_name']) . "</p>
                <p><strong>Захиалгын дугаар:</strong> #" . $order['order_number'] . "</p>
            </div>
            
            <div class='download-box'>
                <h2 style='margin-top: 0; color: #1e40af;'>⬇️ Татах бэлэн</h2>
                <p>Доорх товчоор дарж template-ээ татаж аваарай</p>
                <a href='" . $download_link . "' class='download-btn'>📦 Template татах</a>
                <p style='font-size: 12px; color: #6b7280; margin-top: 15px;'>
                    Эсвэл энэ линкийг хуулаарай:<br>
                    <code style='background: white; padding: 5px 10px; border-radius: 3px; display: inline-block; margin-top: 5px;'>" . $download_link . "</code>
                </p>
            </div>
            
            <div class='warning-box'>
                <h3 style='margin-top: 0;'>⚠️ Анхаар</h3>
                <ul style='margin: 10px 0; padding-left: 20px;'>
                    <li>Энэ линк 30 хоногийн турш хүчинтэй</li>
                    <li>Та хэдэн ч удаа татаж болно</li>
                    <li>Файлыг хадгалж аваарай</li>
                    <li>Documentation-тай танилцана уу</li>
                </ul>
            </div>
            
            <div class='info-box'>
                <h3>🎯 Дараагийн алхмууд:</h3>
                <ol>
                    <li>Татах товч дарж ZIP файлыг татаарай</li>
                    <li>Файлыг задлаарай</li>
                    <li>README файлыг уншаарай</li>
                    <li>Template-ээ ашиглаж эхлээрэй!</li>
                </ol>
            </div>
            
            <p>Асуулт эсвэл тусламж хэрэгтэй бол <a href='mailto:" . ADMIN_EMAIL . "'>" . ADMIN_EMAIL . "</a> хаяг руу холбогдоно уу.</p>
            
            <p style='margin-top: 30px;'>Танд амжилт хүсье!<br><strong>" . SITE_NAME . " баг</strong></p>
        </div>
        
        <div style='text-align: center; padding: 20px; color: #6b7280; font-size: 14px;'>
            <p>&copy; 2025 " . SITE_NAME . ". Бүх эрх хуулиар хамгаалагдсан.</p>
        </div>
    </div>
</body>
</html>
";

// Имэйл илгээх
if(sendEmail($order['email'], $subject, $message)) {
    setAlert("Template амжилттай илгээгдлээ: " . $order['email'], 'success');
} else {
    setAlert("Имэйл илгээхэд алдаа гарлаа", 'error');
}

redirect('orders.php');
?>