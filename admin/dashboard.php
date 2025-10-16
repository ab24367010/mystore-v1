<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$page_title = "Dashboard";
include 'header.php';

// Статистик - Optimized: 6 query-г 1 болгосон
$stats_sql = "
    SELECT
        (SELECT COUNT(*) FROM orders) as total_orders,
        (SELECT COUNT(*) FROM orders WHERE DATE(created_at) = CURDATE()) as today_orders,
        (SELECT COUNT(*) FROM orders WHERE status = 'pending') as pending_orders,
        (SELECT COUNT(*) FROM users) as total_users,
        (SELECT COUNT(*) FROM templates WHERE status = 'active') as total_templates,
        (SELECT COALESCE(SUM(t.price), 0) FROM orders o JOIN templates t ON o.template_id = t.id WHERE MONTH(o.created_at) = MONTH(CURDATE()) AND o.status IN ('paid', 'delivered')) as monthly_revenue
";

$stats_result = mysqli_query($conn, $stats_sql);
$stats = mysqli_fetch_assoc($stats_result);

$total_orders = $stats['total_orders'];
$today_orders = $stats['today_orders'];
$pending_orders = $stats['pending_orders'];
$total_users = $stats['total_users'];
$total_templates = $stats['total_templates'];
$monthly_revenue = $stats['monthly_revenue'];

// Сүүлийн захиалгууд
$recent_orders = mysqli_query($conn, "SELECT o.*, u.name as user_name, t.name as template_name FROM orders o JOIN users u ON o.user_id = u.id JOIN templates t ON o.template_id = t.id ORDER BY o.created_at DESC LIMIT 5");
?>

<div class="container" style="margin-bottom: 60px;">
    
    <?php showAlert(); ?>
    
    <h1 style="margin-bottom: 30px;">Dashboard</h1>
    <p style="color: #6b7280; margin-bottom: 30px;">Тавтай морил, <?php echo $_SESSION['admin_username']; ?>! 👋</p>
    
    <!-- Статистик картууд -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 40px;">
        
        <!-- Өнөөдрийн захиалга -->
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 10px;">
            <h3 style="font-size: 36px; margin-bottom: 10px;"><?php echo $today_orders; ?></h3>
            <p style="font-size: 16px; opacity: 0.9;">Өнөөдрийн захиалга</p>
        </div>
        
        <!-- Pending -->
        <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 30px; border-radius: 10px;">
            <h3 style="font-size: 36px; margin-bottom: 10px;"><?php echo $pending_orders; ?></h3>
            <p style="font-size: 16px; opacity: 0.9;">Хүлээгдэж байгаа</p>
        </div>
        
        <!-- Энэ сарын орлого -->
        <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 30px; border-radius: 10px;">
            <h3 style="font-size: 36px; margin-bottom: 10px;"><?php echo formatPrice($monthly_revenue); ?></h3>
            <p style="font-size: 16px; opacity: 0.9;">Энэ сарын орлого</p>
        </div>
        
    </div>
    
    <!-- Дэлгэрэнгүй статистик -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 40px;">
        
        <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center;">
            <div style="font-size: 32px; font-weight: bold; color: #2563eb; margin-bottom: 5px;"><?php echo $total_orders; ?></div>
            <div style="color: #6b7280;">Нийт захиалга</div>
        </div>
        
        <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center;">
            <div style="font-size: 32px; font-weight: bold; color: #10b981; margin-bottom: 5px;"><?php echo $total_users; ?></div>
            <div style="color: #6b7280;">Нийт хэрэглэгч</div>
        </div>
        
        <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center;">
            <div style="font-size: 32px; font-weight: bold; color: #f59e0b; margin-bottom: 5px;"><?php echo $total_templates; ?></div>
            <div style="color: #6b7280;">Нийт Template</div>
        </div>
        
    </div>
    
    <!-- Сүүлийн захиалгууд -->
    <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <h2 style="margin-bottom: 20px;">Сүүлийн захиалгууд</h2>
        
        <?php if(mysqli_num_rows($recent_orders) > 0): ?>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #e5e7eb;">
                        <th style="padding: 12px; text-align: left;">Захиалга</th>
                        <th style="padding: 12px; text-align: left;">Хэрэглэгч</th>
                        <th style="padding: 12px; text-align: left;">Template</th>
                        <th style="padding: 12px; text-align: center;">Төлөв</th>
                        <th style="padding: 12px; text-align: center;">Үйлдэл</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($order = mysqli_fetch_assoc($recent_orders)): ?>
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 12px;">#<?php echo $order['order_number']; ?></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($order['user_name']); ?></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($order['template_name']); ?></td>
                            <td style="padding: 12px; text-align: center;">
                                <?php if($order['status'] == 'pending'): ?>
                                    <span style="background: #fef3c7; color: #92400e; padding: 5px 10px; border-radius: 15px; font-size: 12px;">Pending</span>
                                <?php elseif($order['status'] == 'paid'): ?>
                                    <span style="background: #d1fae5; color: #065f46; padding: 5px 10px; border-radius: 15px; font-size: 12px;">Paid</span>
                                <?php elseif($order['status'] == 'delivered'): ?>
                                    <span style="background: #dbeafe; color: #1e40af; padding: 5px 10px; border-radius: 15px; font-size: 12px;">Delivered</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 12px; text-align: center;">
                                <a href="orders.php" class="btn btn-primary" style="font-size: 12px; padding: 6px 12px;">Харах</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            
            <div style="text-align: center; margin-top: 20px;">
                <a href="orders.php" class="btn btn-primary">Бүх захиалга харах →</a>
            </div>
        <?php else: ?>
            <p style="text-align: center; color: #6b7280; padding: 40px 0;">Захиалга байхгүй байна</p>
        <?php endif; ?>
    </div>
    
</div>

<?php include 'footer.php'; ?>