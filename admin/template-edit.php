<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$page_title = "Template засах";
include 'header.php';

// Template ID авах
if(!isset($_GET['id']) || empty($_GET['id'])) {
    setAlert("Template олдсонгүй", 'error');
    redirect('templates.php');
}

$template_id = (int)$_GET['id'];

// Template мэдээлэл татах
$sql = "SELECT * FROM templates WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $template_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0) {
    setAlert("Template олдсонгүй", 'error');
    redirect('templates.php');
}

$template = $result->fetch_assoc();

$error = '';

// Форм submit хийхэд
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = clean($_POST['name']);
    $description = clean($_POST['description']);
    $price = (float)$_POST['price'];
    $category = clean($_POST['category']);
    $demo_url = clean($_POST['demo_url']);
    $status = clean($_POST['status']);
    
    // Validation
    if(empty($name) || empty($price)) {
        $error = "Нэр болон үнэ заавал шаардлагатай";
    } else {
        $thumbnail = $template['thumbnail'];
        $file_path = $template['file_path'];
        
        // Шинэ thumbnail upload
        if(isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == 0) {
            $allowed = array('jpg', 'jpeg', 'png', 'gif');
            $filename = $_FILES['thumbnail']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            if(in_array($ext, $allowed)) {
                if($_FILES['thumbnail']['size'] < 5242880) {
                    // Хуучин зураг устгах
                    if($thumbnail && file_exists('../uploads/templates/' . $thumbnail)) {
                        unlink('../uploads/templates/' . $thumbnail);
                    }
                    
                    $thumbnail = uniqid() . '.' . $ext;
                    move_uploaded_file($_FILES['thumbnail']['tmp_name'], '../uploads/templates/' . $thumbnail);
                } else {
                    $error = "Зураг хэт том байна (max 5MB)";
                }
            } else {
                $error = "Зөвхөн зураг файл зөвшөөрөгдөнө";
            }
        }
        
        // Шинэ ZIP файл upload
        if(isset($_FILES['zip_file']) && $_FILES['zip_file']['error'] == 0) {
            $filename = $_FILES['zip_file']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            if($ext == 'zip') {
                if($_FILES['zip_file']['size'] < 52428800) {
                    // Хуучин файл устгах
                    if($file_path && file_exists('../uploads/files/' . $file_path)) {
                        unlink('../uploads/files/' . $file_path);
                    }
                    
                    $file_path = uniqid() . '.zip';
                    move_uploaded_file($_FILES['zip_file']['tmp_name'], '../uploads/files/' . $file_path);
                } else {
                    $error = "ZIP файл хэт том байна (max 50MB)";
                }
            } else {
                $error = "Зөвхөн ZIP файл зөвшөөрөгдөнө";
            }
        }
        
        if(empty($error)) {
            // Database шинэчлэх
            $sql = "UPDATE templates SET name = ?, description = ?, price = ?, thumbnail = ?, demo_url = ?, file_path = ?, category = ?, status = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdsssssi", $name, $description, $price, $thumbnail, $demo_url, $file_path, $category, $status, $template_id);
            
            if($stmt->execute()) {
                setAlert("Template амжилттай шинэчлэгдлээ!", 'success');
                redirect('templates.php');
            } else {
                $error = "Алдаа гарлаа. Дахин оролдоно уу.";
            }
        }
    }
}
?>

