<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$page_title = "Захиалгууд";
include 'header.php';

// Шүүлтүүр
$status_filter = isset($_GET['status']) ? clean($_GET['status']) : '';
$search = isset($_GET['search']) ? clean($_GET['search']) : '';

// SQL query
$sql = "SELECT o.*, u.name as user_name, u.email, t.name as template_name, t.price 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        JOIN templates t ON o.template_id = t.id 
        WHERE 1=1";

// Төлөв шүүлтүүр
if(!empty($status_filter)) {
    $sql .= " AND o.status = '$status_filter'";
}

// Хайлт
if(!empty($search)) {
    $sql .= " AND (o.order_number LIKE '%$search%' OR u.name LIKE '%$search%' OR u.email LIKE '%$search%')";
}

$sql .= " ORDER BY o.created_at DESC";

$result = mysqli_query($conn, $sql);
?>

<div class="container" style="margin-bottom: 60px;">
    
    <?php showAlert(); ?>
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1>📋 Захиалгууд</h1>
        <a href="dashboard.php" class="btn" style="background: #6b7280; color: white;">← Dashboard</a>
    </div>
    
    <!-- Шүүлтүүр -->
    <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px;">
        <form method="GET" action="" style="display: flex; gap: 15px; align-items: end;">
            
            <!-- Хайлт -->
            <div class="form-group" style="flex: 1; margin: 0;">
                <label>Хайх</label>
                <input type="text" name="search" placeholder="Захиалгын дугаар, нэр, имэйл..."
                       value="<?php echo htmlspecialchars($search); ?>">
            </div>
            
            <!-- Төлөв шүүлтүүр -->
            <div class="form-group" style="margin: 0;">
                <label>Төлөв</label>
                <select name="status" style="width: 200px; padding: 10px; border: 1px solid #d1d5db; border-radius: 5px;">
                    <option value="">Бүгд</option>
                    <option value="pending" <?php echo ($status_filter == 'pending') ? 'selected' : ''; ?>>Хүлээгдэж байгаа</option>
                    <option value="paid" <?php echo ($status_filter == 'paid') ? 'selected' : ''; ?>>Төлсөн</option>
                    <option value="delivered" <?php echo ($status_filter == 'delivered') ? 'selected' : ''; ?>>Хүргэгдсэн</option>
                    <option value="cancelled" <?php echo ($status_filter == 'cancelled') ? 'selected' : ''; ?>>Цуцлагдсан</option>
                </select>
            </div>
            
            <!-- Товчнууд -->
            <button type="submit" class="btn btn-primary">Хайх</button>
            <a href="orders.php" class="btn" style="background: #6b7280; color: white;">Цэвэрлэх</a>
            
        </form>
    </div>
    
    <!-- Захиалгын хүснэгт -->
    <?php if(mysqli_num_rows($result) > 0): ?>
        
        <div style="background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f3f4f6;">
                        <th style="padding: 15px; text-align: left; border-bottom: 2px solid #e5e7eb;">ID</th>
                        <th style="padding: 15px; text-align: left; border-bottom: 2px solid #e5e7eb;">Захиалга</th>
                        <th style="padding: 15px; text-align: left; border-bottom: 2px solid #e5e7eb;">Огноо</th>
                        <th style="padding: 15px; text-align: left; border-bottom: 2px solid #e5e7eb;">Хэрэглэгч</th>
                        <th style="padding: 15px; text-align: left; border-bottom: 2px solid #e5e7eb;">Template</th>
                        <th style="padding: 15px; text-align: center; border-bottom: 2px solid #e5e7eb;">Үнэ</th>
                        <th style="padding: 15px; text-align: center; border-bottom: 2px solid #e5e7eb;">Төлөв</th>
                        <th style="padding: 15px; text-align: center; border-bottom: 2px solid #e5e7eb;">Үйлдэл</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($order = mysqli_fetch_assoc($result)): ?>
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            
                            <!-- ID -->
                            <td style="padding: 15px; color: #6b7280;"><?php echo $order['id']; ?></td>
                            
                            <!-- Захиалгын дугаар -->
                            <td style="padding: 15px;">
                                <strong style="color: #2563eb;">#<?php echo $order['order_number']; ?></strong>
                            </td>
                            
                            <!-- Огноо -->
                            <td style="padding: 15px; color: #6b7280; font-size: 14px;">
                                <?php echo formatDate($order['created_at']); ?>
                            </td>
                            
                            <!-- Хэрэглэгч -->
                            <td style="padding: 15px;">
                                <div><?php echo htmlspecialchars($order['user_name']); ?></div>
                                <div style="color: #6b7280; font-size: 12px;"><?php echo htmlspecialchars($order['email']); ?></div>
                            </td>
                            
                            <!-- Template -->
                            <td style="padding: 15px;">
                                <?php echo htmlspecialchars($order['template_name']); ?>
                            </td>
                            
                            <!-- Үнэ -->
                            <td style="padding: 15px; text-align: center; font-weight: bold;">
                                <?php echo formatPrice($order['price']); ?>
                            </td>
                            
                            <!-- Төлөв -->
                            <td style="padding: 15px; text-align: center;">
                                <?php if($order['status'] == 'pending'): ?>
                                    <span style="background: #fef3c7; color: #92400e; padding: 5px 15px; border-radius: 15px; font-size: 12px;">
                                        ⏳ Pending
                                    </span>
                                <?php elseif($order['status'] == 'paid'): ?>
                                    <span style="background: #d1fae5; color: #065f46; padding: 5px 15px; border-radius: 15px; font-size: 12px;">
                                        ✅ Paid
                                    </span>
                                <?php elseif($order['status'] == 'delivered'): ?>
                                    <span style="background: #dbeafe; color: #1e40af; padding: 5px 15px; border-radius: 15px; font-size: 12px;">
                                        📦 Delivered
                                    </span>
                                <?php elseif($order['status'] == 'cancelled'): ?>
                                    <span style="background: #fee2e2; color: #991b1b; padding: 5px 15px; border-radius: 15px; font-size: 12px;">
                                        ❌ Cancelled
                                    </span>
                                <?php endif; ?>
                            </td>
                            
                            <!-- Үйлдэл -->
                            <td style="padding: 15px;">
                                <div style="display: flex; flex-direction: column; gap: 5px;">
                                    
                                    <?php if($order['status'] == 'pending'): ?>
                                        <!-- Invoice илгээх -->
                                        <a href="send-invoice.php?order_id=<?php echo $order['id']; ?>" 
                                           class="btn" 
                                           style="background: #f59e0b; color: white; font-size: 12px; padding: 6px 12px; text-align: center;"
                                           onclick="return confirm('Invoice илгээх үү?')">
                                            📧 Invoice
                                        </a>
                                        
                                        <!-- Paid болгох -->
                                        <a href="mark-paid.php?order_id=<?php echo $order['id']; ?>" 
                                           class="btn btn-success" 
                                           style="font-size: 12px; padding: 6px 12px; text-align: center;"
                                           onclick="return confirm('Төлбөр ирсэн үү? Paid болгох уу?')">
                                            ✅ Paid болго
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if($order['status'] == 'paid'): ?>
                                        <!-- Template илгээх -->
                                        <a href="send-template.php?order_id=<?php echo $order['id']; ?>" 
                                           class="btn btn-primary" 
                                           style="font-size: 12px; padding: 6px 12px; text-align: center;"
                                           onclick="return confirm('Template илгээх үү?')">
                                            📦 Template илгээх
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if($order['status'] == 'delivered'): ?>
                                        <span style="color: #10b981; font-size: 12px; text-align: center;">✓ Бүрэн дууссан</span>
                                    <?php endif; ?>
                                    
                                    <!-- Дэлгэрэнгүй -->
                                    <a href="order-detail.php?id=<?php echo $order['id']; ?>" 
                                       class="btn" 
                                       style="background: #6b7280; color: white; font-size: 12px; padding: 6px 12px; text-align: center;">
                                        👁️ Дэлгэрэнгүй
                                    </a>
                                    
                                </div>
                            </td>
                            
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        
    <?php else: ?>
        
        <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 10px;">
            <div style="font-size: 80px; margin-bottom: 20px;">📭</div>
            <h2 style="color: #6b7280; margin-bottom: 20px;">Захиалга олдсонгүй</h2>
            <p style="color: #9ca3af;">Хайлтын нөхцөлд тохирох захиалга байхгүй байна</p>
        </div>
        
    <?php endif; ?>
    
</div>

<?php include 'footer.php'; ?>