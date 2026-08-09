<?php

use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\UserRedirectIfAuthenticated;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Router;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        function (Router $router) {
            // Health-check pentru Coolify / Docker. Nu are middleware 'web' ca sa
            // nu porneasca sesiune si sa nu atinga baza de date la fiecare probe.
            // (Argumentul health: al lui withRouting e ignorat cand se paseaza un
            // closure de rutare, asa ca ruta se declara aici.)
            $router->get('/up', fn() => response('OK', 200));

            $router
                ->middleware(['web'])
                ->group(base_path('routes/web.php'));

            $router
                ->middleware(['web', 'role:admin'])
                ->name('admin.')
                ->prefix('admin')
                ->group(base_path('routes/admin.php'));

            $router
                ->middleware(['web', 'role:user'])
                ->name('user.')
                ->prefix('user')
                ->group(base_path('routes/user.php'));


            $router
                ->middleware(['web', 'role:company-owner'])
                ->name('company-owner.')
                ->prefix('company-owner')
                ->group(base_path('routes/companyOwner.php'));
        }
        // web: __DIR__.'/../routes/web.php',
        // commands: __DIR__.'/../routes/console.php',
        // health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // In spatele proxy-ului Coolify (Traefik) terminarea TLS se face la proxy.
        // Fara asta Laravel genereaza URL-uri http:// pe un site https:// si se
        // strica asset-urile, redirect-urile si linkurile semnate.
        $middleware->trustProxies(at: '*');

        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        $middleware->alias([
            'role' => RoleMiddleware::class,
            'user.redirect.authenticated' => UserRedirectIfAuthenticated::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
