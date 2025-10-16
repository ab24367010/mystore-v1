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
            <h2 style="color: #2563eb; margin-bottom: 20px;">5. Cookie бодлого</h2>
            <p style="line-height: 1.8; color: #374151; margin-bottom: 20px;">
                Манай вэбсайт нь GDPR болон CCPA хуулийн дагуу янз бүрийн төрлийн cookie ашигладаг.
                Бид таны туршлагыг сайжруулах болон хуулийн шаардлагыг хангахын тулд зөвшөөрлийн систем нэвтрүүлсэн.
            </p>

            <h3 style="margin-top: 20px; margin-bottom: 15px; color: #1f2937;">5.1 Зайлшгүй шаардлагатай Cookie</h3>
            <div style="background: #f0fdf4; padding: 20px; border-radius: 8px; border-left: 4px solid #10b981; margin-bottom: 20px;">
                <p style="line-height: 1.8; color: #374151; margin-bottom: 10px;">
                    <strong>Зорилго:</strong> Вэбсайтын үндсэн функцуудад шаардлагатай
                </p>
                <p style="line-height: 1.8; color: #374151; margin-bottom: 10px;">
                    <strong>Жишээ нь:</strong>
                </p>
                <ul style="line-height: 1.8; color: #374151; margin-left: 20px;">
                    <li><code>MYSTORE_SESSION</code> - Нэвтрэх төлөв, аюулгүй байдал</li>
                    <li><code>cookie_consent</code> - Cookie зөвшөөрлийн сонголт</li>
                </ul>
                <p style="line-height: 1.8; color: #374151; margin-top: 10px;">
                    <strong>Хадгалагдах хугацаа:</strong> Сешн дуустал (эсвэл 1 цаг)
                </p>
                <p style="line-height: 1.8; color: #374151; margin-top: 10px;">
                    <strong>Зөвшөөрөл:</strong> <span style="color: #10b981; font-weight: 600;">Шаардлагагүй</span> (зайлшгүй шаардлагатай)
                </p>
            </div>

            <h3 style="margin-top: 20px; margin-bottom: 15px; color: #1f2937;">5.2 Функциональ Cookie</h3>
            <div style="background: #eff6ff; padding: 20px; border-radius: 8px; border-left: 4px solid #3b82f6; margin-bottom: 20px;">
                <p style="line-height: 1.8; color: #374151; margin-bottom: 10px;">
                    <strong>Зорилго:</strong> Нэмэлт функц болон таны сонголтыг санах
                </p>
                <p style="line-height: 1.8; color: #374151; margin-bottom: 10px;">
                    <strong>Жишээ нь:</strong>
                </p>
                <ul style="line-height: 1.8; color: #374151; margin-left: 20px;">
                    <li>Google Maps (байршил харуулах)</li>
                    <li>Хэл тохиргоо</li>
                    <li>Харагдац тохируулга</li>
                </ul>
                <p style="line-height: 1.8; color: #374151; margin-top: 10px;">
                    <strong>Гуравдагч этгээд:</strong> Google Maps API
                </p>
                <p style="line-height: 1.8; color: #374151; margin-top: 10px;">
                    <strong>Зөвшөөрөл:</strong> <span style="color: #3b82f6; font-weight: 600;">Шаардлагатай</span> (сонголттой)
                </p>
            </div>

            <h3 style="margin-top: 20px; margin-bottom: 15px; color: #1f2937;">5.3 Шинжилгээний Cookie</h3>
            <div style="background: #fff7ed; padding: 20px; border-radius: 8px; border-left: 4px solid #f97316; margin-bottom: 20px;">
                <p style="line-height: 1.8; color: #374151; margin-bottom: 10px;">
                    <strong>Зорилго:</strong> Вэбсайтын ашиглалтыг ойлгох, сайжруулах
                </p>
                <p style="line-height: 1.8; color: #374151; margin-bottom: 10px;">
                    <strong>Жишээ нь:</strong>
                </p>
                <ul style="line-height: 1.8; color: #374151; margin-left: 20px;">
                    <li>Google Analytics (одоогоор суулгаагүй)</li>
                    <li>Хуудас үзэлтийн тоо</li>
                    <li>Хэрэглэгчийн зан байдал</li>
                </ul>
                <p style="line-height: 1.8; color: #374151; margin-top: 10px;">
                    <strong>Мэдээлэл:</strong> Бүх мэдээлэл anonymous байна
                </p>
                <p style="line-height: 1.8; color: #374151; margin-top: 10px;">
                    <strong>Зөвшөөрөл:</strong> <span style="color: #f97316; font-weight: 600;">Шаардлагатай</span> (сонголттой)
                </p>
            </div>

            <h3 style="margin-top: 20px; margin-bottom: 15px; color: #1f2937;">5.4 Маркетингийн Cookie</h3>
            <div style="background: #faf5ff; padding: 20px; border-radius: 8px; border-left: 4px solid #a855f7; margin-bottom: 20px;">
                <p style="line-height: 1.8; color: #374151; margin-bottom: 10px;">
                    <strong>Зорилго:</strong> Танд хамааралтай зар сурталчилгаа харуулах
                </p>
                <p style="line-height: 1.8; color: #374151; margin-bottom: 10px;">
                    <strong>Жишээ нь:</strong>
                </p>
                <ul style="line-height: 1.8; color: #374151; margin-left: 20px;">
                    <li>Facebook Pixel (одоогоор суулгаагүй)</li>
                    <li>Google Ads (одоогоор суулгаагүй)</li>
                    <li>Retargeting pixels</li>
                </ul>
                <p style="line-height: 1.8; color: #374151; margin-top: 10px;">
                    <strong>Зөвшөөрөл:</strong> <span style="color: #a855f7; font-weight: 600;">Шаардлагатай</span> (сонголттой)
                </p>
            </div>

            <h3 style="margin-top: 30px; margin-bottom: 15px; color: #1f2937;">5.5 Cookie удирдах</h3>
            <p style="line-height: 1.8; color: #374151; margin-bottom: 15px;">
                Та өөрийн cookie тохиргоог дараах байдлаар удирдаж болно:
            </p>
            <ul style="line-height: 1.8; color: #374151; margin-left: 20px; margin-bottom: 15px;">
                <li>🎛️ Манай cookie banner дээрх <strong>"Тохируулах"</strong> товчийг дарах</li>
                <li>🎛️ Хөтөч дээрээ cookie-г устгах</li>
                <li>🎛️ Хөтчийн тохиргоо дээр cookie-г идэвхгүй болгох</li>
            </ul>

            <div style="background: #fef2f2; padding: 20px; border-radius: 8px; border-left: 4px solid #ef4444; margin-top: 20px;">
                <p style="line-height: 1.8; color: #374151; margin: 0;">
                    <strong>⚠️ Анхааруулга:</strong> Зайлшгүй cookie-г идэвхгүй болговол сайт зөв ажиллахгүй болж магадгүй
                    (жишээ нь: нэвтрэх, худалдан авалт хийх боломжгүй болно).
                </p>
            </div>

            <h3 style="margin-top: 30px; margin-bottom: 15px; color: #1f2937;">5.6 GDPR & CCPA хуулийн дагуу</h3>
            <p style="line-height: 1.8; color: #374151; margin-bottom: 10px;">
                Бид дараах хуулиудыг дагаж мөрддөг:
            </p>
            <ul style="line-height: 1.8; color: #374151; margin-left: 20px; margin-bottom: 15px;">
                <li>🇪🇺 <strong>GDPR</strong> (General Data Protection Regulation) - ЕХ-ны хэрэглэгчид</li>
                <li>🇺🇸 <strong>CCPA</strong> (California Consumer Privacy Act) - Калифорниа хэрэглэгчид</li>
                <li>🇬🇧 <strong>UK GDPR</strong> - Их Британийн хэрэглэгчид</li>
            </ul>
            <p style="line-height: 1.8; color: #374151;">
                Та эдгээр cookie-г зөвшөөрөх эсвэл татгалзах эрхтэй. Манай систем таны сонголтыг
                хадгалж, 365 хоногийн турш санах болно.
            </p>

            <div style="background: #f0f9ff; padding: 20px; border-radius: 8px; margin-top: 20px; text-align: center;">
                <p style="line-height: 1.8; color: #374151; margin-bottom: 15px;">
                    Та cookie тохиргоогоо дараах товчийг дарж өөрчилж болно:
                </p>
                <button onclick="CookieConsent.openSettings()" class="btn btn-primary">
                    Cookie тохиргоо нээх
                </button>
            </div>
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