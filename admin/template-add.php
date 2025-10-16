<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$page_title = "Шинэ Template";
include 'header.php';

$error = '';
$success = '';

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
        // Thumbnail upload
        $thumbnail = '';
        if(isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == 0) {
            $allowed = array('jpg', 'jpeg', 'png', 'gif');
            $filename = $_FILES['thumbnail']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            if(in_array($ext, $allowed)) {
                if($_FILES['thumbnail']['size'] < 5242880) { // 5MB
                    $thumbnail = uniqid() . '.' . $ext;
                    move_uploaded_file($_FILES['thumbnail']['tmp_name'], '../uploads/templates/' . $thumbnail);
                } else {
                    $error = "Зураг хэт том байна (max 5MB)";
                }
            } else {
                $error = "Зөвхөн зураг файл зөвшөөрөгдөнө";
            }
        }
        
        // ZIP файл upload
        $file_path = '';
        if(isset($_FILES['zip_file']) && $_FILES['zip_file']['error'] == 0) {
            $filename = $_FILES['zip_file']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            if($ext == 'zip') {
                if($_FILES['zip_file']['size'] < 52428800) { // 50MB
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
            // Database-д нэмэх
            $sql = "INSERT INTO templates (name, description, price, thumbnail, demo_url, file_path, category, status, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdsssss", $name, $description, $price, $thumbnail, $demo_url, $file_path, $category, $status);
            
            if($stmt->execute()) {
                $template_id = $conn->insert_id;

                // Нэмэлт screenshot-ууд upload хийх
                if(isset($_FILES['screenshots']) && !empty($_FILES['screenshots']['name'][0])) {
                    $allowed = array('jpg', 'jpeg', 'png', 'gif');

                    for($i = 0; $i < count($_FILES['screenshots']['name']); $i++) {
                        if($_FILES['screenshots']['error'][$i] == 0) {
                            $filename = $_FILES['screenshots']['name'][$i];
                            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                            if(in_array($ext, $allowed) && $_FILES['screenshots']['size'][$i] < 5242880) {
                                $screenshot_name = uniqid() . '.' . $ext;
                                move_uploaded_file($_FILES['screenshots']['tmp_name'][$i], '../uploads/templates/' . $screenshot_name);

                                // Database-д нэмэх
                                $screenshot_sql = "INSERT INTO template_screenshots (template_id, image_path, display_order) VALUES (?, ?, ?)";
                                $screenshot_stmt = $conn->prepare($screenshot_sql);
                                $display_order = $i + 1;
                                $screenshot_stmt->bind_param("isi", $template_id, $screenshot_name, $display_order);
                                $screenshot_stmt->execute();
                            }
                        }
                    }
                }

                setAlert("Template амжилттай нэмэгдлээ!", 'success');
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
        <h1>+ Шинэ Template нэмэх</h1>
        <a href="templates.php" class="btn" style="background: #6b7280; color: white;">← Буцах</a>
    </div>
    
    <?php if($error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="POST" action="" data-loading="Template нэмэж байна..." data-loading-overlay enctype="multipart/form-data">
        
        <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            
            <h2 style="margin-bottom: 20px;">Үндсэн мэдээлэл</h2>
            
            <!-- Нэр -->
            <div class="form-group">
                <label>Нэр *</label>
                <input type="text" name="name" required placeholder="E-commerce Template"
                       value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
            </div>
            
            <!-- Тайлбар -->
            <div class="form-group">
                <label>Тайлбар</label>
                <textarea name="description" rows="5" placeholder="Template-ийн дэлгэрэнгүй тайлбар..."><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
            </div>
            
            <!-- Үнэ болон Category -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Үнэ ($) *</label>
                    <input type="number" name="price" step="0.01" min="0" required placeholder="49.99"
                           value="<?php echo isset($_POST['price']) ? $_POST['price'] : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label>Category</label>
                    <select name="category" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 5px;">
                        <option value="">Сонгох...</option>
                        <option value="E-commerce">E-commerce</option>
                        <option value="Portfolio">Portfolio</option>
                        <option value="Landing Page">Landing Page</option>
                        <option value="Corporate">Corporate</option>
                        <option value="Blog">Blog</option>
                        <option value="Other">Бусад</option>
                    </select>
                </div>
            </div>
            
            <!-- Demo URL -->
            <div class="form-group">
                <label>Demo URL</label>
                <input type="url" name="demo_url" placeholder="https://demo.yoursite.com/template1"
                       value="<?php echo isset($_POST['demo_url']) ? htmlspecialchars($_POST['demo_url']) : ''; ?>">
            </div>
            
            <!-- Төлөв -->
            <div class="form-group">
                <label>Төлөв</label>
                <select name="status" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 5px;">
                    <option value="active">Идэвхтэй</option>
                    <option value="inactive">Идэвхгүй</option>
                </select>
            </div>
            
        </div>
        
        <!-- Файлууд upload -->
        <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-top: 20px;">
            
            <h2 style="margin-bottom: 20px;">Файлууд</h2>
            
            <!-- Thumbnail -->
            <div class="form-group">
                <label>Thumbnail зураг (Үндсэн зураг) *</label>
                <input type="file" name="thumbnail" accept="image/*">
                <p style="color: #6b7280; font-size: 12px; margin-top: 5px;">PNG, JPG, GIF (max 5MB) - Энэ зураг жагсаалтанд харагдана</p>
            </div>

            <!-- Нэмэлт screenshots -->
            <div class="form-group">
                <label>Нэмэлт Screenshot-ууд (Дэлгэрэнгүй хуудсанд харагдана)</label>
                <input type="file" name="screenshots[]" accept="image/*" multiple>
                <p style="color: #6b7280; font-size: 12px; margin-top: 5px;">
                    PNG, JPG, GIF (max 5MB тус бүр) - Олон зураг сонгож болно (Ctrl/Cmd дарж олон файл сонгох)
                    <br>Санал: Header, Content, Footer зэрэг янз бүрийн хэсгүүдийн зураг оруулна уу
                </p>
            </div>

            <!-- ZIP файл -->
            <div class="form-group">
                <label>Template ZIP файл</label>
                <input type="file" name="zip_file" accept=".zip">
                <p style="color: #6b7280; font-size: 12px; margin-top: 5px;">ZIP файл (max 50MB)</p>
            </div>
            
        </div>
        
        <!-- Товчнууд -->
        <div style="margin-top: 30px; text-align: right;">
            <a href="templates.php" class="btn" style="background: #6b7280; color: white; margin-right: 10px;">Болих</a>
            <button type="submit" class="btn btn-success">💾 Хадгалах</button>
        </div>
        
    </form>
    
</div>

<?php include 'footer.php'; ?>