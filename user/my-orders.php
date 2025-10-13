<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Нэвтэрсэн эсэхийг шалгах
if(!isLoggedIn()) {
    redirect('../login.php?redirect=user/my-orders.php');
}

$user_id = $_SESSION['user_id'];

// Бүх захиалгууд
$sql = "SELECT o.*, t.name as template_name, t.thumbnail, t.price 
        FROM orders o 
        JOIN templates t ON o.template_id = t.id 
        WHERE o.user_id = ? 
        ORDER BY o.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$page_title = "Миний Захиалгууд";
include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container" style="margin-top: 40px; margin-bottom: 60px;">
    
    <h1 style="margin-bottom: 30px;">📋 Миний Захиалгууд</h1>
    
    <?php if(mysqli_num_rows($result) > 0): ?>
        
        <div style="background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f3f4f6;">
                        <th style="padding: 15px; text-align: left; border-bottom: 2px solid #e5e7eb;">Захиалга</th>
                        <th style="padding: 15px; text-align: left; border-bottom: 2px solid #e5e7eb;">Template</th>
                        <th style="padding: 15px; text-align: left; border-bottom: 2px solid #e5e7eb;">Огноо</th>
                        <th style="padding: 15px; text-align: center; border-bottom: 2px solid #e5e7eb;">Үнэ</th>
                        <th style="padding: 15px; text-align: center; border-bottom: 2px solid #e5e7eb;">Төлөв</th>
                        <th style="padding: 15px; text-align: center; border-bottom: 2px solid #e5e7eb;">Үйлдэл</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($order = mysqli_fetch_assoc($result)): ?>
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            
                            <!-- Захиалгын дугаар -->
                            <td style="padding: 15px;">
                                <strong style="color: #2563eb;">#<?php echo $order['order_number']; ?></strong>
                            </td>
                            
                            <!-- Template -->
                            <td style="padding: 15px;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <img src="<?php echo $order['thumbnail'] ? SITE_URL . '/uploads/templates/' . $order['thumbnail'] : SITE_URL . '/images/placeholder.jpg'; ?>" 
                                         alt="<?php echo htmlspecialchars($order['template_name']); ?>"
                                         style="width: 60px; height: 40px; object-fit: cover; border-radius: 5px;">
                                    <span><?php echo htmlspecialchars($order['template_name']); ?></span>
                                </div>
                            </td>
                            
                            <!-- Огноо -->
                            <td style="padding: 15px; color: #6b7280; font-size: 14px;">
                                <?php echo date('Y-m-d', strtotime($order['created_at'])); ?>
                            </td>
                            
                            <!-- Үнэ -->
                            <td style="padding: 15px; text-align: center; font-weight: bold;">
                                <?php echo formatPrice($order['price']); ?>
                            </td>
                            
                            <!-- Төлөв -->
                            <td style="padding: 15px; text-align: center;">
                                <?php if($order['status'] == 'pending'): ?>
                                    <span style="display: inline-block; background: #fef3c7; color: #92400e; padding: 5px 15px; border-radius: 15px; font-size: 12px;">
                                        ⏳ Хүлээгдэж байгаа
                                    </span>
                                <?php elseif($order['status'] == 'paid'): ?>
                                    <span style="display: inline-block; background: #d1fae5; color: #065f46; padding: 5px 15px; border-radius: 15px; font-size: 12px;">
                                        ✅ Төлсөн
                                    </span>
                                <?php elseif($order['status'] == 'delivered'): ?>
                                    <span style="display: inline-block; background: #dbeafe; color: #1e40af; padding: 5px 15px; border-radius: 15px; font-size: 12px;">
                                        📦 Хүргэгдсэн
                                    </span>
                                <?php elseif($order['status'] == 'cancelled'): ?>
                                    <span style="display: inline-block; background: #fee2e2; color: #991b1b; padding: 5px 15px; border-radius: 15px; font-size: 12px;">
                                        ❌ Цуцлагдсан
                                    </span>
                                <?php endif; ?>
                            </td>
                            
                            <!-- Үйлдэл -->
                            <td style="padding: 15px; text-align: center;">
                                <?php if($order['status'] == 'paid' || $order['status'] == 'delivered'): ?>
                                    <a href="download.php?token=<?php echo $order['download_token']; ?>" 
                                       class="btn btn-success" style="font-size: 12px; padding: 8px 15px;">
                                        ⬇️ Татах
                                    </a>
                                <?php else: ?>
                                    <span style="color: #9ca3af; font-size: 12px;">Хүлээгдэж байна</span>
                                <?php endif; ?>
                            </td>
                            
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        
    <?php else: ?>
        
        <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 10px;">
            <div style="font-size: 80px; margin-bottom: 20px;">📝</div>
            <h2 style="color: #6b7280; margin-bottom: 20px;">Захиалга байхгүй байна</h2>
            <p style="color: #9ca3af; margin-bottom: 30px;">Эхний template-ээ авцгаая!</p>
            <a href="../templates.php" class="btn btn-primary">Template үзэх</a>
        </div>
        
    <?php endif; ?>
    
</div>

<?php include '../includes/footer.php'; ?>