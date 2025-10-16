<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$success = '';
$error = '';

// Форм submit хийхэд
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = clean($_POST['name']);
    $email = clean($_POST['email']);
    $subject = clean($_POST['subject']);
    $message = clean($_POST['message']);

    // Validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = "Бүх талбарыг бөглөнө үү";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Имэйл хаяг буруу байна";
    } else {
        // Админд имэйл илгээх
        $email_content = "
            <h2>📧 Шинэ мессеж - Contact Form</h2>
            
            <div class='info-box'>
                <p><strong>Нэр:</strong> " . htmlspecialchars($name) . "</p>
                <p><strong>Имэйл:</strong> " . htmlspecialchars($email) . "</p>
                <p><strong>Сэдэв:</strong> " . htmlspecialchars($subject) . "</p>
            </div>
            
            <div class='info-box'>
                <h3>Мессеж:</h3>
                <p>" . nl2br(htmlspecialchars($message)) . "</p>
            </div>
            
            <p style='color: #6b7280; font-size: 12px; margin-top: 20px;'>
                Илгээсэн: " . date('Y-m-d H:i:s') . "
            </p>
        ";

        $email_subject = "📧 Холбоо барих: " . $subject;
        $html_message = getEmailTemplate("Шинэ мессеж", $email_content);

        if (sendEmail(ADMIN_EMAIL, $email_subject, $html_message)) {
            $success = "Таны мессежийг амжилттай илгээлээ! Бид тун удахгүй хариу өгөх болно.";

            // Хэрэглэгчид баталгаажуулах имэйл
            $user_content = "
                <p>Сайн байна уу <strong>" . htmlspecialchars($name) . "</strong>,</p>
                
                <p>Таны мессежийг хүлээн авлаа. Баярлалаа!</p>
                
                <div class='success-box'>
                    <h3>✅ Таны мессеж хүлээн авлаа</h3>
                    <p><strong>Сэдэв:</strong> " . htmlspecialchars($subject) . "</p>
                </div>
                
                <div class='info-box'>
                    <p>Бид 24-48 цагийн дотор хариу өгөх болно.</p>
                </div>
                
                <p>Баярлалаа!<br><strong>" . SITE_NAME . " баг</strong></p>
            ";

            $user_subject = "Таны мессеж хүлээн авлаа - " . SITE_NAME;
            $user_html = getEmailTemplate("Мессеж хүлээн авлаа", $user_content);
            sendEmail($email, $user_subject, $user_html);
        } else {
            $error = "Мессеж илгээхэд алдаа гарлаа. Дахин оролдоно уу.";
        }
    }
}

