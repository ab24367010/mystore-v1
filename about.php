<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$page_title = "Бидний тухай";
include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container" style="margin-top: 40px; margin-bottom: 60px;">

    <!-- Hero Section -->
    <div style="text-align: center; margin-bottom: 60px;">
        <h1 style="font-size: 48px; margin-bottom: 20px;">Бидний тухай</h1>
        <p style="font-size: 20px; color: #6b7280; max-width: 700px; margin: 0 auto;">
            Бид мэргэжлийн веб template-үүд бүтээж, таны бизнесийг дараагийн түвшинд хүргэдэг.
        </p>
    </div>

    <!-- Story Section -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 60px; margin-bottom: 80px; align-items: center;">
        <div>
            <img src="<?php echo SITE_URL; ?>/images/about-team.webp"
                alt="Team"
                style="width: 100%; height: 400px; object-fit: cover; border-radius: 10px;"
                onerror="this.src='https://via.placeholder.com/600x400/667eea/ffffff?text=Our+Team'">
        </div>
        <div>
            <h2 style="font-size: 36px; margin-bottom: 20px;">Манай түүх</h2>
            <p style="line-height: 1.8; color: #4b5563; margin-bottom: 15px;">
                <?php echo SITE_NAME; ?> нь 2024 онд үүсгэн байгуулагдсан бөгөөд
                өндөр чанартай, мэргэжлийн веб template-үүдийг хүргэх зорилготой юм.
            </p>
            <p style="line-height: 1.8; color: #4b5563; margin-bottom: 15px;">
                Бид жижиг бизнесүүд болон хувь хүмүүст хялбар, боломжийн үнээр
                мэргэжлийн вэбсайт бүтээх боломжийг олгодог.
            </p>
            <p style="line-height: 1.8; color: #4b5563;">
                Манай зорилго: Чанартай дизайн, хурдан хүргэлт, 24/7 дэмжлэг.
            </p>
        </div>
    </div>

    <!-- Values Section -->
    <div style="margin-bottom: 80px;">
        <h2 style="text-align: center; font-size: 36px; margin-bottom: 50px;">Манай үнэт зүйлс</h2>

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px;">

            <!-- Чанар -->
            <div style="background: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center;">
                <div style="font-size: 60px; margin-bottom: 20px;">⭐</div>
                <h3 style="font-size: 24px; margin-bottom: 15px;">Чанар</h3>
                <p style="color: #6b7280; line-height: 1.8;">
                    Бид зөвхөн өндөр стандартын дагуу бүтээгдсэн template-уудыг санал болгодог.
                </p>
            </div>

            <!-- Дэмжлэг -->
            <div style="background: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center;">
                <div style="font-size: 60px; margin-bottom: 20px;">🤝</div>
                <h3 style="font-size: 24px; margin-bottom: 15px;">Дэмжлэг</h3>
                <p style="color: #6b7280; line-height: 1.8;">
                    24/7 дэмжлэг үзүүлж, таны бүх асуултад хариулах бэлэн байдаг.
                </p>
            </div>

            <!-- Инновац -->
            <div style="background: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center;">
                <div style="font-size: 60px; margin-bottom: 20px;">🚀</div>
                <h3 style="font-size: 24px; margin-bottom: 15px;">Инновац</h3>
                <p style="color: #6b7280; line-height: 1.8;">
                    Хамгийн сүүлийн үеийн технологи, дизайн хандлагыг дагадаг.
                </p>
            </div>

        </div>
    </div>

    <!-- Stats Section -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 60px 40px; border-radius: 10px; margin-bottom: 80px;">
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 30px; text-align: center;">

            <div>
                <h3 style="font-size: 48px; color: white; margin-bottom: 10px;">500+</h3>
                <p style="color: rgba(255,255,255,0.9); font-size: 18px;">Баярлах хэрэглэгч</p>
            </div>

            <div>
                <h3 style="font-size: 48px; color: white; margin-bottom: 10px;">50+</h3>
                <p style="color: rgba(255,255,255,0.9); font-size: 18px;">Template-үүд</p>
            </div>

            <div>
                <h3 style="font-size: 48px; color: white; margin-bottom: 10px;">24/7</h3>
                <p style="color: rgba(255,255,255,0.9); font-size: 18px;">Дэмжлэг</p>
            </div>

            <div>
                <h3 style="font-size: 48px; color: white; margin-bottom: 10px;">100%</h3>
                <p style="color: rgba(255,255,255,0.9); font-size: 18px;">Сэтгэл ханамж</p>
            </div>

        </div>
    </div>

    <!-- Team Section -->
    <div style="margin-bottom: 60px;">
        <h2 style="text-align: center; font-size: 36px; margin-bottom: 50px;">Манай баг</h2>

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px;">

            <!-- Team Member 1 -->
            <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center;">
                <div style="width: 120px; height: 120px; border-radius: 50%; margin: 0 auto 20px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                    <img src="<?php echo SITE_URL; ?>/images/CEO.webp"
                        alt="Altan-Ochir"
                        style="width: 100%; height: 100%; object-fit: cover;"
                        onerror="this.src='https://via.placeholder.com/120x120/4facfe/ffffff?text=AO'">
                </div>
                <h3 style="font-size: 20px; margin-bottom: 5px;">ALTAN-OCHIR B.</h3>
                <p style="color: #6b7280; font-size: 14px; margin-bottom: 15px;">CEO & Founder</p>
                <p style="color: #6b7280; font-size: 14px; line-height: 1.6;">
                    5 жилийн туршлагатай веб хөгжүүлэгч
                </p>
            </div>


            <!-- Team Member 2 -->
            <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center;">
                <div style="width: 120px; height: 120px; border-radius: 50%; margin: 0 auto 20px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                    <img src="<?php echo SITE_URL; ?>/images/wearehiring.webp"
                        alt="Altan-Ochir"
                        style="width: 100%; height: 100%; object-fit: cover;"
                        onerror="this.src='https://via.placeholder.com/120x120/4facfe/ffffff?text=AO'">
                </div>
                <h3 style="font-size: 20px; margin-bottom: 5px;">Ta ch baij bolno.</h3>
                <p style="color: #6b7280; font-size: 14px; margin-bottom: 15px;">Lead Designer</p>
                <p style="color: #6b7280; font-size: 14px; line-height: 1.6;">
                    Гоо зүйч, UX/UI мэргэжилтэн
                </p>
            </div>

            <!-- Team Member 3 -->
            <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center;">
                <div style="width: 120px; height: 120px; border-radius: 50%; margin: 0 auto 20px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                    <img src="<?php echo SITE_URL; ?>/images/wearehiring.webp"
                        alt="Altan-Ochir"
                        style="width: 100%; height: 100%; object-fit: cover;"
                        onerror="this.src='https://via.placeholder.com/120x120/4facfe/ffffff?text=AO'">
                </div>
                <h3 style="font-size: 20px; margin-bottom: 5px;">Ta ch baij bolno.</h3>
                <p style="color: #6b7280; font-size: 14px; margin-bottom: 15px;">Support Manager</p>
                <p style="color: #6b7280; font-size: 14px; line-height: 1.6;">
                    Хэрэглэгчийн дэмжлэг, туслалцаа
                </p>
            </div>

        </div>
    </div>

    <!-- CTA Section -->
    <div style="background: #f3f4f6; padding: 60px 40px; border-radius: 10px; text-align: center;">
        <h2 style="font-size: 36px; margin-bottom: 20px;">Бидэнтэй нэгдээрэй!</h2>
        <p style="font-size: 18px; color: #6b7280; margin-bottom: 30px;">
            Таны бизнест тохирсон шийдлийг хамтдаа бүтээцгээе
        </p>
        <a href="<?php echo SITE_URL; ?>/templates.php" class="btn btn-primary" style="font-size: 18px; padding: 15px 40px;">
            Template-үүд үзэх
        </a>
    </div>

</div>

<?php include 'includes/footer.php'; ?>