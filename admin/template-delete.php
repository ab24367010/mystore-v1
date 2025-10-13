<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Админ эсэхийг шалгах
if(!isAdmin()) {
    redirect('login.php');
}

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

// Захиалгатай эсэхийг шалгах
$check_sql = "SELECT COUNT(*) as count FROM orders WHERE template_id = ?";
$stmt = $conn->prepare($check_sql);
$stmt->bind_param("i", $template_id);
$stmt->execute();
$has_orders = $stmt->get_result()->fetch_assoc()['count'];

if($has_orders > 0) {
    setAlert("Энэ template-д захиалга байгаа тул устгах боломжгүй. Харин 'Идэвхгүй' болгож болно.", 'error');
    redirect('templates.php');
}

// Файлууд устгах
if($template['thumbnail'] && file_exists('../uploads/templates/' . $template['thumbnail'])) {
    unlink('../uploads/templates/' . $template['thumbnail']);
}

if($template['file_path'] && file_exists('../uploads/files/' . $template['file_path'])) {
    unlink('../uploads/files/' . $template['file_path']);
}

// Screenshot-ууд устгах
$screenshot_sql = "SELECT image_path FROM template_screenshots WHERE template_id = ?";
$stmt = $conn->prepare($screenshot_sql);
$stmt->bind_param("i", $template_id);
$stmt->execute();
$screenshots = $stmt->get_result();

while($screenshot = $screenshots->fetch_assoc()) {
    if(file_exists('../uploads/templates/' . $screenshot['image_path'])) {
        unlink('../uploads/templates/' . $screenshot['image_path']);
    }
}

// Database-аас устгах
$sql = "DELETE FROM template_screenshots WHERE template_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $template_id);
$stmt->execute();

$sql = "DELETE FROM templates WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $template_id);

if($stmt->execute()) {
    setAlert("Template амжилттай устгагдлаа", 'success');
} else {
    setAlert("Алдаа гарлаа. Дахин оролдоно уу.", 'error');
}

redirect('templates.php');
?>