$page_title = "Холбоо барих";
include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container" style="margin-top: 40px; margin-bottom: 60px;">

    <!-- Hero -->
    <div style="text-align: center; margin-bottom: 60px;">
        <h1 style="font-size: 48px; margin-bottom: 20px;">Холбоо барих</h1>
        <p style="font-size: 20px; color: #6b7280; max-width: 700px; margin: 0 auto;">
            Асуулт, санал хүсэлт байвал бидэнд мэдэгдээрэй. Бид тантай холбогдох болно.
        </p>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 60px;">

        <!-- Contact Form -->
        <div>
            <div style="background: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h2 style="margin-bottom: 30px;">Мессеж илгээх</h2>

                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST" action="" data-loading="Мессеж илгээж байна..." data-loading-overlay>
                    <div class="form-group">
                        <label>Нэр *</label>
                        <input type="text" name="name" required placeholder="Таны нэр"
                            value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label>Имэйл хаяг *</label>
                        <input type="email" name="email" required placeholder="your@email.com"
                            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label>Сэдэв *</label>
                        <input type="text" name="subject" required placeholder="Мессежийн сэдэв"
                            value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label>Мессеж *</label>
                        <textarea name="message" rows="6" required placeholder="Таны мессеж..."><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; font-size: 18px;">
                        📧 Мессеж илгээх
                    </button>
                </form>
            </div>
        </div>

        <!-- Contact Info -->
        <div>
            <!-- Хаяг -->
            <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
                <div style="display: flex; align-items: start; gap: 20px;">
                    <div style="font-size: 40px;">📍</div>
                    <div>
                        <h3 style="margin-bottom: 10px;">Хаяг</h3>
                        <p style="color: #6b7280; line-height: 1.8;">
                            Tokyo, Japan
                        </p>
                    </div>
                </div>
            </div>

            <!-- Утас -->
            <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
                <div style="display: flex; align-items: start; gap: 20px;">
                    <div style="font-size: 40px;">📞</div>
                    <div>
                        <h3 style="margin-bottom: 10px;">Утас</h3>
                        <p style="color: #6b7280; line-height: 1.8;">
                            +81 80 9053 6482<br>
                            Өдөр бүр: 9:00 - 18:00
                        </p>
                    </div>
                </div>
            </div>

            <!-- Имэйл -->
            <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
                <div style="display: flex; align-items: start; gap: 20px;">
                    <div style="font-size: 40px;">✉️</div>
                    <div>
                        <h3 style="margin-bottom: 10px;">Имэйл</h3>
                        <p style="color: #6b7280; line-height: 1.8;">
                            <a href="mailto:<?php echo ADMIN_EMAIL; ?>" style="color: #2563eb; text-decoration: none;">
                                <?php echo ADMIN_EMAIL; ?>
                            </a><br>
                            24/7 дэмжлэг
                        </p>
                    </div>
                </div>
            </div>

            <!-- Social Media -->
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; border-radius: 10px; text-align: center; color: white;">
                <h3 style="margin-bottom: 20px;">Биднийг дагаарай</h3>
                <div style="display: flex; justify-content: center; gap: 25px; font-size: 32px;">
                    <a href="https://www.facebook.com/" target="_blank" style="color: white; text-decoration: none;">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="https://www.instagram.com/_ciol_ft/" target="_blank" style="color: white; text-decoration: none;">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://x.com/" target="_blank" style="color: white; text-decoration: none;">
                        <i class="fab fa-x-twitter"></i>
                    </a>
                    <a href="https://www.threads.com/@_ciol_ft" target="_blank" style="color: white; text-decoration: none;">
                        <i class="fab fa-threads"></i>
                    </a>
                </div>
            </div>

        </div>

    </div>

    <!-- Map Section (Conditional on Cookie Consent) -->
    <div style="margin-top: 60px;">
        <h2 style="text-align: center; margin-bottom: 30px;">Манай байршил</h2>

        <!-- Google Maps Placeholder (shown when consent not given) -->
        <div class="google-maps-placeholder" style="background: #f3f4f6; height: 400px; border-radius: 10px; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px; text-align: center;">
            <div style="font-size: 60px; margin-bottom: 20px;">🗺️</div>
            <h3 style="margin-bottom: 15px; color: #374151;">Google Maps идэвхгүй байна</h3>
            <p style="color: #6b7280; margin-bottom: 25px; max-width: 500px;">
                Газрын зургийг харахын тулд функциональ cookie-г зөвшөөрөх хэрэгтэй. Энэ нь Google-аас гуравдагч этгээдийн cookie ашиглах болно.
            </p>
            <button onclick="CookieConsent.openSettings()" class="btn btn-primary" style="padding: 12px 30px;">
                Cookie тохиргоо нээх
            </button>
        </div>

        <!-- Google Maps iframe (shown when consent is given) -->
        <div class="google-maps-iframe" style="background: #f3f4f6; height: 400px; border-radius: 10px; display: none;">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d207446.32915335396!2d139.57605273186255!3d35.668410308564354!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x60188b857628235d%3A0xcdd8aef709a2b520!2zVG9reW8sINCi0L7QutC40L4!5e0!3m2!1smn!2sjp!4v1760423555412!5m2!1smn!2sjp" width="100%" height="400" style="border:0; border-radius: 10px;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>

</div>

<?php include 'includes/footer.php'; ?>