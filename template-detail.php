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

// Хэрэглэгч энэ template-ийг худалдаж авсан эсэхийг шалгах
$is_purchased = false;
if(isLoggedIn()) {
    $user_id = $_SESSION['user_id'];
    $purchase_sql = "SELECT * FROM orders WHERE user_id = ? AND template_id = ? AND status = 'paid'";
    $purchase_stmt = $conn->prepare($purchase_sql);
    $purchase_stmt->bind_param("ii", $user_id, $template_id);
    $purchase_stmt->execute();
    $purchase_result = $purchase_stmt->get_result();
    $is_purchased = ($purchase_result->num_rows > 0);
}

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
            <div style="background: #f3f4f6; border-radius: 10px; overflow: hidden; margin-bottom: 20px; position: relative; cursor: pointer;" onclick="openLightbox(0)">
                <img id="mainImage"
                     src="<?php echo $template['thumbnail'] ? SITE_URL . '/uploads/templates/' . $template['thumbnail'] : SITE_URL . '/images/placeholder.jpg'; ?>"
                     alt="<?php echo htmlspecialchars($template['name']); ?>"
                     style="width: 100%; height: 600px; object-fit: contain; background: #f9fafb;">

                <!-- Watermark (зөвхөн худалдаж аваагүй үед харагдана) -->
                <?php if(!$is_purchased): ?>
                <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; pointer-events: none; display: flex; align-items: center; justify-content: center;">
                    <div style="font-size: 48px; font-weight: bold; color: rgba(37, 99, 235, 0.15); transform: rotate(-45deg); text-align: center; user-select: none; white-space: nowrap;">
                        <?php echo SITE_NAME; ?><br>
                        <span style="font-size: 32px;">PREVIEW</span>
                    </div>
                </div>
                <?php endif; ?>

                <div style="position: absolute; bottom: 15px; right: 15px; background: rgba(0,0,0,0.7); color: white; padding: 8px 15px; border-radius: 5px; font-size: 14px;">
                    🔍 Томруулж үзэх
                </div>
            </div>

            <!-- Screenshot thumbnail-ууд -->
            <?php
            $screenshots_array = [];
            while($row = mysqli_fetch_assoc($screenshots)) {
                $screenshots_array[] = $row;
            }
            ?>

            <?php if(count($screenshots_array) > 0): ?>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 10px;">

                    <!-- Эхний зураг (thumbnail) -->
                    <div style="position: relative; cursor: pointer;" onclick="changeImage('<?php echo SITE_URL . '/uploads/templates/' . $template['thumbnail']; ?>', 0)">
                        <img src="<?php echo SITE_URL . '/uploads/templates/' . $template['thumbnail']; ?>"
                             alt="Thumbnail"
                             class="thumbnail-img"
                             style="width: 100%; height: 100px; object-fit: cover; border-radius: 5px; border: 3px solid #2563eb; transition: all 0.3s;">
                    </div>

                    <!-- Бусад screenshot-ууд -->
                    <?php foreach($screenshots_array as $index => $screenshot): ?>
                        <div style="position: relative; cursor: pointer;" onclick="changeImage('<?php echo SITE_URL . '/uploads/templates/' . $screenshot['image_path']; ?>', <?php echo $index + 1; ?>)">
                            <img src="<?php echo SITE_URL . '/uploads/templates/' . $screenshot['image_path']; ?>"
                                 alt="Screenshot <?php echo $index + 1; ?>"
                                 class="thumbnail-img"
                                 style="width: 100%; height: 100px; object-fit: cover; border-radius: 5px; border: 3px solid transparent; transition: all 0.3s;">
                        </div>
                    <?php endforeach; ?>

                </div>

                <p style="text-align: center; color: #6b7280; margin-top: 15px; font-size: 14px;">
                    📸 Зургийг дарж дэлгэрэнгүй үзнэ үү (<?php echo count($screenshots_array) + 1; ?> зураг)
                </p>
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

                <!-- Худалдаж авсан эсэх мэдээлэл -->
                <?php if($is_purchased): ?>
                    <div style="background: #d1fae5; border: 2px solid #10b981; padding: 15px; border-radius: 8px; margin-bottom: 15px; text-align: center;">
                        <p style="color: #065f46; font-weight: bold; margin: 0;">✅ Та энэ template-ийг худалдаж авсан байна</p>
                        <p style="color: #047857; font-size: 13px; margin: 5px 0 0 0;">Watermark-гүй бүрэн хувилбарыг "Миний template" хэсгээс татах боломжтой</p>
                    </div>
                <?php endif; ?>

                <!-- Demo товч -->
                <?php if($template['demo_url']): ?>
                    <a href="<?php echo $template['demo_url']; ?>" target="_blank" class="btn"
                       style="width: 100%; margin-bottom: 15px; background: #6b7280; color: white; text-align: center;">
                        🔍 Live Demo үзэх
                    </a>
                <?php endif; ?>
                
                <!-- Худалдаж авах товч -->
                <?php if($is_purchased): ?>
                    <!-- Худалдаж авсан бол татах товч -->
                    <a href="user/my-templates.php" class="btn btn-success"
                       style="width: 100%; font-size: 18px; text-align: center;">
                        📥 Миний template руу очих
                    </a>
                <?php elseif(isLoggedIn()): ?>
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

