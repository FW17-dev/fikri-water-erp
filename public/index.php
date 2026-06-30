<?php

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

try {

    /** @var \Illuminate\Foundation\Application $app */
    $app = require_once __DIR__.'/../bootstrap/app.php';

    $app->handleRequest(Illuminate\Http\Request::capture());

} catch (\Throwable $e) {

    http_response_code(500);

    header('Content-Type: text/plain');

    echo "===== REAL ERROR =====\n\n";

    echo $e::class."\n\n";

    echo $e->getMessage()."\n\n";

    echo $e->getFile().":".$e->getLine()."\n\n";

    echo $e->getTraceAsString();

    exit;
}
