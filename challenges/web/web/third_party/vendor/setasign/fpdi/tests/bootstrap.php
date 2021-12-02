<?php

if (version_compare(PHP_VERSION, '8.0', '>=')) {
    // required for tcpdf
    error_reporting(E_ALL & ~E_DEPRECATED);
} else {
    error_reporting(E_ALL);
}
ini_set('display_errors', 1);

// if in travis scope
if (defined('AUTOLOAD_PATH')) {
    if (is_file(__DIR__ . '/../' . AUTOLOAD_PATH)) {
        /** @noinspection PhpIncludeInspection */
        include_once __DIR__ . '/../' . AUTOLOAD_PATH;
    } else {
        throw new InvalidArgumentException(sprintf(
            'Can\'t load custom autoload file located at %s',
            AUTOLOAD_PATH
        ));
    }
}
