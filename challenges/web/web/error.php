
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.rawgit.com/TeaMeow/TocasUI/2.3.2/dist/tocas.css" rel='stylesheet'>
    <script src="https://cdn.rawgit.com/TeaMeow/TocasUI/2.3.2/dist/tocas.js"></script>
    <title>錯誤</title>
    <style type="text/css">
        .segment {
            max-width: 300px;
        }
    </style>
</head>
<body>
    <div class="ts narrow container">
        <br />
        <br />
        <h1 class="ts center aligned header">
            錯誤
        </h1>

        <div class="ts centered secondary segment">
            <?=$_SESSION['error_msg'] ?>
            <?php
                unset($_SESSION['error_msg']);
            ?>
        </div>

        <div class="ts centered basic segment">
            <a href="#" onclick="history.go(-1); return false;"><i class="reply icon"></i>回到上一頁</a>
        </div>
        
    </div>
</body>
</html>

<?php 
    exit();
?>