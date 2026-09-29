<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

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

        $exceptions->render(function (Throwable $exception, Request $request) {
            if (! env('VERCEL') || ! $request->isMethod('post') || ! $request->is('login')) {
                return null;
            }

            $summaries = [];
            $current = $exception;

            for ($depth = 0; $current !== null && $depth < 5; $depth++) {
                $message = preg_replace(
                    [
                        '#(?:postgres(?:ql)?://)[^@\\s]+@#i',
                        '#(password\\s*[=:]\\s*)[^\\s;]+#i',
                    ],
                    ['$0', '$1[redacted]'],
                    $current->getMessage(),
                );

                $message = preg_replace('#(?:postgres(?:ql)?://)[^@\\s]+@#i', 'postgresql://[redacted]@', $message);

                $summaries[] = sprintf(
                    '%s (code=%s) at %s:%d: %s',
                    $current::class,
                    (string) $current->getCode(),
                    basename($current->getFile()),
                    $current->getLine(),
                    $message,
                );
                $current = $current->getPrevious();
            }

            return response(
                "Login diagnostic\n\n".implode("\nCaused by: ", $summaries),
                500,
                ['Content-Type' => 'text/plain; charset=UTF-8'],
            );
        });

        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
