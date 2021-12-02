<?php


require_once('../frontend/include.php');

session_start_once();

if (!in_array(get_client_ip(), ['127.0.0.1', '172.18.11.89'], true)) {
    header('Location: /');
    exit();
}

if (!isset($_SESSION['user_id'])) {
    if (!endsWith($_SERVER['SCRIPT_FILENAME'], 'login.php')) {
        header('Location: /b8ck3nd/login.php');
        exit();
    }
}


