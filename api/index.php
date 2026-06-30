<?php

if (!defined('LARAVEL_START')) {
    define('LARAVEL_START', microtime(true));
}

$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/../public/index.php';

require_once __DIR__ . '/../public/index.php';
