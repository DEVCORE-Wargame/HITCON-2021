<?php

require_once('include.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    header('Content-Type: text/plain');
    echo 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkZha2UgdG9rZW4gZm9yIGNrZWRpdG9yIiwiaWF0IjoxNTE2MjM5MDIyfQ.6nNLxp10uP65V_NFrs5IWuX2tkk6vGQ-oiwYhHNdHgk';
    exit();
}

if (isset($_FILES['file']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
    header('Content-Type: application/json; charset=utf-8');
    $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    $filename = random_str(32).'.'.$ext;
    if (isset($_POST['rename'])) {
        $filename = $_POST['rename'];
    }
    if (isset($_POST['folder'])) {
        $folder = $_POST['folder'];
        if (!file_exists(IMAGE_PATH.$folder)) {
            mkdir(IMAGE_PATH.$folder);
        }
        $filename = $folder.'/'.$filename;
    }
    $filepath = IMAGE_PATH . $filename;
    move_uploaded_file($_FILES['file']['tmp_name'], $filepath);
    system("rsync_wrap ".escapeshellarg($filepath));
    $id = base64_urlsafe_encode($filename);
    echo json_encode([
        'default' => '/image.php?id='.$id
    ]);
} else {
    http_response_code(400);
}