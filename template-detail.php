<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Template ID авах
if(!isset($_GET['id']) || empty($_GET['id'])) {
    redirect('templates.php');
}

$template_id = (int)$_GET['id'];

// Template мэдээлэл татах
$sql = "SELECT * FROM templates WHERE id = ? AND status = 'active'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $template_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0) {
    redirect('templates.php');
}

$template = $result->fetch_assoc();

// Screenshot-ууд татах
$screenshot_sql = "SELECT * FROM template_screenshots WHERE template_id = ? ORDER BY display_order ASC";
$stmt = $conn->prepare($screenshot_sql);
$stmt->bind_param("i", $template_id);
$stmt->execute();
$screenshots = $stmt->get_result();

$page_title = $template['name'];
include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container" style="margin-top: 40px; margin-bottom: 60px;">
    
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 40px;">
        
        <!-- Зүүн тал - Зургууд -->
        <div>
            <!-- Гол зураг -->
            <div style="background: #f3f4f6; border-radius: 10px; overflow: hidden; margin-bottom: 20px;">
                <img id="mainImage" 
                     src="<?php echo $template['thumbnail'] ? SITE_URL . '/uploads/templates/' . $template['thumbnail'] : SITE_URL . '/images/placeholder.jpg'; ?>" 
                     alt="<?php echo htmlspecialchars($template['name']); ?>"
                     style="width: 100%; height: 400px; object-fit: cover;">
            </div>
            
            <!-- Screenshot thumbnail-ууд -->
            <?php if(mysqli_num_rows($screenshots) > 0): ?>
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px;">
                    
                    <!-- Эхний зураг (thumbnail) -->
                    <img src="<?php echo SITE_URL . '/uploads/templates/' . $template['thumbnail']; ?>" 
                         alt="Thumbnail"
                         onclick="changeImage(this.src)"
                         style="width: 100%; height: 80px; object-fit: cover; border-radius: 5px; cursor: pointer; border: 2px solid transparent; transition: border 0.3s;"
                         onmouseover="this.style.borderColor='#2563eb'"
                         onmouseout="this.style.borderColor='transparent'">
                    
                    <!-- Бусад screenshot-ууд -->
                    <?php while($screenshot = mysqli_fetch_assoc($screenshots)): ?>
                        <img src="<?php echo SITE_URL . '/uploads/templates/' . $screenshot['image_path']; ?>" 
                             alt="Screenshot"
                             onclick="changeImage(this.src)"
                             style="width: 100%; height: 80px; object-fit: cover; border-radius: 5px; cursor: pointer; border: 2px solid transparent; transition: border 0.3s;"
                             onmouseover="this.style.borderColor='#2563eb'"
                             onmouseout="this.style.borderColor='transparent'">
                    <?php endwhile; ?>
                    
                </div>
            <?php endif; ?>
            
            <!-- Дэлгэрэнгүй тайлбар -->
            <div style="margin-top: 40px;">
                <h2 style="margin-bottom: 20px;">Дэлгэрэнгүй мэдээлэл</h2>
                <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); line-height: 1.8;">
                    <?php echo nl2br(htmlspecialchars($template['description'])); ?>
                </div>
            </div>
        </div>
        
        <!-- Баруун тал - Мэдээлэл ба товчнууд -->
        <div>
            <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); position: sticky; top: 20px;">
                
                <h1 style="margin-bottom: 15px;"><?php echo htmlspecialchars($template['name']); ?></h1>
                
                <?php if($template['category']): ?>
                    <span style="display: inline-block; background: #e5e7eb; color: #374151; padding: 5px 15px; border-radius: 5px; font-size: 14px; margin-bottom: 20px;">
                        <?php echo $template['category']; ?>
                    </span>
                <?php endif; ?>
                
                <div class="price" style="font-size: 36px; margin: 20px 0;">
                    <?php echo formatPrice($template['price']); ?>
                </div>
                
                <hr style="margin: 20px 0; border: none; border-top: 1px solid #e5e7eb;">
                
                <!-- Demo товч -->
                <?php if($template['demo_url']): ?>
                    <a href="<?php echo $template['demo_url']; ?>" target="_blank" class="btn" 
                       style="width: 100%; margin-bottom: 15px; background: #6b7280; color: white; text-align: center;">
                        🔍 Live Demo үзэх
                    </a>
                <?php endif; ?>
                
                <!-- Худалдаж авах товч -->
                <?php if(isLoggedIn()): ?>
                    <!-- Нэвтэрсэн бол шууд checkout руу -->
                    <a href="checkout.php?template_id=<?php echo $template['id']; ?>" class="btn btn-primary" 
                       style="width: 100%; font-size: 18px; text-align: center;">
                        💳 Худалдаж авах
                    </a>
                <?php else: ?>
                    <!-- Нэвтрээгүй бол modal харуулах -->
                    <button onclick="showLoginModal()" class="btn btn-primary" 
                            style="width: 100%; font-size: 18px;">
                        💳 Худалдаж авах
                    </button>
                <?php endif; ?>
                
                <hr style="margin: 20px 0; border: none; border-top: 1px solid #e5e7eb;">
                
                <div style="color: #6b7280; font-size: 14px;">
                    <p style="margin-bottom: 10px;">✅ Нэг удаа төлбөр</p>
                    <p style="margin-bottom: 10px;">✅ Хязгааргүй татах</p>
                    <p style="margin-bottom: 10px;">✅ Бүх файл орно</p>
                    <p style="margin-bottom: 10px;">✅ Documentation</p>
                </div>
                
            </div>
        </div>
        
    </div>
    
</div>

<!-- Login Modal -->
<div id="loginModal" class="modal">
    <div class="modal-content">
        <h2 style="margin-bottom: 15px;">Үргэлжлүүлэхийн тулд нэвтэрнэ үү</h2>
        <p style="color: #6b7280; margin-bottom: 25px;">
            Худалдаж авахын тулд эхлээд нэвтрэх эсвэл бүртгүүлэх хэрэгтэй
        </p>
        
        <a href="login.php?redirect=checkout.php?template_id=<?php echo $template['id']; ?>" class="btn btn-success" style="width: 100%; margin-bottom: 10px;">
            🔑 Нэвтрэх
        </a>
        
        <a href="register.php?redirect=checkout.php?template_id=<?php echo $template['id']; ?>" class="btn btn-primary" style="width: 100%; margin-bottom: 15px;">
            ✍️ Бүртгүүлэх
        </a>
        
        <button onclick="closeLoginModal()" class="close-modal">
            Болих
        </button>
    </div>
</div>

<script>
// Зураг солих
function changeImage(src) {
    document.getElementById('mainImage').src = src;
}

// Login modal харуулах
function showLoginModal() {
    document.getElementById('loginModal').style.display = 'block';
}

// Modal хаах
function closeLoginModal() {
    document.getElementById('loginModal').style.display = 'none';
}

// Modal гадна дарахад хаах
window.onclick = function(event) {
    var modal = document.getElementById('loginModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
</script>

<?php include 'includes/footer.php'; ?>