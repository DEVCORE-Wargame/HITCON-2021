<?php

require_once('include.php');

$id = $_GET['id'];
$file = base64_urlsafe_decode($id);
$file = IMAGE_PATH . $file;

if (!file_exists($file)) {
    http_response_code(404);
} else {
    header('Content-Type: ' . mime_content_type($file));
    readfile($file);
}
