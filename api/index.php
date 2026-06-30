<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    $_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/../public/index.php';
    $_SERVER['SCRIPT_NAME'] = '/index.php';

    require_once __DIR__ . '/../public/index.php';
} catch (Throwable $e) {
    http_response_code(500);

    echo "<h2>VERCEL RUNTIME ERROR</h2>";
    echo "<pre>";
    echo $e->getMessage();
    echo "\n\n";
    echo $e->getFile();
    echo " : ";
    echo $e->getLine();
    echo "\n\n";
    echo $e->getTraceAsString();
    echo "</pre>";
}
