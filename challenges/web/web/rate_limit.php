<?php

require_once('include.php');

$pdo = get_pdo();
$ip = $_SERVER['REMOTE_ADDR'];
$res = $pdo->query('SELECT last_visit, visit_times FROM rate_limit WHERE ip ='.$pdo->quote($ip), PDO::FETCH_ASSOC);
$row = $res->fetch();
if (!$row) {
    $pdo->exec(sprintf(
        'INSERT INTO rate_limit (ip, last_visit, visit_times) VALUES (%s, %s, %s)',
        $pdo->quote($ip),
        $pdo->quote(date('Y-m-d h:i:s')),
        0,
    ));
}

$t1 = new DateTime(date('Y-m-d h:i:s'));
$t2 = new DateTime($row['last_visit']);
$dt = $t1->getTimestamp() - $t2->getTimestamp();
if ($dt <= 30) {
    $times = intval($row['visit_times']);
    if ($times >= 15) {
        $_SESSION['error_msg'] = '系統忙碌中，請稍後再試';
        require_once('error.php');
    }
    $pdo->exec('UPDATE rate_limit SET visit_times = visit_times + 1 WHERE ip = '.$pdo->quote($ip));
} else {
    $pdo->exec(sprintf(
        'UPDATE rate_limit SET visit_times=0, last_visit=%s WHERE ip = %s',
        $pdo->quote($t1->format('Y-m-d H:i:s')),
        $pdo->quote($ip)
    ));
}
