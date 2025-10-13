<?php
// SQL injection засах
function clean($string) {
    global $conn;
    return mysqli_real_escape_string($conn, trim($string));
}

// Redirect хийх
function redirect($page) {
    header("Location: " . $page);
    exit();
}

// Alert мессеж тохируулах
function setAlert($message, $type = 'success') {
    $_SESSION['alert'] = $message;
    $_SESSION['alert_type'] = $type;
}

// Alert харуулах
function showAlert() {
    if(isset($_SESSION['alert'])) {
        $type = $_SESSION['alert_type'];
        $message = $_SESSION['alert'];
        
        $color = ($type == 'success') ? 'green' : 'red';
        
        echo "<div style='background: $color; color: white; padding: 15px; margin: 10px 0; border-radius: 5px;'>";
        echo $message;
        echo "</div>";
        
        unset($_SESSION['alert']);
        unset($_SESSION['alert_type']);
    }
}

// Нэвтэрсэн эсэхийг шалгах
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Админ эсэхийг шалгах
function isAdmin() {
    return isset($_SESSION['admin_id']);
}

// Үнэ format
function formatPrice($price) {
    return "$" . number_format($price, 2);
}

// Огноо format
function formatDate($date) {
    return date('Y-m-d H:i', strtotime($date));
}

// Random token үүсгэх
function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}

// Имэйл илгээх
function sendEmail($to, $subject, $message) {
    $headers = "From: " . ADMIN_EMAIL . "\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    return mail($to, $subject, $message, $headers);
}

// Захиалгын дугаар үүсгэх
function generateOrderNumber() {
    return 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
}
?>