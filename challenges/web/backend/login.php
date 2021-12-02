<?php

require_once('include.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $pdo = get_pdo();

    $res = $pdo->query(sprintf(
        'SELECT * FROM backend_users WHERE username = %s AND password = %s',
        $pdo->quote($username),
        $pdo->quote($password)
    ), PDO::FETCH_ASSOC);
    $row = $res->fetch();
    if ($row && $row['username'] == $username) {
        $_SESSION['user_id'] = $row['id'];
        header('Location: /b8ck3nd/index.php');
        exit();
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>後台管理登入</title>
    <?php
        require_once('frontend_lib.php')
    ?>
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
            歡迎回來
            <div class="sub header">很高興能夠再次見到你</div>
        </h1>

        <div class="ts centered secondary segment">
            <form class="ts form" method="POST">
                <div class="field">
                    <label>帳號</label>
                    <input name="username" type="text" placeholder="" value="">
                </div>

                <div class="field">
                    <label>密碼</label>
                    <input name="password" type="password" placeholder="" value="">
                </div>

                <button type="submit" class="ts positive fluid button">登入</button>
            </form>
        </div>
    </div>
</body>
</html>