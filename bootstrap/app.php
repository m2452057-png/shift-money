<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

$databaseUrl = env('DATABASE_URL');

if (env('VERCEL') && is_string($databaseUrl)) {
    $databaseHost = parse_url($databaseUrl, PHP_URL_HOST);

    if (is_string($databaseHost) && preg_match('/^(ep-[a-z0-9-]+?)(?:-pooler)?\./', $databaseHost, $matches)) {
        putenv('PGOPTIONS=endpoint='.$matches[1]);
    }
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Vercel truncates long stack traces from the beginning. Emit a short
        // summary containing only exception types, codes, and source locations.
        $exceptions->report(function (Throwable $exception): void {
            if (! env('VERCEL')) {
                return;
            }

            $summaries = [];
            $current = $exception;

            for ($depth = 0; $current !== null && $depth < 5; $depth++) {
                $summaries[] = sprintf(
                    '%s(code=%s) at %s:%d',
                    $current::class,
                    (string) $current->getCode(),
                    basename($current->getFile()),
                    $current->getLine(),
                );
                $current = $current->getPrevious();
            }

            error_log('LARAVEL_ERROR '.implode(' <- ', $summaries));
        });

        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
