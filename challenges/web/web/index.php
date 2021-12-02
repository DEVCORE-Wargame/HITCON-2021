<?php

require_once('include.php');

$pdo = get_pdo();
$res = $pdo->query('SELECT * FROM items WHERE id = 1', PDO::FETCH_ASSOC);
$row = $res->fetch();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DEVCORE 感謝祭</title>
    <?php
        require_once('frontend_lib.php')
    ?>
    <style>
        body > .ts.secondary.menu {
            background-color: #f7f7f7;
        }
    </style>
    <script>
        function confirm_submit() {
            if (confirm('即將送出訂單，確定資料無誤？\n送出後即無法修改訂單資料，有問題請洽詢網站管理員。')) {
                
            } else {
                event.preventDefault();
            }
        }
    </script>
</head>
<body>
    <div class="ts fitted slate">
        <div class="ts very narrow container">
            <div class="ts secondary menu">
                <div class="right menu">
                    <a href="/lang.php?lang=zh-tw" class="item">中文</a>
                    <a href="/lang.php?lang=en-us" class="item">English</a>
                </div>
            </div>
        </div>
    </div>

    <div class="ts horizontally fitted fluid slate">
        <div class="ts very narrow container">
            <h1 class="ts header">
                DEVCORE 感謝祭
                <div class="sub header">慶祝 DEVCORE Research Team 榮獲 Pwn2Own Austin 2021 亞軍</div>
            </h1>
        </div>
    </div>

    <div class="ts very narrow container">
        <br />
        <div class="sixteen wide column">
            <h3 class="ts dividing header">
                <i class="thumbs up icon"></i><?=htmlspecialchars($row['title']) ?>
            </h3>
        </div>

        <div class="sixteen wide column">
            <div id="description" style="white-space: pre-wrap;"></div>
        </div>

        <br />
        <hr />
        <br />

        <div class="sixteen wide column">
            <h3 class="ts dividing header">
                <i class="in cart icon"></i>立刻訂購
            </h3>
        </div>

        <br />
        <form class="ts relaxed form" method="POST" action="/submit.php" onsubmit="confirm_submit()">
            <div class="field">
                <label>收貨人姓名</label>
                <input name="name" type="text">
                <small>例如：王小明</small>
            </div>

            <div class="field">
                <label>電子郵件地址</label>
                <input name="email" type="email">
                <small>例如：abcdef@mymail.com</small>
            </div>

            <div class="field">
                <label>電話</label>
                <input name="phone" type="text">
                <small>例如：0912345678</small>
            </div>

            <div class="field">
                <label>收貨地址</label>
                <input name="address" type="text">
                <small>例如：103 台北市松山區ＯＯ路 1 號 2 樓</small>
            </div>

            <button class="ts fluid primary button" type="submit">送出</button>

            <div class="ts center aligned mini basic fitted message">
                按下「送出」表示您同意以上資料均為本人資料，若刻意填寫錯誤或他人資料，您將必須承擔相對應法律責任。
            </div>
        </form>
        <br />
        <br />
        <br />
    </div>

    <script>
        let dirty = decodeURIComponent(atob('<?=base64_encode(rawurlencode($row['description'])) ?>'));
        let clean = DOMPurify.sanitize(dirty);
        document.getElementById('description').innerHTML = clean;
    </script>
</body>
</html>