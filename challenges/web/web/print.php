<?php

require_once('include.php');
require_once('third_party/vendor/autoload.php');

//require_once('rate_limit.php');
// rate limit is not working, use random sleep as a workaround
sleep(random_int(0, 2));

$is_from_print = true;

$id = get_get_param('id', '');
$sig = get_get_param('sig', '');
$sig_hash = get_sig_hash($sig);
$pdo = get_pdo();
$res = $pdo->query("
    SELECT *
    FROM orders 
    WHERE sig_hash = '$sig_hash' AND id = $id
    LIMIT 1
", PDO::FETCH_ASSOC);

try {
    $order = $res->fetch();
} catch (Error $e) {
    $order = [];
}

ob_start();
include('pdf.php');
$html = ob_get_clean();

$mpdf = new \Mpdf\Mpdf([
    'tempDir' => '/tmp',
    'autoScriptToLang' => true,
    'autoLangToFont' => true,
    'mode' => 'utf-8'
]);
$mpdf->SetTitle('收據明細');
$mpdf->SetSubject('收據明細');
$mpdf->SetAuthor(random_str((random_int(1, 256))));
$mpdf->SetCreator(random_str((random_int(1, 256))));
$mpdf->WriteHTML($html);
$mpdf->Output();
