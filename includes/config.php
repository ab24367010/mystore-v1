<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
// Composer autoload
require_once __DIR__ . '/../vendor/autoload.php';

// Load .env файл
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Database тохиргоо (.env-ээс унших)
define('DB_HOST', $_ENV['DB_HOST']);
define('DB_USER', $_ENV['DB_USER']);
define('DB_PASS', $_ENV['DB_PASS']);
define('DB_NAME', $_ENV['DB_NAME']);

// Database холболт
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Холболт шалгах
if (!$conn) {
    die("Database холболт амжилтгүй: " . mysqli_connect_error());
}

// UTF-8 тохиргоо
mysqli_set_charset($conn, "utf8mb4");

// Сайтын тохиргоо (.env-ээс унших)
define('SITE_NAME', $_ENV['SITE_NAME']);
define('SITE_URL', $_ENV['SITE_URL']);
define('ADMIN_EMAIL', $_ENV['ADMIN_EMAIL']);

// Security & Validation Constants
define('MIN_PASSWORD_LENGTH', 6);
define('MAX_PASSWORD_LENGTH', 100);
define('TOKEN_LENGTH', 32);
define('VERIFICATION_CODE_LENGTH', 6);
define('MAX_FILE_SIZE', 5242880); // 5MB in bytes
define('SESSION_LIFETIME', 3600); // 1 hour in seconds

// Rate Limiting Constants
define('LOGIN_MAX_ATTEMPTS', 5);
define('LOGIN_TIME_WINDOW', 900); // 15 minutes
define('REGISTER_MAX_ATTEMPTS', 3);
define('REGISTER_TIME_WINDOW', 3600); // 1 hour
define('PASSWORD_RESET_MAX_ATTEMPTS', 3);
define('PASSWORD_RESET_TIME_WINDOW', 3600); // 1 hour

// Token Expiration Constants
define('DOWNLOAD_TOKEN_DAYS', 30);
define('PASSWORD_RESET_MINUTES', 15);

// Session аюулгүй байдал
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', isset($_SERVER['HTTPS']) ? 1 : 0);
    session_name('MYSTORE_SESSION');
    session_start();
}
?>