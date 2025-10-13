<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Нэвтэрсэн эсэхийг шалгах
if(!isLoggedIn()) {
    redirect('../login.php?redirect=user/my-templates.php');
}

$user_id = $_SESSION['user_id'];

// Худалдаж авсан template-үүд
$sql = "SELECT o.*, t.name, t.thumbnail, t.price, t.demo_url, t.file_path 
        FROM orders o 
        JOIN templates t ON o.template_id = t.id 
        WHERE o.user_id = ? 
        ORDER BY o.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$page_title = "Миний Template-үүд";
include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container" style="margin-top: 40px; margin-bottom: 60px;">
    
    <?php showAlert(); ?>
    
    <h1 style="margin-bottom: 30px;">📦 Миний Template-үүд</h1>
    
    <?php if(mysqli_num_rows($result) > 0): ?>
        
        <div class="templates-grid">
            <?php while($order = mysqli_fetch_assoc($result)): ?>
                
                <div class="template-card">
                    <img src="<?php echo $order['thumbnail'] ? SITE_URL . '/uploads/templates/' . $order['thumbnail'] : SITE_URL . '/images/placeholder.jpg'; ?>" 
                         alt="<?php echo htmlspecialchars($order['name']); ?>">
                    
                    <div class="template-card-content">
                        <h3><?php echo htmlspecialchars($order['name']); ?></h3>
                        
                        <!-- Төлөв -->
                        <?php if($order['status'] == 'pending'): ?>
                            <span style="display: inline-block; background: #fef3c7; color: #92400e; padding: 5px 15px; border-radius: 5px; font-size: 12px; margin-bottom: 10px;">
                                ⏳ Төлбөр хүлээгдэж байна
                            </span>
                        <?php elseif($order['status'] == 'paid' || $order['status'] == 'delivered'): ?>
                            <span style="display: inline-block; background: #d1fae5; color: #065f46; padding: 5px 15px; border-radius: 5px; font-size: 12px; margin-bottom: 10px;">
                                ✅ Бэлэн
                            </span>
                        <?php elseif($order['status'] == 'cancelled'): ?>
                            <span style="display: inline-block; background: #fee2e2; color: #991b1b; padding: 5px 15px; border-radius: 5px; font-size: 12px; margin-bottom: 10px;">
                                ❌ Цуцлагдсан
                            </span>
                        <?php endif; ?>
                        
                        <p style="color: #6b7280; font-size: 14px; margin-bottom: 10px;">
                            Огноо: <?php echo formatDate($order['created_at']); ?>
                        </p>
                        
                        <div class="price" style="font-size: 20px; margin-bottom: 15px;">
                            <?php echo formatPrice($order['price']); ?>
                        </div>
                        
                        <!-- Үйлдлүүд -->
                        <div style="display: flex; gap: 10px; flex-direction: column;">
                            
                            <?php if($order['status'] == 'paid' || $order['status'] == 'delivered'): ?>
                                <!-- Татах товч -->
                                <a href="download.php?token=<?php echo $order['download_token']; ?>" 
                                   class="btn btn-success" style="text-align: center;">
                                    ⬇️ Татах
                                </a>
                            <?php endif; ?>
                            
                            <?php if($order['demo_url']): ?>
                                <!-- Demo үзэх -->
                                <a href="<?php echo $order['demo_url']; ?>" target="_blank"
                                   class="btn" style="background: #6b7280; color: white; text-align: center;">
                                    👁️ Demo үзэх
                                </a>
                            <?php endif; ?>
                            
                            <!-- Дэлгэрэнгүй -->
                            <a href="../template-detail.php?id=<?php echo $order['template_id']; ?>"
                               class="btn btn-primary" style="text-align: center;">
                                📄 Дэлгэрэнгүй
                            </a>
                            
                        </div>
                    </div>
                </div>
                
            <?php endwhile; ?>
        </div>
        
    <?php else: ?>
        
        <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 10px;">
            <div style="font-size: 80px; margin-bottom: 20px;">📭</div>
            <h2 style="color: #6b7280; margin-bottom: 20px;">Та template аваагүй байна</h2>
            <p style="color: #9ca3af; margin-bottom: 30px;">Танд тохирох template-ээ олоорой!</p>
            <a href="../templates.php" class="btn btn-primary">Template үзэх</a>
        </div>
        
    <?php endif; ?>
    
</div>

<?php include '../includes/footer.php'; ?>