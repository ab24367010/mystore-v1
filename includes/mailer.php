<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Имэйл илгээх функц (PHPMailer ашиглан)
 * 
 * @param string $to - Хэнд илгээх имэйл хаяг
 * @param string $subject - Имэйлийн гарчиг
 * @param string $message - HTML агуулга
 * @param array $attachments - Хавсралт файлууд (optional)
 * @return bool - Амжилттай бол true
 */
function sendEmail($to, $subject, $message, $attachments = []) {
    $mail = new PHPMailer(true);
    
    try {
        // SMTP тохиргоо
        $mail->isSMTP();
        $mail->Host = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['MAIL_USERNAME'];
        $mail->Password = $_ENV['MAIL_PASSWORD'];
        $mail->SMTPSecure = $_ENV['MAIL_ENCRYPTION'];
        $mail->Port = $_ENV['MAIL_PORT'];
        $mail->CharSet = 'UTF-8';
        
        // Илгээгч
        $mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
        
        // Хүлээн авагч
        $mail->addAddress($to);
        
        // Хавсралт файлууд (хэрэв байвал)
        if (!empty($attachments)) {
            foreach ($attachments as $file) {
                $mail->addAttachment($file);
            }
        }
        
        // Агуулга
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;
        
        // Plain text альтернатив
        $mail->AltBody = strip_tags($message);
        
        // Илгээх
        $mail->send();
        
        // Log хийх (optional)
        error_log("Email sent to: $to - Subject: $subject");
        
        return true;
        
    } catch (Exception $e) {
        // Алдаа log хийх
        error_log("Email failed: {$mail->ErrorInfo}");
        return false;
    }
}

/**
 * Email template wrapper
 */
function getEmailTemplate($title, $content) {
    return "
    <!DOCTYPE html>
    <html lang='mn'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background: #f4f4f4; }
            .container { max-width: 600px; margin: 20px auto; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
            .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; }
            .header h1 { margin: 0; font-size: 24px; }
            .content { padding: 30px; }
            .button { display: inline-block; background: #667eea; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
            .footer { background: #f9f9f9; padding: 20px; text-align: center; font-size: 12px; color: #666; }
            .info-box { background: #f0f9ff; border-left: 4px solid #3b82f6; padding: 15px; margin: 15px 0; }
            .warning-box { background: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin: 15px 0; }
            .success-box { background: #d1fae5; border-left: 4px solid #10b981; padding: 15px; margin: 15px 0; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>$title</h1>
            </div>
            <div class='content'>
                $content
            </div>
            <div class='footer'>
                <p>&copy; 2025 " . $_ENV['SITE_NAME'] . ". Бүх эрх хуулиар хамгаалагдсан.</p>
                <p>Асуулт байвал: " . $_ENV['ADMIN_EMAIL'] . "</p>
            </div>
        </div>
    </body>
    </html>
    ";
}
?>