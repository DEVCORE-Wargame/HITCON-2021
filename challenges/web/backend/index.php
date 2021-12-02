<?php

require_once('include.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['description'])) {
        $pdo = get_pdo();
        $pdo->exec(sprintf(
            'UPDATE items SET description = %s WHERE id = 1',
            $pdo->quote($_POST['description'])
        ));
    }
    header('Location: index.php');
    exit();
}

$pdo = get_pdo();
$res = $pdo->query('SELECT * FROM items WHERE id = 1', PDO::FETCH_ASSOC);
$row = $res->fetch();

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <title>管理後台</title>
    <?php
        require_once('frontend_lib.php')
    ?>
    <style type="text/css">
        body {
            padding: 70px 0;
        }
        .ck.ck-content {
            font-size: 1em;
            line-height: 1.6em;
            margin-bottom: 0.8em;
            min-height: 40rem;
        }
    </style>
</head>
<body>
    <div class="ts top fixed inverted borderless large menu">
        <div class="ts narrow container">
            <a href="index.php" class="item">修改商品描述</a>
            <a href="orders.php" class="item">訂單列表</a>
            <a href="logout.php" class="right item">登出</a>
        </div>
    </div>

    <div class="ts narrow container">
        <div class="sixteen wide column">
            <h3 class="ts header">
                修改商品描述
            </h3>
            <div>
                按下「儲存」按鈕後會立刻更新到首頁上，請再次確認內容無誤後再儲存！
                <br />
                <br />
                <?php
                    echo shell_exec('/readflag2');
                ?>
            </div>
            <br />
            <br />
        </div>
    </div>

    <div class="ts narrow container">
        <form method="POST">
            <div class="sixteen wide column">
                <div class="centered">
                    <textarea name="description" id="editor"><?=htmlspecialchars($row['description']) ?></textarea>
                </div>
            </div>
            <div class="sixteen wide column">
                <div class="ts secondary fitted menu">
                    <div class="right menu">
                        <div class="item">
                            <div class="ts relaxed separated buttons">
                                <button class="ts button" type="submit">
                                <i class="download icon"></i> 儲存
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        ClassicEditor
            .create( document.querySelector( '#editor' ), {
                cloudServices: {
                    tokenUrl: '/b8ck3nd/upload.php',
                    uploadUrl: '/b8ck3nd/upload.php'
                }
            } )
            .then( editor => {
                window.editor = editor;
            } )
            .catch( err => {
                console.error( err.stack );
            } );
    </script>
</body>
</html>