<!-- Lightbox Modal -->
<div id="lightboxModal" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.95); overflow: auto;">
    <span onclick="closeLightbox()" style="position: absolute; top: 20px; right: 40px; color: #fff; font-size: 40px; font-weight: bold; cursor: pointer; z-index: 10000;">&times;</span>

    <div style="position: relative; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; padding: 60px 20px;">
        <!-- Зүүн сум -->
        <button onclick="changeLightboxImage(-1)" style="position: absolute; left: 30px; background: rgba(255,255,255,0.3); color: white; border: none; font-size: 30px; padding: 15px 20px; cursor: pointer; border-radius: 5px; z-index: 10000; transition: background 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.5)'" onmouseout="this.style.background='rgba(255,255,255,0.3)'">
            &#10094;
        </button>

        <!-- Зураг -->
        <div style="text-align: center; max-width: 90%; max-height: 90%; position: relative;">
            <img id="lightboxImage" src="" style="max-width: 100%; max-height: 85vh; object-fit: contain; border-radius: 10px; box-shadow: 0 10px 50px rgba(0,0,0,0.5);">

            <!-- Lightbox Watermark (зөвхөн худалдаж аваагүй үед) -->
            <?php if(!$is_purchased): ?>
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-45deg); pointer-events: none; user-select: none;">
                <div style="font-size: 72px; font-weight: bold; color: rgba(255, 255, 255, 0.2); text-align: center; white-space: nowrap; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">
                    <?php echo SITE_NAME; ?><br>
                    <span style="font-size: 48px;">PREVIEW</span>
                </div>
            </div>
            <?php endif; ?>

            <p id="lightboxCaption" style="color: white; font-size: 16px; margin-top: 20px;"></p>
        </div>

        <!-- Баруун сум -->
        <button onclick="changeLightboxImage(1)" style="position: absolute; right: 30px; background: rgba(255,255,255,0.3); color: white; border: none; font-size: 30px; padding: 15px 20px; cursor: pointer; border-radius: 5px; z-index: 10000; transition: background 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.5)'" onmouseout="this.style.background='rgba(255,255,255,0.3)'">
            &#10095;
        </button>
    </div>
</div>

<script>
// Бүх зургуудын массив
const allImages = [
    '<?php echo SITE_URL . '/uploads/templates/' . $template['thumbnail']; ?>',
    <?php foreach($screenshots_array as $screenshot): ?>
        '<?php echo SITE_URL . '/uploads/templates/' . $screenshot['image_path']; ?>',
    <?php endforeach; ?>
];

let currentImageIndex = 0;

// Гол зураг солих
function changeImage(src, index) {
    document.getElementById('mainImage').src = src;
    currentImageIndex = index;

    // Бүх thumbnail-уудын border-г арилгах
    const thumbnails = document.querySelectorAll('.thumbnail-img');
    thumbnails.forEach(thumb => {
        thumb.style.borderColor = 'transparent';
    });

    // Сонгогдсон thumbnail-г highlight хийх
    thumbnails[index].style.borderColor = '#2563eb';
}

// Lightbox нээх
function openLightbox(index) {
    currentImageIndex = index;
    updateLightboxImage();
    document.getElementById('lightboxModal').style.display = 'block';
    document.body.style.overflow = 'hidden'; // Scroll-г хаах
}

// Lightbox хаах
function closeLightbox() {
    document.getElementById('lightboxModal').style.display = 'none';
    document.body.style.overflow = 'auto'; // Scroll-г нээх
}

// Lightbox зураг шинэчлэх
function updateLightboxImage() {
    document.getElementById('lightboxImage').src = allImages[currentImageIndex];
    document.getElementById('lightboxCaption').textContent = `Зураг ${currentImageIndex + 1} / ${allImages.length}`;
}

// Lightbox дээр зураг солих
function changeLightboxImage(direction) {
    currentImageIndex += direction;

    // Эхлэл/төгсгөлд хүрвэл буцаах
    if (currentImageIndex >= allImages.length) {
        currentImageIndex = 0;
    } else if (currentImageIndex < 0) {
        currentImageIndex = allImages.length - 1;
    }

    updateLightboxImage();

    // Гол зураг болон thumbnail-г мөн шинэчлэх
    document.getElementById('mainImage').src = allImages[currentImageIndex];
    const thumbnails = document.querySelectorAll('.thumbnail-img');
    thumbnails.forEach((thumb, index) => {
        thumb.style.borderColor = (index === currentImageIndex) ? '#2563eb' : 'transparent';
    });
}

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    const lightbox = document.getElementById('lightboxModal');
    if (lightbox.style.display === 'block') {
        if (e.key === 'ArrowLeft') {
            changeLightboxImage(-1);
        } else if (e.key === 'ArrowRight') {
            changeLightboxImage(1);
        } else if (e.key === 'Escape') {
            closeLightbox();
        }
    }
});

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
    const loginModal = document.getElementById('loginModal');
    const lightboxModal = document.getElementById('lightboxModal');

    if (event.target == loginModal) {
        loginModal.style.display = 'none';
    }
    if (event.target == lightboxModal) {
        closeLightbox();
    }
}
</script>

<?php include 'includes/footer.php'; ?>