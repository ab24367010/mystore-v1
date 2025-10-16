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

// Alert харуулах (Accessibility: ARIA-live region)
function showAlert() {
    if(isset($_SESSION['alert'])) {
        $type = $_SESSION['alert_type'];
        $message = $_SESSION['alert'];

        $color = ($type == 'success') ? 'green' : 'red';
        $role = ($type == 'success') ? 'status' : 'alert';

        echo "<div role='$role' aria-live='polite' aria-atomic='true' style='background: $color; color: white; padding: 15px; margin: 10px 0; border-radius: 5px;'>";
        echo htmlspecialchars($message);
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
function generateToken($length = TOKEN_LENGTH) {
    return bin2hex(random_bytes($length));
}

// Захиалгын дугаар үүсгэх
function generateOrderNumber() {
    return 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
}

// CSRF Token функцүүд
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        return false;
    }
    return true;
}

function getCSRFField() {
    $token = generateCSRFToken();
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
}

// Rate Limiting функцүүд
function checkRateLimit($action, $max_attempts = 5, $time_window = 900) {
    $key = $action . '_' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown');

    if (!isset($_SESSION['rate_limit'][$key])) {
        $_SESSION['rate_limit'][$key] = [
            'attempts' => 0,
            'first_attempt' => time()
        ];
    }

    $data = $_SESSION['rate_limit'][$key];

    // Time window дууссан бол reset хийх
    if (time() - $data['first_attempt'] > $time_window) {
        $_SESSION['rate_limit'][$key] = [
            'attempts' => 1,
            'first_attempt' => time()
        ];
        return true;
    }

    // Max attempts хэтэрсэн эсэх
    if ($data['attempts'] >= $max_attempts) {
        return false;
    }

    $_SESSION['rate_limit'][$key]['attempts']++;
    return true;
}

// File upload validation
function validateImageUpload($file, $max_size = MAX_FILE_SIZE) {
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/gif'];
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

    // Check if file exists
    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return ['success' => false, 'error' => 'Файл сонгоогүй байна'];
    }

    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'Файл upload хийхэд алдаа гарлаа'];
    }

    // Check file size
    if ($file['size'] > $max_size) {
        $max_mb = round($max_size / 1048576, 1);
        return ['success' => false, 'error' => "Файлын хэмжээ хэтэрсэн байна (Max: {$max_mb}MB)"];
    }

    // Check MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime_type, $allowed_types)) {
        return ['success' => false, 'error' => 'Зөвхөн зураг файл upload хийх боломжтой (JPG, PNG, WebP, GIF)'];
    }

    // Check file extension
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, $allowed_extensions)) {
        return ['success' => false, 'error' => 'Файлын өргөтгөл буруу байна'];
    }

    // Additional security: Check if it's really an image
    $image_info = getimagesize($file['tmp_name']);
    if ($image_info === false) {
        return ['success' => false, 'error' => 'Файл зураг биш байна'];
    }

    return ['success' => true];
}

// Logging функц
function logError($message, $context = []) {
    $log_file = __DIR__ . '/../logs/error.log';
    $log_dir = dirname($log_file);

    if (!file_exists($log_dir)) {
        mkdir($log_dir, 0755, true);
    }

    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $context_str = !empty($context) ? json_encode($context) : '';

    $log_entry = "[$timestamp] [$ip] $message $context_str\n";
    error_log($log_entry, 3, $log_file);
}

// Mailer функцүүд
require_once __DIR__ . '/mailer.php';
?>