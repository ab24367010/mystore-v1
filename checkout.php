<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Заавал нэвтэрсэн байх ёстой
if(!isLoggedIn()) {
    redirect('login.php?redirect=checkout.php?template_id=' . $_GET['template_id']);
}

// Template ID шалгах
if(!isset($_GET['template_id']) || empty($_GET['template_id'])) {
    redirect('templates.php');
}

$template_id = (int)$_GET['template_id'];
$user_id = $_SESSION['user_id'];

// Template мэдээлэл татах
$sql = "SELECT * FROM templates WHERE id = ? AND status = 'active'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $template_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0) {
    redirect('templates.php');
}

$template = $result->fetch_assoc();

// Хэрэглэгч аль хэдийн энэ template-ийг авсан эсэхийг шалгах
$check_sql = "SELECT * FROM orders WHERE user_id = ? AND template_id = ? AND status IN ('paid', 'delivered')";
$stmt = $conn->prepare($check_sql);
$stmt->bind_param("ii", $user_id, $template_id);
$stmt->execute();

if($stmt->get_result()->num_rows > 0) {
    setAlert("Та энэ template-ийг аль хэдийн авсан байна!", 'error');
    redirect('user/my-templates.php');
}

$error = '';

// Форм submit хийхэд
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Захиалгын дугаар үүсгэх
    $order_number = generateOrderNumber();
    
    // Download token үүсгэх
    $download_token = generateToken();
    
    // Token хугацаа (30 хоног)
    $token_expires = date('Y-m-d H:i:s', strtotime('+30 days'));
    
    // Захиалга үүсгэх
    $sql = "INSERT INTO orders (user_id, template_id, order_number, status, download_token, token_expires, created_at) 
            VALUES (?, ?, ?, 'pending', ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisss", $user_id, $template_id, $order_number, $download_token, $token_expires);
    
    if($stmt->execute()) {
        $order_id = $stmt->insert_id;
        
        // Админд имэйл илгээх (энгийн хувилбар)
        $admin_subject = "🔔 Шинэ захиалга #" . $order_number;
        $admin_message = "
            <h2>Шинэ захиалга ирлээ!</h2>
            <p><strong>Захиалгын дугаар:</strong> #$order_number</p>
            <p><strong>Хэрэглэгч:</strong> {$_SESSION['user_name']} ({$_SESSION['user_email']})</p>
            <p><strong>Template:</strong> {$template['name']}</p>
            <p><strong>Үнэ:</strong> " . formatPrice($template['price']) . "</p>
            <p><a href='" . SITE_URL . "/admin/orders.php'>Захиалга удирдах</a></p>
        ";
        sendEmail(ADMIN_EMAIL, $admin_subject, $admin_message);
        
        // Хэрэглэгчид имэйл илгээх
        $user_subject = "Захиалга баталгаажлаа - #" . $order_number;
        $user_message = "
            <h2>Сайн байна уу {$_SESSION['user_name']},</h2>
            <p>Таны захиалгыг хүлээн авлаа!</p>
            
            <h3>Захиалгын мэдээлэл</h3>
            <p><strong>Захиалгын дугаар:</strong> #$order_number</p>
            <p><strong>Template:</strong> {$template['name']}</p>
            <p><strong>Үнэ:</strong> " . formatPrice($template['price']) . "</p>
            
            <h3>Дараагийн алхам</h3>
            <ol>
                <li>Төлбөрийн мэдээлэл 5-10 минутын дотор ирнэ</li>
                <li>Төлбөр хийх</li>
                <li>Template татах линк ирнэ (24 цагийн дотор)</li>
            </ol>
            
            <p>Баярлалаа!</p>
        ";
        sendEmail($_SESSION['user_email'], $user_subject, $user_message);
        
        // Баталгаажуулах хуудас руу шилжих
        redirect('order-confirmation.php?order_id=' . $order_id);
        
    } else {
        $error = "Алдаа гарлаа. Дахин оролдоно уу.";
    }
}

$page_title = "Худалдаж авах";
include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container" style="max-width: 800px; margin-top: 50px; margin-bottom: 60px;">
    
    <h1 style="text-align: center; margin-bottom: 40px;">Захиалгаа баталгаажуулах</h1>
    
    <?php if($error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <div style="background: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        
        <!-- Template мэдээлэл -->
        <div style="display: flex; gap: 20px; margin-bottom: 30px; padding-bottom: 30px; border-bottom: 2px solid #e5e7eb;">
            <img src="<?php echo $template['thumbnail'] ? SITE_URL . '/uploads/templates/' . $template['thumbnail'] : SITE_URL . '/images/placeholder.jpg'; ?>" 
                 alt="<?php echo htmlspecialchars($template['name']); ?>"
                 style="width: 150px; height: 100px; object-fit: cover; border-radius: 5px;">
            
            <div style="flex: 1;">
                <h2 style="margin-bottom: 10px;"><?php echo htmlspecialchars($template['name']); ?></h2>
                <p style="color: #6b7280; margin-bottom: 10px;"><?php echo substr($template['description'], 0, 150); ?>...</p>
                <p style="font-size: 24px; font-weight: bold; color: #2563eb;"><?php echo formatPrice($template['price']); ?></p>
            </div>
        </div>
        
        <!-- Хэрэглэгчийн мэдээлэл -->
        <div style="margin-bottom: 30px;">
            <h3 style="margin-bottom: 15px;">Захиалагч</h3>
            <p><strong>Нэр:</strong> <?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
            <p><strong>Имэйл:</strong> <?php echo htmlspecialchars($_SESSION['user_email']); ?></p>
        </div>
        
        <!-- Нийт дүн -->
        <div style="background: #f3f4f6; padding: 20px; border-radius: 5px; margin-bottom: 30px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 20px; font-weight: bold;">Нийт төлөх дүн:</span>
                <span style="font-size: 28px; font-weight: bold; color: #2563eb;">
                    <?php echo formatPrice($template['price']); ?>
                </span>
            </div>
        </div>
        
        <!-- Баталгаажуулах форм -->
        <form method="POST" action="">
            <p style="color: #6b7280; margin-bottom: 20px; text-align: center;">
                "Баталгаажуулах" товч дарснаар та манай үйлчилгээний нөхцөлийг хүлээн зөвшөөрч байна.
            </p>
            
            <button type="submit" class="btn btn-success" style="width: 100%; font-size: 18px; padding: 15px;">
                ✅ Захиалга баталгаажуулах
            </button>
        </form>
        
        <p style="text-align: center; margin-top: 20px; color: #6b7280;">
            <a href="template-detail.php?id=<?php echo $template['id']; ?>" style="color: #6b7280;">← Буцах</a>
        </p>
        
    </div>
    
</div>

<?php include 'includes/footer.php'; ?>