<?php

require_once('include.php');


if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    $languages = explode(',', ALLOWED_LANGUAGE);
    if (in_array($lang, $languages, true)) {
        $_SESSION['lang'] = $lang;
    }
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
