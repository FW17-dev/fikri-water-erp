<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();

// Di Vercel (serverless) filesystem read-only, kecuali /tmp.
// Arahkan storage Laravel (log, cache, view, session file) ke /tmp.
if (getenv('VERCEL') || getenv('VERCEL_ENV')) {
    $tmpStorage = '/tmp/storage';
    foreach (['app', 'app/public', 'framework', 'framework/cache', 'framework/cache/data', 'framework/sessions', 'framework/testing', 'framework/views', 'logs'] as $dir) {
        $path = $tmpStorage.'/'.$dir;
        if (!is_dir($path)) {
            @mkdir($path, 0777, true);
        }
    }
    $app->useStoragePath($tmpStorage);
}

return $app;
