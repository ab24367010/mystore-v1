<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Token шалгах
if(!isset($_GET['token']) || empty($_GET['token'])) {
    redirect('my-templates.php');
}

$token = clean($_GET['token']);

// Token-оор захиалга олох
$sql = "SELECT o.*, t.name as template_name, t.file_path, t.demo_url, u.email 
        FROM orders o 
        JOIN templates t ON o.template_id = t.id 
        JOIN users u ON o.user_id = u.id 
        WHERE o.download_token = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0) {
    $error = "Татах линк буруу эсвэл хугацаа дууссан байна.";
} else {
    $order = $result->fetch_assoc();
    
    // Хугацаа шалгах
    if(strtotime($order['token_expires']) < time()) {
        $error = "Татах линкийн хугацаа дууссан байна. Манай дэмжлэгтэй холбогдоно уу.";
    }
    // Төлөв шалгах
    elseif($order['status'] != 'paid' && $order['status'] != 'delivered') {
        $error = "Төлбөр хүлээгдэж байна. Төлбөр хийсний дараа татах боломжтой болно.";
    }
}

$page_title = "Template татах";
include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container" style="max-width: 700px; margin-top: 50px; margin-bottom: 60px;">
    
    <?php if(isset($error)): ?>
        
        <!-- Алдааны мессеж -->
        <div style="text-align: center; background: white; padding: 60px 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <div style="font-size: 80px; margin-bottom: 20px;">⚠️</div>
            <h2 style="color: #ef4444; margin-bottom: 20px;">Алдаа гарлаа</h2>
            <p style="color: #6b7280; margin-bottom: 30px; font-size: 18px;"><?php echo $error; ?></p>
            <a href="my-templates.php" class="btn btn-primary">Миний template-үүд руу буцах</a>
        </div>
        
    <?php else: ?>
        
        <!-- Татах хуудас -->
        <div style="background: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center;">
            
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); width: 100px; height: 100px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 30px;">
                <span style="font-size: 50px;">📦</span>
            </div>
            
            <h1 style="margin-bottom: 20px;"><?php echo htmlspecialchars($order['template_name']); ?></h1>
            
            <p style="color: #6b7280; margin-bottom: 30px; font-size: 16px;">
                Таны template бэлэн боллоо! Доорх товчоор татаж авна уу.
            </p>
            
            <hr style="margin: 30px 0; border: none; border-top: 1px solid #e5e7eb;">
            
            <!-- Татах товч -->
            <?php if($order['file_path']): ?>
                <a href="<?php echo SITE_URL . '/uploads/files/' . $order['file_path']; ?>" 
                   download
                   class="btn btn-success" 
                   style="width: 100%; font-size: 18px; padding: 15px; margin-bottom: 15px;">
                    ⬇️ Template татах (ZIP)
                </a>
            <?php else: ?>
                <div class="alert alert-error">
                    Файл олдсонгүй. Манай дэмжлэгтэй холбогдоно уу.
                </div>
            <?php endif; ?>
            
            <!-- Demo товч -->
            <?php if($order['demo_url']): ?>
                <a href="<?php echo $order['demo_url']; ?>" 
                   target="_blank"
                   class="btn" 
                   style="width: 100%; background: #6b7280; color: white; margin-bottom: 15px;">
                    👁️ Demo үзэх
                </a>
            <?php endif; ?>
            
            <!-- Documentation -->
            <a href="#" class="btn btn-primary" style="width: 100%; margin-bottom: 30px;">
                📚 Documentation унших
            </a>
            
            <hr style="margin: 30px 0; border: none; border-top: 1px solid #e5e7eb;">
            
            <!-- Анхааруулга -->
            <div style="background: #fef3c7; padding: 20px; border-radius: 5px; border-left: 4px solid #f59e0b; text-align: left; margin-bottom: 20px;">
                <h3 style="color: #92400e; margin-bottom: 10px;">⚠️ Анхаар</h3>
                <ul style="color: #78350f; margin: 0; padding-left: 20px; line-height: 1.8;">
                    <li>Энэ линк <?php echo date('Y-m-d', strtotime($order['token_expires'])); ?> хүртэл хүчинтэй</li>
                    <li>Файлыг хадгалж аваарай (дахин татах боломжтой)</li>
                    <li>Асуулт байвал <?php echo ADMIN_EMAIL; ?> руу холбогдоно уу</li>
                </ul>
            </div>
            
            <div style="display: flex; gap: 10px; justify-content: center;">
                <a href="my-templates.php" class="btn" style="background: #e5e7eb; color: #374151;">
                    ← Миний template-үүд
                </a>
                <a href="../templates.php" class="btn" style="background: #e5e7eb; color: #374151;">
                    🛍️ Бусад template үзэх
                </a>
            </div>
            
        </div>
        
    <?php endif; ?>
    
</div>

<?php include '../includes/footer.php'; ?>