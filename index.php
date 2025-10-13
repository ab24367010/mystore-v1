<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$page_title = "Нүүр хуудас";

include 'includes/header.php';
include 'includes/navbar.php';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <h1>Мэргэжлийн Website Template-үүд</h1>
        <p>Таны бизнест тохирсон өндөр чанартай загвар</p>
        <a href="templates.php" class="btn btn-white">Template-үүд үзэх</a>
    </div>
</section>

<!-- Templates Section -->
<section class="templates-section">
    <div class="container">
        <h2 class="section-title">Шилдэг Template-үүд</h2>
        
        <div class="templates-grid">
            <?php
            // Идэвхтэй template-үүдийг татах (багадаа 6)
            $sql = "SELECT * FROM templates WHERE status = 'active' ORDER BY created_at DESC LIMIT 6";
            $result = mysqli_query($conn, $sql);
            
            if(mysqli_num_rows($result) > 0):
                while($template = mysqli_fetch_assoc($result)):
            ?>
            
            <div class="template-card">
                <img src="<?php echo $template['thumbnail'] ? SITE_URL . '/uploads/templates/' . $template['thumbnail'] : SITE_URL . '/images/placeholder.jpg'; ?>" 
                     alt="<?php echo $template['name']; ?>">
                <div class="template-card-content">
                    <h3><?php echo $template['name']; ?></h3>
                    <p><?php echo substr($template['description'], 0, 100); ?>...</p>
                    <div class="price"><?php echo formatPrice($template['price']); ?></div>
                    <a href="template-detail.php?id=<?php echo $template['id']; ?>" class="btn btn-primary">Дэлгэрэнгүй</a>
                </div>
            </div>
            
            <?php 
                endwhile;
            else:
            ?>
            
            <p>Template байхгүй байна.</p>
            
            <?php endif; ?>
        </div>
        
        <div style="text-align: center; margin-top: 40px;">
            <a href="templates.php" class="btn btn-primary">Бүх Template-үүд үзэх</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>