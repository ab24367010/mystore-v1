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
                <div style="position: relative; overflow: hidden;">
                    <img src="<?php echo $template['thumbnail'] ? SITE_URL . '/uploads/templates/' . $template['thumbnail'] : SITE_URL . '/images/placeholder.jpg'; ?>"
                         alt="<?php echo htmlspecialchars($template['name']); ?>"
                         loading="lazy">

                    <!-- Watermark -->
                    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; display: flex; align-items: center; justify-content: center; pointer-events: none;">
                        <div style="font-size: 20px; font-weight: bold; color: rgba(37, 99, 235, 0.2); transform: rotate(-45deg); user-select: none; white-space: nowrap;">
                            PREVIEW
                        </div>
                    </div>
                </div>
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