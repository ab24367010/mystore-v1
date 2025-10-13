<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$page_title = "Хэрэглэгчид";
include 'header.php';

// Хайлт
$search = isset($_GET['search']) ? clean($_GET['search']) : '';

// SQL query
$sql = "SELECT u.*, 
        (SELECT COUNT(*) FROM orders WHERE user_id = u.id) as total_orders,
        (SELECT COUNT(*) FROM orders WHERE user_id = u.id AND status IN ('paid', 'delivered')) as paid_orders
        FROM users u 
        WHERE 1=1";

if(!empty($search)) {
    $sql .= " AND (u.name LIKE '%$search%' OR u.email LIKE '%$search%')";
}

$sql .= " ORDER BY u.created_at DESC";

$result = mysqli_query($conn, $sql);
?>

<div class="container" style="margin-bottom: 60px;">
    
    <?php showAlert(); ?>
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1>👥 Хэрэглэгчид</h1>
        <a href="dashboard.php" class="btn" style="background: #6b7280; color: white;">← Dashboard</a>
    </div>
    
    <!-- Хайлт -->
    <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px;">
        <form method="GET" action="" style="display: flex; gap: 15px; align-items: end;">
            <div class="form-group" style="flex: 1; margin: 0;">
                <label>Хайх</label>
                <input type="text" name="search" placeholder="Нэр, имэйл хайх..."
                       value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Хайх</button>
            <a href="users.php" class="btn" style="background: #6b7280; color: white;">Цэвэрлэх</a>
        </form>
    </div>
    
    <!-- Хэрэглэгчийн хүснэгт -->
    <?php if(mysqli_num_rows($result) > 0): ?>
        
        <div style="background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f3f4f6;">
                        <th style="padding: 15px; text-align: left; border-bottom: 2px solid #e5e7eb;">ID</th>
                        <th style="padding: 15px; text-align: left; border-bottom: 2px solid #e5e7eb;">Нэр</th>
                        <th style="padding: 15px; text-align: left; border-bottom: 2px solid #e5e7eb;">Имэйл</th>
                        <th style="padding: 15px; text-align: center; border-bottom: 2px solid #e5e7eb;">Захиалга</th>
                        <th style="padding: 15px; text-align: center; border-bottom: 2px solid #e5e7eb;">Төлсөн</th>
                        <th style="padding: 15px; text-align: left; border-bottom: 2px solid #e5e7eb;">Бүртгүүлсэн</th>
                        <th style="padding: 15px; text-align: center; border-bottom: 2px solid #e5e7eb;">Үйлдэл</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($user = mysqli_fetch_assoc($result)): ?>
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            
                            <!-- ID -->
                            <td style="padding: 15px; color: #6b7280;"><?php echo $user['id']; ?></td>
                            
                            <!-- Нэр -->
                            <td style="padding: 15px;">
                                <strong><?php echo htmlspecialchars($user['name']); ?></strong>
                            </td>
                            
                            <!-- Имэйл -->
                            <td style="padding: 15px;">
                                <a href="mailto:<?php echo $user['email']; ?>" style="color: #2563eb;">
                                    <?php echo htmlspecialchars($user['email']); ?>
                                </a>
                            </td>
                            
                            <!-- Нийт захиалга -->
                            <td style="padding: 15px; text-align: center;">
                                <span style="background: #e5e7eb; color: #374151; padding: 5px 12px; border-radius: 15px; font-size: 14px; font-weight: bold;">
                                    <?php echo $user['total_orders']; ?>
                                </span>
                            </td>
                            
                            <!-- Төлсөн захиалга -->
                            <td style="padding: 15px; text-align: center;">
                                <span style="background: #d1fae5; color: #065f46; padding: 5px 12px; border-radius: 15px; font-size: 14px; font-weight: bold;">
                                    <?php echo $user['paid_orders']; ?>
                                </span>
                            </td>
                            
                            <!-- Бүртгүүлсэн огноо -->
                            <td style="padding: 15px; color: #6b7280; font-size: 14px;">
                                <?php echo date('Y-m-d', strtotime($user['created_at'])); ?>
                            </td>
                            
                            <!-- Үйлдэл -->
                            <td style="padding: 15px; text-align: center;">
                                <a href="orders.php?search=<?php echo urlencode($user['email']); ?>" 
                                   class="btn btn-primary" 
                                   style="font-size: 12px; padding: 6px 12px;">
                                    📋 Захиалгууд
                                </a>
                            </td>
                            
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        
    <?php else: ?>
        
        <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 10px;">
            <div style="font-size: 80px; margin-bottom: 20px;">👥</div>
            <h2 style="color: #6b7280; margin-bottom: 20px;">Хэрэглэгч олдсонгүй</h2>
            <p style="color: #9ca3af;">Хайлтын нөхцөлд тохирох хэрэглэгч байхгүй байна</p>
        </div>
        
    <?php endif; ?>
    
</div>

<?php include 'footer.php'; ?>