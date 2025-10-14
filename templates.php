<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Хайлт болон шүүлтүүр
$search = isset($_GET['search']) ? clean($_GET['search']) : '';
$category = isset($_GET['category']) ? clean($_GET['category']) : '';

// SQL query бүтээх
$sql = "SELECT * FROM templates WHERE status = 'active'";

// Хайлт нэмэх
if(!empty($search)) {
    $sql .= " AND (name LIKE '%$search%' OR description LIKE '%$search%')";
}

// Category шүүлтүүр
if(!empty($category)) {
    $sql .= " AND category = '$category'";
}

$sql .= " ORDER BY created_at DESC";

$result = mysqli_query($conn, $sql);

// Category жагсаалт авах
$category_sql = "SELECT DISTINCT category FROM templates WHERE status = 'active' AND category IS NOT NULL";
$categories = mysqli_query($conn, $category_sql);

$page_title = "Template-үүд";
include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container" style="margin-top: 40px; margin-bottom: 60px;">
    
    <h1 style="margin-bottom: 30px; text-align: center;">Бүх Template-үүд</h1>
    
    <!-- Хайлт болон шүүлтүүр -->
    <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 40px;">
        <form method="GET" action="" style="display: flex; gap: 15px; align-items: end;">
            
            <!-- Хайлтын талбар -->
            <div class="form-group" style="flex: 1; margin: 0;">
                <label>Хайх</label>
                <input type="text" name="search" placeholder="Template хайх..." 
                       value="<?php echo htmlspecialchars($search); ?>">
            </div>
            
            <!-- Category шүүлтүүр -->
            <div class="form-group" style="flex: 1; margin: 0;">
                <label>Ангилал</label>
                <select name="category" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 5px;">
                    <option value="">Бүгд</option>
                    <?php while($cat = mysqli_fetch_assoc($categories)): ?>
                        <option value="<?php echo $cat['category']; ?>" 
                                <?php echo ($category == $cat['category']) ? 'selected' : ''; ?>>
                            <?php echo $cat['category']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <!-- Товчнууд -->
            <button type="submit" class="btn btn-primary">Хайх</button>
            <a href="templates.php" class="btn" style="background: #6b7280; color: white;">Цэвэрлэх</a>
            
        </form>
    </div>
    
    <!-- Template Grid -->
    <?php if(mysqli_num_rows($result) > 0): ?>
        
        <div class="templates-grid">
            <?php while($template = mysqli_fetch_assoc($result)): ?>
                
                <div class="template-card">
                    <div style="position: relative; overflow: hidden;">
                        <img src="<?php echo $template['thumbnail'] ? SITE_URL . '/uploads/templates/' . $template['thumbnail'] : SITE_URL . '/images/placeholder.jpg'; ?>"
                             alt="<?php echo htmlspecialchars($template['name']); ?>">

                        <!-- Watermark -->
                        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; display: flex; align-items: center; justify-content: center; pointer-events: none;">
                            <div style="font-size: 20px; font-weight: bold; color: rgba(37, 99, 235, 0.2); transform: rotate(-45deg); user-select: none; white-space: nowrap;">
                                PREVIEW
                            </div>
                        </div>
                    </div>

                    <div class="template-card-content">
                        <h3><?php echo htmlspecialchars($template['name']); ?></h3>
                        
                        <?php if($template['category']): ?>
                            <span style="display: inline-block; background: #e5e7eb; color: #374151; padding: 5px 10px; border-radius: 5px; font-size: 12px; margin-bottom: 10px;">
                                <?php echo $template['category']; ?>
                            </span>
                        <?php endif; ?>
                        
                        <p><?php echo substr($template['description'], 0, 100); ?>...</p>
                        
                        <div class="price"><?php echo formatPrice($template['price']); ?></div>
                        
                        <a href="template-detail.php?id=<?php echo $template['id']; ?>" class="btn btn-primary" style="width: 100%;">
                            Дэлгэрэнгүй үзэх
                        </a>
                    </div>
                </div>
                
            <?php endwhile; ?>
        </div>
        
    <?php else: ?>
        
        <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 10px;">
            <h2 style="color: #6b7280; margin-bottom: 20px;">😔 Template олдсонгүй</h2>
            <p style="color: #9ca3af; margin-bottom: 20px;">Таны хайлтад тохирох template байхгүй байна</p>
            <a href="templates.php" class="btn btn-primary">Бүх template-үүд үзэх</a>
        </div>
        
    <?php endif; ?>
    
</div>

<?php include 'includes/footer.php'; ?>