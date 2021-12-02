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

switch ($order['status']) {
    case ORDER_STATUS_PICKING:
        $step = 1;
        break;
    case ORDER_STATUS_PACKING:
        $step = 2;
        break;
    case ORDER_STATUS_SENDING:
        $step = 3;
        break;
    case ORDER_STATUS_DELIVERING:
        $step = 4;
        break;
    case ORDER_STATUS_ARRIVED:
        $step = 5;
        break;
    case ORDER_STATUS_FINISH:
        $step = 6;
        break;
}



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>訂單資訊</title>
    <?php
        require_once('frontend_lib.php')
    ?>
    <script>
        function show_receipt() {
            let qs = window.location.search;
            if (!qs.startsWith('?')) {
                qs = '?' + qs;
            }
            window.open('/receipt.php' + qs, '_blank').focus();
        }
    </script>
</head>
<body>
    <br />
    <br />
    <div class="ts narrow container grid">
        <div class="sixteen wide column">
            <h3 class="ts dividing header">
                <i class="id card icon"></i>訂單資訊
            </h3>
            <br />
        </div>


        <div class="sixteen wide column">
            <h5 class="ts header">訂單資訊</h5>
            <strong>訂單編號</strong>：#<?=htmlspecialchars($order['id']) ?><br />
            <strong>訂購日期</strong>：<?=htmlspecialchars($order['order_date']) ?><br />
            <strong>商品名稱</strong>：HP Color LaserJet Pro M283fdw 彩色雷射多功能事務機<br />
            <strong>商品數量</strong>：1 台<br />
            <br />
        </div>
    

        <div class="sixteen wide column">
            <h5 class="ts header">收件人</h5>
            <?=htmlspecialchars($order['name']) ?><br>
            電子郵件：<?=htmlspecialchars($order['email']) ?><br />
            連絡電話：<?=htmlspecialchars($order['phone']) ?><br />
            收件地址：<?=htmlspecialchars($order['address']) ?><br />
        </div>

        
        <div class="sixteen wide column">
            <div class="ts secondary fitted menu">
                <div class="right menu">
                    <button class="ts button" onclick="show_receipt()">
                        <i class="file text icon"></i> 查看收據
                    </button>
                </div>
            </div>
        </div>

        <div class="sixteen wide column">
            <br />
            <h3 class="ts dividing header">
                <i class="large icons">
                    <i class="shipping icon"></i>
                    <i class="corner notched circle loading icon"></i>
                </i>
                出貨狀態
            </h3>
            <br />
            <div class="ts primary progress">
                <div class="bar" style="width: <?=(int)(100 * $step / 6)?>%">
                    <span class="text"><i class="horizontally flipped truck icon"></i> <?=(int)(100 * $step / 6)?>%</span>
                </div>
            </div>
        </div>

        <div class="four wide column">
            <div class="ts vertical steps">
                <?php
                    if ($step == 1) {
                        $class = 'active';
                        $icon = 'forward';
                    } else {
                        $class = 'completed';
                        $icon = 'ellipsis horizontal';
                    }
                ?>
                <div class="<?=$class ?> step">
                    <i class="<?=$icon ?> icon"></i>
                    <div class="content">
                        <div class="title">撿貨</div>
                    </div>
                </div>
                <?php
                    if ($step == 2) {
                        $class = 'active';
                        $icon = 'forward';
                    } else if ($step > 2) {
                        $class = 'completed';
                        $icon = '';
                    } else {
                        $class = '';
                        $icon = 'ellipsis horizontal';
                    }
                ?>
                <div class="<?=$class ?> step">
                    <i class="<?=$icon ?> icon"></i>
                    <div class="content">
                        <div class="title">包裝中</div>
                    </div>
                </div>
                <?php
                    if ($step == 3) {
                        $class = 'active';
                        $icon = 'forward';
                    } else if ($step > 3) {
                        $class = 'completed';
                        $icon = '';
                    } else {
                        $class = '';
                        $icon = 'ellipsis horizontal';
                    }
                ?>
                <div class="<?=$class ?> step">
                    <i class="<?=$icon ?> icon"></i>
                    <div class="content">
                        <div class="title">等待貨運士取貨</div>
                    </div>
                </div>
                <?php
                    if ($step == 4) {
                        $class = 'active';
                        $icon = 'forward';
                    } else if ($step > 4) {
                        $class = 'completed';
                        $icon = '';
                    } else {
                        $class = '';
                        $icon = 'ellipsis horizontal';
                    }
                ?>
                <div class="<?=$class ?> step">
                    <i class="<?=$icon ?> icon"></i>
                    <div class="content">
                        <div class="title">配送中</div>
                    </div>
                </div>
                <?php
                    if ($step == 5) {
                        $class = 'active';
                        $icon = 'forward';
                    } else if ($step > 5) {
                        $class = 'completed';
                        $icon = '';
                    } else {
                        $class = '';
                        $icon = 'ellipsis horizontal';
                    }
                ?>
                <div class="<?=$class ?> step">
                    <i class="<?=$icon ?> icon"></i>
                    <div class="content">
                        <div class="title">貨物已送達</div>
                    </div>
                </div>
                <?php
                    if ($step == 6) {
                        $class = 'completed';
                        $icon = '';
                    } else {
                        $class = '';
                        $icon = 'ellipsis horizontal';
                    }
                ?>
                <div class="<?=$class ?> step">
                    <i class="<?=$icon ?> icon"></i>
                    <div class="content">
                        <div class="title">完成</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="twelve wide column">
            <div class="ts slate">
                <h3 class="ts header">貨態補充資訊</h3>
                <br />
                <div style="white-space: pre-wrap;"><?=empty($order['note']) ? '無' : htmlspecialchars($order['note']) ?></div>
            </div>
        </div>
    </div>
    <!-- / 主要網格容器 -->
</body>
</html>