<div class="container" style="max-width: 900px; margin-bottom: 60px;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1>✏️ Template засах</h1>
        <a href="templates.php" class="btn" style="background: #6b7280; color: white;">← Буцах</a>
    </div>
    
    <?php if($error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="POST" action="" enctype="multipart/form-data">
        
        <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            
            <h2 style="margin-bottom: 20px;">Үндсэн мэдээлэл</h2>
            
            <!-- Нэр -->
            <div class="form-group">
                <label>Нэр *</label>
                <input type="text" name="name" required value="<?php echo htmlspecialchars($template['name']); ?>">
            </div>
            
            <!-- Тайлбар -->
            <div class="form-group">
                <label>Тайлбар</label>
                <textarea name="description" rows="5"><?php echo htmlspecialchars($template['description']); ?></textarea>
            </div>
            
            <!-- Үнэ болон Category -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Үнэ ($) *</label>
                    <input type="number" name="price" step="0.01" min="0" required value="<?php echo $template['price']; ?>">
                </div>
                
                <div class="form-group">
                    <label>Category</label>
                    <select name="category" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 5px;">
                        <option value="">Сонгох...</option>
                        <option value="E-commerce" <?php echo ($template['category'] == 'E-commerce') ? 'selected' : ''; ?>>E-commerce</option>
                        <option value="Portfolio" <?php echo ($template['category'] == 'Portfolio') ? 'selected' : ''; ?>>Portfolio</option>
                        <option value="Landing Page" <?php echo ($template['category'] == 'Landing Page') ? 'selected' : ''; ?>>Landing Page</option>
                        <option value="Corporate" <?php echo ($template['category'] == 'Corporate') ? 'selected' : ''; ?>>Corporate</option>
                        <option value="Blog" <?php echo ($template['category'] == 'Blog') ? 'selected' : ''; ?>>Blog</option>
                        <option value="Other" <?php echo ($template['category'] == 'Other') ? 'selected' : ''; ?>>Бусад</option>
                    </select>
                </div>
            </div>
            
            <!-- Demo URL -->
            <div class="form-group">
                <label>Demo URL</label>
                <input type="url" name="demo_url" value="<?php echo htmlspecialchars($template['demo_url']); ?>">
            </div>
            
            <!-- Төлөв -->
            <div class="form-group">
                <label>Төлөв</label>
                <select name="status" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 5px;">
                    <option value="active" <?php echo ($template['status'] == 'active') ? 'selected' : ''; ?>>Идэвхтэй</option>
                    <option value="inactive" <?php echo ($template['status'] == 'inactive') ? 'selected' : ''; ?>>Идэвхгүй</option>
                </select>
            </div>
            
        </div>
        
        <!-- Файлууд upload -->
        <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-top: 20px;">
            
            <h2 style="margin-bottom: 20px;">Файлууд</h2>
            
            <!-- Thumbnail -->
            <div class="form-group">
                <label>Thumbnail зураг</label>
                
                <?php if($template['thumbnail']): ?>
                    <div style="margin-bottom: 10px;">
                        <img src="<?php echo SITE_URL . '/uploads/templates/' . $template['thumbnail']; ?>" 
                             alt="Current thumbnail"
                             style="width: 200px; height: auto; border-radius: 5px; border: 2px solid #e5e7eb;">
                        <p style="color: #6b7280; font-size: 12px; margin-top: 5px;">Одоогийн зураг</p>
                    </div>
                <?php endif; ?>
                
                <input type="file" name="thumbnail" accept="image/*">
                <p style="color: #6b7280; font-size: 12px; margin-top: 5px;">Шинэ зураг сонговол хуучинх солигдоно</p>
            </div>
            
            <!-- ZIP файл -->
            <div class="form-group">
                <label>Template ZIP файл</label>
                
                <?php if($template['file_path']): ?>
                    <div style="background: #f3f4f6; padding: 10px; border-radius: 5px; margin-bottom: 10px;">
                        <p style="margin: 0; font-size: 14px;">📦 Одоогийн файл: <strong><?php echo $template['file_path']; ?></strong></p>
                    </div>
                <?php endif; ?>
                
                <input type="file" name="zip_file" accept=".zip">
                <p style="color: #6b7280; font-size: 12px; margin-top: 5px;">Шинэ файл сонговол хуучинь солигдоно</p>
            </div>
            
        </div>
        
        <!-- Товчнууд -->
        <div style="margin-top: 30px; text-align: right;">
            <a href="templates.php" class="btn" style="background: #6b7280; color: white; margin-right: 10px;">Болих</a>
            <button type="submit" class="btn btn-primary">💾 Шинэчлэх</button>
        </div>
        
    </form>
    
</div>

<?php include 'footer.php'; ?>