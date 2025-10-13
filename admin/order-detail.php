<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$page_title = "Захиалгын дэлгэрэнгүй";
include 'header.php';

// Order ID авах
if(!isset($_GET['id']) || empty($_GET['id'])) {
    setAlert("Захиалга олдсонгүй", 'error');
    redirect('orders.php');
}

$order_id = (int)$_GET['id'];

// Захиалгын мэдээлэл татах
$sql = "SELECT o.*, u.name as user_name, u.email, t.name as template_name, t.price, t.thumbnail, t.demo_url 
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
?>

<div class="container" style="max-width: 900px; margin-bottom: 60px;">
    
    <?php showAlert(); ?>
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1>Захиалгын дэлгэрэнгүй #<?php echo $order['order_number']; ?></h1>
        <a href="orders.php" class="btn" style="background: #6b7280; color: white;">← Буцах</a>
    </div>
    
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
        
        <!-- Зүүн тал - Дэлгэрэнгүй -->
        <div>
            <!-- Захиалгын мэдээлэл -->
            <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
                <h2 style="margin-bottom: 20px;">📋 Захиалгын мэдээлэл</h2>
                
                <table style="width: 100%;">
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 12px 0; color: #6b7280;">Захиалгын дугаар:</td>
                        <td style="padding: 12px 0; text-align: right; font-weight: bold;">#<?php echo $order['order_number']; ?></td>
                    </tr>
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 12px 0; color: #6b7280;">Огноо:</td>
                        <td style="padding: 12px 0; text-align: right;"><?php echo formatDate($order['created_at']); ?></td>
                    </tr>
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 12px 0; color: #6b7280;">Төлөв:</td>
                        <td style="padding: 12px 0; text-align: right;">
                            <?php if($order['status'] == 'pending'): ?>
                                <span style="background: #fef3c7; color: #92400e; padding: 5px 15px; border-radius: 15px; font-size: 12px;">⏳ Pending</span>
                            <?php elseif($order['status'] == 'paid'): ?>
                                <span style="background: #d1fae5; color: #065f46; padding: 5px 15px; border-radius: 15px; font-size: 12px;">✅ Paid</span>
                            <?php elseif($order['status'] == 'delivered'): ?>
                                <span style="background: #dbeafe; color: #1e40af; padding: 5px 15px; border-radius: 15px; font-size: 12px;">📦 Delivered</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 12px 0; color: #6b7280;">Download Token хугацаа:</td>
                        <td style="padding: 12px 0; text-align: right;"><?php echo date('Y-m-d', strtotime($order['token_expires'])); ?></td>
                    </tr>
                </table>
            </div>
            
            <!-- Хэрэглэгчийн мэдээлэл -->
            <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
                <h2 style="margin-bottom: 20px;">👤 Хэрэглэгч</h2>
                
                <p><strong>Нэр:</strong> <?php echo htmlspecialchars($order['user_name']); ?></p>
                <p><strong>Имэйл:</strong> <a href="mailto:<?php echo $order['email']; ?>" style="color: #2563eb;"><?php echo $order['email']; ?></a></p>
            </div>
            
            <!-- Template мэдээлэл -->
            <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h2 style="margin-bottom: 20px;">📦 Template</h2>
                
                <div style="display: flex; gap: 20px; align-items: start;">
                    <img src="<?php echo $order['thumbnail'] ? SITE_URL . '/uploads/templates/' . $order['thumbnail'] : SITE_URL . '/images/placeholder.jpg'; ?>" 
                         alt="<?php echo htmlspecialchars($order['template_name']); ?>"
                         style="width: 150px; height: 100px; object-fit: cover; border-radius: 5px;">
                    
                    <div style="flex: 1;">
                        <h3 style="margin-bottom: 10px;"><?php echo htmlspecialchars($order['template_name']); ?></h3>
                        <p style="font-size: 24px; font-weight: bold; color: #2563eb; margin-bottom: 10px;">
                            <?php echo formatPrice($order['price']); ?>
                        </p>
                        
                        <?php if($order['demo_url']): ?>
                            <a href="<?php echo $order['demo_url']; ?>" target="_blank" class="btn" style="background: #6b7280; color: white; font-size: 14px; padding: 8px 15px;">
                                🔍 Demo үзэх
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Баруун тал - Үйлдлүүд -->
        <div>
            <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); position: sticky; top: 20px;">
                <h3 style="margin-bottom: 20px;">⚡ Үйлдлүүд</h3>
                
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    
                    <?php if($order['status'] == 'pending'): ?>
                        <!-- Invoice илгээх -->
                        <a href="send-invoice.php?order_id=<?php echo $order['id']; ?>" 
                           class="btn" 
                           style="background: #f59e0b; color: white; text-align: center;"
                           onclick="return confirm('Invoice илгээх үү?')">
                            📧 Invoice илгээх
                        </a>
                        
                        <!-- Paid болгох -->
                        <a href="mark-paid.php?order_id=<?php echo $order['id']; ?>" 
                           class="btn btn-success" 
                           style="text-align: center;"
                           onclick="return confirm('Төлбөр ирсэн үү? Paid болгох уу?')">
                            ✅ Paid болгох
                        </a>
                    <?php endif; ?>
                    
                    <?php if($order['status'] == 'paid'): ?>
                        <!-- Template илгээх -->
                        <a href="send-template.php?order_id=<?php echo $order['id']; ?>" 
                           class="btn btn-primary" 
                           style="text-align: center;"
                           onclick="return confirm('Template илгээх үү?')">
                            📦 Template илгээх
                        </a>
                    <?php endif; ?>
                    
                    <?php if($order['status'] == 'delivered'): ?>
                        <div style="background: #d1fae5; color: #065f46; padding: 15px; border-radius: 5px; text-align: center; font-weight: bold;">
                            ✅ Бүрэн дууссан
                        </div>
                        
                        <!-- Download линк үзүүлэх -->
                        <a href="<?php echo SITE_URL . '/user/download.php?token=' . $order['download_token']; ?>" 
                           target="_blank"
                           class="btn" 
                           style="background: #6b7280; color: white; text-align: center;">
                            🔗 Download линк
                        </a>
                    <?php endif; ?>
                    
                    <hr style="margin: 10px 0; border: none; border-top: 1px solid #e5e7eb;">
                    
                    <!-- Хэрэглэгч рүү имэйл -->
                    <a href="mailto:<?php echo $order['email']; ?>" 
                       class="btn" 
                       style="background: #6b7280; color: white; text-align: center;">
                        ✉️ Имэйл илгээх
                    </a>
                    
                    <!-- Хэрэглэгч харах -->
                    <a href="users.php?search=<?php echo urlencode($order['email']); ?>" 
                       class="btn" 
                       style="background: #6b7280; color: white; text-align: center;">
                        👤 Хэрэглэгч харах
                    </a>
                    
                </div>
            </div>
        </div>
        
    </div>
    
</div>

<?php include 'footer.php'; ?>