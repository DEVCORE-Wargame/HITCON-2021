<?php

if (!isset($id) || !isset($sig)) {
    header('Location: /');
    exit();
}

$url = $_SERVER['REQUEST_SCHEME'] 
    . '://' 
    . $_SERVER['HTTP_HOST'] 
    . '/order.php?id=' . $id . '&sig=' . $sig;

$generator = new QRCode($url, []);
$image = $generator->render_image();

ob_start();
imagepng($image);
imagedestroy($image);
$qrcode = base64_encode(ob_get_contents());
ob_end_clean(); 

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.rawgit.com/TeaMeow/TocasUI/2.3.2/dist/tocas.css" rel='stylesheet'>
    <script src="https://cdn.rawgit.com/TeaMeow/TocasUI/2.3.2/dist/tocas.js"></script>
    <title>訂購成功</title>
    <style type="text/css">
        .segment {
            max-width: 600px;
        }
    </style>
</head>
<body>
    <div class="ts narrow container">
        <br />
        <br />
        <h1 class="ts center aligned header">
            訂購成功
        </h1>

        <div class="ts centered secondary segment">
            欲查詢訂單資料、出貨狀態，請使用以下網址：
            <br />
            <a href="<?=htmlspecialchars($url) ?>" target="_blank"><?=htmlspecialchars($url) ?></a>
            <br />
            <img src="data:image/png;base64,<?=$qrcode ?>" alt="">
            <br />
            <br />
            此網址僅供訂購本人使用，請避免洩漏給其他人並應妥善保存。
            <br />
            若遺失此網址，請洽詢網站管理員。
        </div>
    </div>
</body>
</html>

<?php 
    exit();
?>