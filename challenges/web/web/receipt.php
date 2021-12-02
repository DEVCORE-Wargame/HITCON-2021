<?php

require_once('include.php');

$id = get_get_param('id', '');
$sig = get_get_param('sig', '');

if (empty($id) || empty($sig)) {
    header('Location: /');
    exit();
}

$id = intval($id);
$pdo = get_pdo();
$res = $pdo->query('SELECT * FROM orders WHERE id = '.$id, PDO::FETCH_ASSOC);
$order = $res->fetch();

if (!$order) {
    $_SESSION['error_msg'] = '找不到此訂單';
    require_once('error.php');
}

$sig_hash = get_sig_hash($sig);
if ($sig_hash && $sig_hash != $order['sig_hash']) {
    $_SESSION['error_msg'] = '訂單網址參數錯誤';
    require_once('error.php');
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>收據明細</title>
    <?php
        require_once('frontend_lib.php')
    ?>

    <script>
        function do_print() {
            document.getElementById('pdf_button').style.display = 'block';
            window.print();
        }

        function do_export_pdf() {
            let qs = window.location.search;
            if (!qs.startsWith('?')) {
                qs = '?' + qs;
            }
            window.open('/print.php' + qs, '_blank').focus();
        }
    </script>
</head>
<body>
    <br />
    <br />
    <div class="ts narrow container grid">
        <div class="sixteen wide column">
            <h3 class="ts dividing header">
                <i class="file text icon"></i>收據明細
            </h3>
        </div>

        <div class="sixteen wide column">
            <h5 class="ts header">訂單資訊</h5>
            <strong>訂單編號</strong>: #<?=htmlspecialchars($order['id']) ?><br />
            <strong>訂購日期</strong>: <?=htmlspecialchars($order['order_date']) ?><br />
            <strong>開立日期</strong>: <?=htmlspecialchars($order['order_date']) ?><br />
            <br />
        </div>


        <div class="sixteen wide column">
            <h5 class="ts header">收件人</h5>
            <?=$order['name'] ?><br>
            電子郵件: <?=htmlspecialchars($order['email']) ?><br />
            連絡電話: <?=htmlspecialchars($order['phone']) ?><br />
            收件地址: <?=htmlspecialchars($order['address']) ?><br />
        </div>

        <div class="sixteen wide column">
            <br>
            <table class="ts very basic striped table">
                <thead>
                    <tr>
                        <th>商品名稱</th>
                        <th>數量</th>
                        <th>總計</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>HP Color LaserJet Pro M283fdw 彩色雷射多功能事務機</td>
                        <td>1</td>
                        <td>NT$ 0</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="sixteen wide column">
            <br />
            <div class="ts secondary fitted menu">
                <div class="right menu">
                    <button class="ts button" onclick="do_print()">
                        <i class="print icon"></i> 列印
                    </button>
                </div>
            </div>
        </div>

        <div id="pdf_button" class="sixteen wide column" style="display: none;">
            <br />
            <div class="ts secondary fitted menu">
                <div class="right menu">
                    <span style="display: flex; align-items: center; margin-right: 10px">無法使用瀏覽器列印功能？</span>
                    <button class="ts button" onclick="do_export_pdf()">
                        <i class="print icon"></i> 匯出 PDF
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>