<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
$page_title = "Нууцлалын бодлого";
include 'includes/header.php';
include 'includes/navbar.php';
?>
<div class="container" style="margin-top: 40px; margin-bottom: 60px; max-width: 900px;">
    <div style="text-align: center; margin-bottom: 60px;">
        <h1 style="font-size: 48px; margin-bottom: 20px;">🔒 Нууцлалын бодлого</h1>
        <p style="font-size: 16px; color: #6b7280;">
            Сүүлд шинэчлэгдсэн: <?php echo date('Y оны m сарын d'); ?>
        </p>
    </div>

    <div style="background: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">

        <!-- Танилцуулга -->
        <section style="margin-bottom: 40px;">
            <h2 style="color: #2563eb; margin-bottom: 20px;">1. Танилцуулга</h2>
            <p style="line-height: 1.8; color: #374151;">
                <?php echo SITE_NAME; ?> ("Бид", "Манай", "Сайт") нь таны хувийн мэдээллийн нууцлалыг эрхэмлэн хамгаална.
                Энэхүү нууцлалын бодлого нь манай сайтыг ашиглах явцад бид таны мэдээллийг хэрхэн цуглуулж,
                ашиглаж, хамгаалж байгааг тайлбарлана.
            </p>
        </section>

        <!-- Цуглуулах мэдээлэл -->
        <section style="margin-bottom: 40px;">
            <h2 style="color: #2563eb; margin-bottom: 20px;">2. Бидний цуглуулдаг мэдээлэл</h2>

            <h3 style="margin-top: 20px; margin-bottom: 10px;">2.1 Хувийн мэдээлэл</h3>
            <p style="line-height: 1.8; color: #374151;">
                Бүртгүүлэх болон худалдан авалт хийхдээ та дараах мэдээллийг өгнө:
            </p>
            <ul style="line-height: 1.8; color: #374151; margin-left: 20px; margin-bottom: 15px;">
                <li>Нэр</li>
                <li>Имэйл хаяг</li>
                <li>Төлбөрийн мэдээлэл (банкны шилжүүлэг)</li>
            </ul>

            <h3 style="margin-top: 20px; margin-bottom: 10px;">2.2 Автоматаар цуглуулагдах мэдээлэл</h3>
            <p style="line-height: 1.8; color: #374151;">
                Таны сайтад зочлох үед дараах мэдээлэл автоматаар цуглуулагдана:
            </p>
            <ul style="line-height: 1.8; color: #374151; margin-left: 20px;">
                <li>IP хаяг</li>
                <li>Хөтөч болон төхөөрөмжийн мэдээлэл</li>
                <li>Зочилсон хуудсууд</li>
                <li>Цагийн хугацаа</li>
            </ul>
        </section>

        <!-- Мэдээлэл ашиглах -->
        <section style="margin-bottom: 40px;">
            <h2 style="color: #2563eb; margin-bottom: 20px;">3. Мэдээллийг хэрхэн ашигладаг вэ?</h2>
            <p style="line-height: 1.8; color: #374151; margin-bottom: 15px;">
                Бид таны мэдээллийг дараах зорилгоор ашигладаг:
            </p>
            <ul style="line-height: 1.8; color: #374151; margin-left: 20px;">
                <li>✅ Захиалга боловсруулах болон template хүргэх</li>
                <li>✅ Хэрэглэгчийн дэмжлэг үзүүлэх</li>
                <li>✅ Төлбөрийн мэдээлэл илгээх</li>
                <li>✅ Шинэ бүтээгдэхүүний мэдээлэл илгээх (зөвшөөрөл авсан тохиолдолд)</li>
                <li>✅ Сайтын аюулгүй байдал хангах</li>
                <li>✅ Үйлчилгээгээ сайжруулах</li>
            </ul>
        </section>

        <!-- Мэдээлэл хуваалцах -->
        <section style="margin-bottom: 40px;">
            <h2 style="color: #2563eb; margin-bottom: 20px;">4. Мэдээлэл хуваалцах</h2>
            <p style="line-height: 1.8; color: #374151; margin-bottom: 15px;">
                Бид таны хувийн мэдээллийг дараах тохиолдолд л гуравдагч этгээдэд дамжуулна:
            </p>
            <ul style="line-height: 1.8; color: #374151; margin-left: 20px;">
                <li>🏦 Төлбөрийн системүүд (банк, QPay гэх мэт) - зөвхөн гүйлгээ боловсруулахад</li>
                <li>📧 Имэйл үйлчилгээ үзүүлэгчид - зөвхөн имэйл илгээхэд</li>
                <li>⚖️ Хуулийн шаардлагаар</li>
            </ul>
            <p style="line-height: 1.8; color: #374151; margin-top: 15px;">
                <strong>Бид таны мэдээллийг хэзээ ч худалдахгүй, түрээслэхгүй.</strong>
            </p>
        </section>

        <!-- Cookie -->
        <section style="margin-bottom: 40px;">
            <h2 style="color: #2563eb; margin-bottom: 20px;">5. Cookies</h2>
            <p style="line-height: 1.8; color: #374151;">
                Манай сайт cookies ашигладаг. Энэ нь:
            </p>
            <ul style="line-height: 1.8; color: #374151; margin-left: 20px; margin-bottom: 15px;">
                <li>🍪 Нэвтрэх төлөв хадгалах</li>
                <li>🍪 Таны сонголтыг санах</li>
                <li>🍪 Сайтын ашиглалтыг шинжлэх</li>
            </ul>
            <p style="line-height: 1.8; color: #374151;">
                Та хөтөч дээрээ cookies-г идэвхгүй болгож болно, гэхдээ зарим функц ажиллахгүй болж магадгүй.
            </p>
        </section>

        <!-- Аюулгүй байдал -->
        <section style="margin-bottom: 40px;">
            <h2 style="color: #2563eb; margin-bottom: 20px;">6. Аюулгүй байдал</h2>
            <p style="line-height: 1.8; color: #374151;">
                Бид таны мэдээллийг хамгаалахын тулд дараах арга хэмжээг авч байна:
            </p>
            <ul style="line-height: 1.8; color: #374151; margin-left: 20px;">
                <li>🔒 HTTPS шифрлэлт</li>
                <li>🔒 Нууц үгийн hash хийх</li>
                <li>🔒 Аюулгүй серверт хадгалах</li>
                <li>🔒 Хандах эрхийн хяналт</li>
            </ul>
        </section>

        <!-- Таны эрх -->
        <section style="margin-bottom: 40px;">
            <h2 style="color: #2563eb; margin-bottom: 20px;">7. Таны эрх</h2>
            <p style="line-height: 1.8; color: #374151; margin-bottom: 15px;">
                Та дараах эрхтэй:
            </p>
            <ul style="line-height: 1.8; color: #374151; margin-left: 20px;">
                <li>✅ Өөрийн мэдээллээ харах</li>
                <li>✅ Мэдээллээ засварлах</li>
                <li>✅ Бүртгэлээ устгах</li>
                <li>✅ Маркетингийн имэйлээс татгалзах</li>
                <li>✅ Өөрийн мэдээллийн хуулбар авах</li>
            </ul>
        </section>

        <!-- Хүүхдийн нууцлал -->
        <section style="margin-bottom: 40px;">
            <h2 style="color: #2563eb; margin-bottom: 20px;">8. Хүүхдийн нууцлал</h2>
            <p style="line-height: 1.8; color: #374151;">
                Манай сайт нь 18 наснаас доош хүүхдэд зориулагдаагүй. Бид санаатайгаар
                18 наснаас доош хүмүүсээс хувийн мэдээлэл цуглуулдаггүй.
            </p>
        </section>

        <!-- Өөрчлөлт -->
        <section style="margin-bottom: 40px;">
            <h2 style="color: #2563eb; margin-bottom: 20px;">9. Бодлогын өөрчлөлт</h2>
            <p style="line-height: 1.8; color: #374151;">
                Бид энэхүү нууцлалын бодлогыг цаг үед шинэчилж болно. Томоохон өөрчлөлт орвол
                имэйлээр мэдэгдэх болно. Шинэчлэгдсэн бодлого нь энэ хуудсанд нийтлэгдэнэ.
            </p>
        </section>

        <!-- Холбоо барих -->
        <section style="background: #f3f4f6; padding: 30px; border-radius: 10px; border-left: 4px solid #2563eb;">
            <h2 style="color: #2563eb; margin-bottom: 20px;">10. Холбоо барих</h2>
            <p style="line-height: 1.8; color: #374151; margin-bottom: 15px;">
                Нууцлалын бодлоготой холбоотой асуулт байвал:
            </p>
            <p style="line-height: 1.8; color: #374151;">
                📧 Имэйл: <a href="mailto:<?php echo ADMIN_EMAIL; ?>" style="color: #2563eb;"><?php echo ADMIN_EMAIL; ?></a><br>
                🌐 Сайт: <a href="<?php echo SITE_URL; ?>" style="color: #2563eb;"><?php echo SITE_URL; ?></a><br>
                📞 Утас: +81 80 0000 0000
            </p>
        </section>

    </div>

    <!-- CTA -->
    <div style="text-align: center; margin-top: 40px;">
        <a href="<?php echo SITE_URL; ?>/templates.php" class="btn btn-primary">
            Template-үүд үзэх →
        </a>
    </div>
</div>
<?php include 'includes/footer.php'; ?>