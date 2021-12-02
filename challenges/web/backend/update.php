<?php

require_once('include.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_SERVER['HTTP_ORIGIN'] == $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST']) {
        $id = $_POST['id'];
        $column = $_POST['column'];
        $pdo = get_pdo();
        $id = intval($id);
        if ($column == 'status') {
            $status = $_POST['status'];
            $status_arr = [
                ORDER_STATUS_PICKING, ORDER_STATUS_PACKING, ORDER_STATUS_SENDING,
                ORDER_STATUS_DELIVERING, ORDER_STATUS_ARRIVED, ORDER_STATUS_FINISH
            ];
            if (!in_array($status, $status_arr, true)) {
                http_response_code(400);
                exit();
            }
            $pdo->exec(sprintf(
                'UPDATE orders SET status = %s WHERE id = %s',
                $pdo->quote($status),
                $id
            ));
        } else if ($column == 'note') {
            $note = $_POST['note'];
            $pdo->exec(sprintf(
                'UPDATE orders SET note = %s WHERE id = %s',
                $pdo->quote($note),
                $id
            ));
        }
        
        echo 'OK';
        exit();
    } else {
        http_response_code(400);
        exit();
    }
}

