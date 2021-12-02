<?php

require_once('include.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = get_post_param('name', '');
    $email = get_post_param('email', '');
    $phone = get_post_param('phone', '');
    $address = get_post_param('address', '');

    if (empty($name) || empty($email) || empty($phone) || empty($address)) {
        $_SESSION['error_msg'] = '尚有訂單欄位未填寫完成。';
        require_once('error.php');
    }

    $sig = random_str(64);
    $sig_hash = get_sig_hash($sig);
    $pdo = get_pdo();
    $sql = sprintf(
        'INSERT INTO orders (name, email, phone, address, order_date, sig_hash) VALUES (%s, %s, %s, %s, %s, %s)',
        $pdo->quote($name),
        $pdo->quote($email),
        $pdo->quote($phone),
        $pdo->quote($address),
        $pdo->quote(date('Y-m-d')),
        $pdo->quote($sig_hash)
    );
    $result = $pdo->exec($sql);
    if ($result == 1) {
        $id = $pdo->lastInsertId();
        require_once('success.php');
    } else {
        $_SESSION['error_msg'] = '無法處理訂單請求，請洽系統管理員。';
        header('Location: /error.php');
        exit();
    }

} else {
    header('Location: /');
}