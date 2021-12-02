<?php

require_once('include.php');

if (!isset($is_from_print)) {
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <?php
        require_once('frontend_lib.php')
    ?>

    <script>
        function do_print() {
            document.getElementById('pdf_button').style.display = 'block';
            window.print();
        }
    </script>
</head>
<body>
    <br />

    <div class="ts narrow container grid">
        <div class="sixteen wide column">
            <h3 class="ts dividing header">
                <i class="ticket icon"></i>收據明細
            </h3>
            <br />
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
    </div>
    <div><div><div></div></div></div>
</body>
</html>