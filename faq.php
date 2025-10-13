<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$page_title = "Түгээмэл асуултууд";
include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container" style="margin-top: 40px; margin-bottom: 60px; max-width: 900px;">
    
    <div style="text-align: center; margin-bottom: 60px;">
        <h1 style="font-size: 48px; margin-bottom: 20px;">Түгээмэл асуултууд</h1>
        <p style="font-size: 20px; color: #6b7280;">
            Хамгийн их асуугддаг асуултуудын хариулт
        </p>
    </div>
    
    <?php
    $faqs = [
        [
            'q' => 'Template-үүд ямар хэлээр бичигдсэн бэ?',
            'a' => 'Бүх template-үүд HTML, CSS, JavaScript (болон React) ашиглан бичигдсэн. PHP backend-тэй template-үүд бас байдаг.'
        ],
        [
            'q' => 'Төлбөрийн аргууд юу вэ?',
            'a' => 'Бид банкны шилжүүлэг, QPay, болон картын төлбөр хүлээн авдаг.'
        ],
        [
            'q' => 'Template-г хэдэн удаа татаж болох вэ?',
            'a' => 'Худалдаж авсны дараа хязгааргүй удаа татах боломжтой. Download линк 30 хоног хүчинтэй байна.'
        ],
        [
            'q' => 'Дэмжлэг үйлчилгээ үнэгүй юу?',
            'a' => 'Тийм ээ! Худалдаж авсан template-дээ 24/7 дэмжлэг үнэгүй авах боломжтой.'
        ],
        [
            'q' => 'Буцаан олголт хийдэг үү?',
            'a' => 'Худалдаж авснаас хойш 14 хоногийн дотор техникийн алдаа илэрвэл буцаан олголт хийнэ.'
        ],
        [
            'q' => 'Template-г хэд хэдэн website дээр ашиглаж болох уу?',
            'a' => 'Нэг лиценз нь нэг төслийн хувьд хүчинтэй. Хэрэв олон төсөлд ашиглах бол тус бүрд нь худалдаж авах шаардлагатай.'
        ]
    ];
    
    foreach($faqs as $index => $faq):
    ?>
    
    <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <h3 style="color: #2563eb; margin-bottom: 15px; font-size: 20px;">
            ❓ <?php echo $faq['q']; ?>
        </h3>
        <p style="color: #4b5563; line-height: 1.8; margin: 0;">
            <?php echo $faq['a']; ?>
        </p>
    </div>
    
    <?php endforeach; ?>
    
    <!-- Contact CTA -->
    <div style="background: #f3f4f6; padding: 40px; border-radius: 10px; text-align: center; margin-top: 60px;">
        <h2 style="margin-bottom: 15px;">Таны асуулт энд байхгүй юу?</h2>
        <p style="color: #6b7280; margin-bottom: 25px;">Бидэнд холбогдоорой, бид тантай холбогдох болно</p>
        <a href="<?php echo SITE_URL; ?>/contact.php" class="btn btn-primary">Холбоо барих</a>
    </div>
    
</div>

<?php include 'includes/footer.php'; ?>