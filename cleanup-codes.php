<?php
/**
 * Password Reset Code Cleanup Script
 *
 * Хуучин болон ашигласан verification code-уудыг устгана
 * Cron job эсвэл manual ажиллуулж болно
 *
 * Жишээ cron job:
 * 0 0 * * * php /path/to/mystore-v1/cleanup-codes.php
 */

require_once __DIR__ . '/includes/config.php';

// Устгах code-ууд: хугацаа дууссан эсвэл баталгаажсан
$sql = "DELETE FROM password_reset_codes
        WHERE expires_at < NOW() OR verified = 1";

if($conn->query($sql)) {
    $deleted = $conn->affected_rows;

    // Log хийх
    $log_message = "[" . date('Y-m-d H:i:s') . "] Password reset code cleanup: $deleted codes deleted\n";
    error_log($log_message);

    // Console output (manual ажиллуулахад)
    echo "✅ Амжилттай: $deleted verification code устгагдлаа\n";
    echo "Огноо: " . date('Y-m-d H:i:s') . "\n";
} else {
    // Алдаа log
    $error = $conn->error;
    error_log("[" . date('Y-m-d H:i:s') . "] Code cleanup failed: $error");
    echo "❌ Алдаа гарлаа: $error\n";
}

$conn->close();
?>
