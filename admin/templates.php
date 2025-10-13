<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$page_title = "Template-үүд";
include 'header.php';

// Бүх template-үүд
$sql = "SELECT * FROM templates ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<div class="container" style="margin-bottom: 60px;">
    
    <?php showAlert(); ?>
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1>📦 Template-үүд</h1>
        <a href="template-add.php" class="btn btn-success">+ Шинэ нэмэх</a>
    </div>
    
    <!-- Template хүснэгт -->
    <?php if(mysqli_num_rows($result) > 0): ?>
        
        <div style="background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f3f4f6;">
                        <th style="padding: 15px; text-align: left; border-bottom: 2px solid #e5e7eb;">ID</th>
                        <th style="padding: 15px; text-align: left; border-bottom: 2px solid #e5e7eb;">Зураг</th>
                        <th style="padding: 15px; text-align: left; border-bottom: 2px solid #e5e7eb;">Нэр</th>
                        <th style="padding: 15px; text-align: left; border-bottom: 2px solid #e5e7eb;">Category</th>
                        <th style="padding: 15px; text-align: center; border-bottom: 2px solid #e5e7eb;">Үнэ</th>
                        <th style="padding: 15px; text-align: center; border-bottom: 2px solid #e5e7eb;">Төлөв</th>
                        <th style="padding: 15px; text-align: center; border-bottom: 2px solid #e5e7eb;">Үйлдэл</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($template = mysqli_fetch_assoc($result)): ?>
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            
                            <!-- ID -->
                            <td style="padding: 15px; color: #6b7280;"><?php echo $template['id']; ?></td>
                            
                            <!-- Зураг -->
                            <td style="padding: 15px;">
                                <img src="<?php echo $template['thumbnail'] ? SITE_URL . '/uploads/templates/' . $template['thumbnail'] : SITE_URL . '/images/placeholder.jpg'; ?>" 
                                     alt="<?php echo htmlspecialchars($template['name']); ?>"
                                     style="width: 80px; height: 50px; object-fit: cover; border-radius: 5px;">
                            </td>
                            
                            <!-- Нэр -->
                            <td style="padding: 15px;">
                                <strong><?php echo htmlspecialchars($template['name']); ?></strong>
                                <div style="font-size: 12px; color: #6b7280; margin-top: 5px;">
                                    <?php echo date('Y-m-d', strtotime($template['created_at'])); ?>
                                </div>
                            </td>
                            
                            <!-- Category -->
                            <td style="padding: 15px;">
                                <?php if($template['category']): ?>
                                    <span style="background: #e5e7eb; color: #374151; padding: 5px 10px; border-radius: 5px; font-size: 12px;">
                                        <?php echo $template['category']; ?>
                                    </span>
                                <?php else: ?>
                                    <span style="color: #9ca3af; font-size: 12px;">-</span>
                                <?php endif; ?>
                            </td>
                            
                            <!-- Үнэ -->
                            <td style="padding: 15px; text-align: center; font-weight: bold;">
                                <?php echo formatPrice($template['price']); ?>
                            </td>
                            
                            <!-- Төлөв -->
                            <td style="padding: 15px; text-align: center;">
                                <?php if($template['status'] == 'active'): ?>
                                    <span style="background: #d1fae5; color: #065f46; padding: 5px 15px; border-radius: 15px; font-size: 12px;">
                                        ✅ Идэвхтэй
                                    </span>
                                <?php else: ?>
                                    <span style="background: #fee2e2; color: #991b1b; padding: 5px 15px; border-radius: 15px; font-size: 12px;">
                                        ❌ Идэвхгүй
                                    </span>
                                <?php endif; ?>
                            </td>
                            
                            <!-- Үйлдэл -->
                            <td style="padding: 15px;">
                                <div style="display: flex; gap: 5px; justify-content: center;">
                                    
                                    <!-- Үзэх -->
                                    <a href="<?php echo SITE_URL . '/template-detail.php?id=' . $template['id']; ?>" 
                                       target="_blank"
                                       class="btn" 
                                       style="background: #6b7280; color: white; font-size: 12px; padding: 6px 12px;"
                                       title="Үзэх">
                                        👁️
                                    </a>
                                    
                                    <!-- Засах -->
                                    <a href="template-edit.php?id=<?php echo $template['id']; ?>" 
                                       class="btn btn-primary" 
                                       style="font-size: 12px; padding: 6px 12px;"
                                       title="Засах">
                                        ✏️
                                    </a>
                                    
                                    <!-- Устгах -->
                                    <a href="template-delete.php?id=<?php echo $template['id']; ?>" 
                                       class="btn" 
                                       style="background: #ef4444; color: white; font-size: 12px; padding: 6px 12px;"
                                       onclick="return confirm('Устгах уу?')"
                                       title="Устгах">
                                        🗑️
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
            <div style="font-size: 80px; margin-bottom: 20px;">📦</div>
            <h2 style="color: #6b7280; margin-bottom: 20px;">Template байхгүй байна</h2>
            <a href="template-add.php" class="btn btn-success">+ Эхний template нэмэх</a>
        </div>
        
    <?php endif; ?>
    
</div>

<?php include 'footer.php'; ?>