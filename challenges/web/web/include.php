<?php

/*
                  _     ___   ___  _  __                
 __   __ __   __ | |   / _ \ / _ \| |/ / __   __ __   __
 \ \ / / \ \ / / | |  | | | | | | | ' /  \ \ / / \ \ / /
  \ V /   \ V /  | |__| |_| | |_| | . \   \ V /   \ V / 
   \_/     \_/   |_____\___/ \___/|_|\_\   \_/     \_/  
                                                        

   scvsDP{no.1_path_traverse_to_the_m00n}

 */


define('IMAGE_PATH', '/usr/share/nginx/images/');

define('MYSQL_HOST', 'mysql');
define('MYSQL_USER', 'web_user');
define('MYSQL_PASSWORD', 'n%6GZgt*hH[+p7vJ');
define('MYSQL_DATABASE', 'web');

define('ORDER_STATUS_PICKING', 'PICKING');
define('ORDER_STATUS_PACKING', 'PACKING');
define('ORDER_STATUS_SENDING', 'SENDING');
define('ORDER_STATUS_DELIVERING', 'DELIVERING');
define('ORDER_STATUS_ARRIVED', 'ARRIVED');
define('ORDER_STATUS_FINISH', 'FINISH');

define('DEFAULT_LANGUAGE', 'zh-tw');
define('ALLOWED_LANGUAGE', 'zh-tw');

function session_start_once() {
    if (!isset($_SESSION)) { 
        session_start();
    }
}

session_start_once();


if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = DEFAULT_LANGUAGE;
}

require_once('langs/' . $_SESSION['lang'] . '.php');

require_once('qrcode.php');

function base64_urlsafe_encode($input) {
    return strtr(base64_encode($input), '+/', '._');
}

function base64_urlsafe_decode($input) {
    return base64_decode(strtr($input, '._', '+/'));
}


$GLOBALS['_pdo'] = false;

function get_pdo() {
    if ($GLOBALS['_pdo']) {
        return $GLOBALS['_pdo'];
    }
    try {
        $pdo = new PDO(
                    'mysql:host='.MYSQL_HOST.';dbname='.MYSQL_DATABASE.';charset=utf8mb4',
                    MYSQL_USER, MYSQL_PASSWORD,
                    array(
                        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'utf8mb4\' COLLATE \'utf8mb4_unicode_ci\';',
                        PDO::ATTR_TIMEOUT => 2
                    ));
        $GLOBALS['_pdo'] = $pdo;
    } catch (Exception $e) {
        http_response_code(500);
        die("Failed to connect database. Please contact the administrtor.");
    }
    return $pdo;
}

function get_post_param($key, $default=null) {
    if (isset($_POST[$key])) {
        return $_POST[$key];
    } else {
        return $default;
    }
}

function get_get_param($key, $default=null) {
    if (isset($_GET[$key])) {
        return $_GET[$key];
    } else {
        return $default;
    }
}

function get_client_ip() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function random_str(
    int $length = 64,
    string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
): string {
    if ($length < 1) {
        throw new \RangeException("Length must be a positive integer");
    }
    $pieces = [];
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $pieces []= $keyspace[random_int(0, $max)];
    }
    return implode('', $pieces);
}

function get_sig_hash($data) {
    $pdo = get_pdo();
    $res = $pdo->query("SELECT `value` FROM options WHERE `key` = 'sig_secret' LIMIT 1", PDO::FETCH_ASSOC);
    $row = $res->fetch();
    if (!$row) {
        $secret = random_str(64);
        $pdo->exec("INSERT INTO options VALUES ('sig_secret', '".$secret."'), ('sig_algorithm', 'sha256')");
    } else {
        $secret = $row['value'];
    }
    $res = $pdo->query("SELECT `value` FROM options WHERE `key` = 'sig_algorithm' LIMIT 1", PDO::FETCH_ASSOC);
    $algo = $res->fetch()['value'];
    return hash_hmac($algo, $data, $secret);
}

function get_timezone() {
    $pdo = get_pdo();
    $res = $pdo->query("SELECT `value` FROM options WHERE `key` = 'timezone' LIMIT 1", PDO::FETCH_ASSOC);
    $row = $res->fetch();
    if (!$row) {
        $pdo->exec("INSERT INTO options VALUES ('timezone', 'Asia/Taipei')");
        $timezone = 'Asia/Taipei';
    } else {
        $timezone = $row['value'];
    }
    return $timezone;
}

define('TIMEZONE', get_timezone());
date_default_timezone_set(TIMEZONE);

function endsWith( $haystack, $needle ) {
    $length = strlen( $needle );
    if( !$length ) {
        return true;
    }
    return substr( $haystack, -$length ) === $needle;
}


function order_status_to_text($status) {
    $text_arr = [
        ORDER_STATUS_PICKING => '撿貨',
        ORDER_STATUS_PACKING => '包裝中',
        ORDER_STATUS_SENDING => '等待貨運士取貨',
        ORDER_STATUS_DELIVERING => '配送中',
        ORDER_STATUS_ARRIVED => '貨物已送達',
        ORDER_STATUS_FINISH => '完成'
    ];
    return $text_arr[$status];
}
