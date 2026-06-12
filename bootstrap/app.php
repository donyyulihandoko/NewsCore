<?php

use App\Http\Middleware\IsAdminMiddleware;
use App\Http\Middleware\IsAuthorMiddleware;
use App\Http\Middleware\IsUserMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\Attributes\RedirectToRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'is_admin' => IsAdminMiddleware::class,
            'is_user' => IsUserMiddleware::class,
            'is_author' => IsAuthorMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        
            $exceptions->render(function(ModelNotFoundException $model, Request $request){
                $resource = $request->segment(1);

            if ($resource && Route::has("$resource.index"))  {
                return response()->redirectToRoute("$resource.index");
            }
            return response()->redirectToRoute('home.index');
        });

        // catch log error
        $exceptions->report(function (QueryException $e) {
            Log::error('Database query error: ' . $e->getMessage(), [
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'code' => $e->getCode(),
            ]);
        });

        $exceptions->render(function (QueryException $e) {
                return back()->with('error', 'Something went wrong on our end. Contact support if the issue persists.');
            });
    })->create();
