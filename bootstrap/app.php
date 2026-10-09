<?php

use App\Http\Middleware\EnsureAccountActive;
use App\Http\Middleware\EnsureRole;
use App\Http\Middleware\EnsureUserApproved;
use App\Http\Middleware\ResolveRolePaths;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => EnsureRole::class,
            'approved' => EnsureUserApproved::class,
        ]);
        $middleware->web(append: [
            EnsureAccountActive::class,
            ResolveRolePaths::class,
        ]);
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->hasSession()) {
                $request->session()->flash('warning', 'Sesi Anda telah berakhir, silakan login kembali.');
            }

            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (HttpException $e, Request $request) {
            if ($e->getStatusCode() !== 419 || $request->expectsJson()) {
                return null;
            }

            return redirect()->route('login')
                ->with('warning', 'Sesi Anda telah berakhir, silakan login kembali.');
        });

        $exceptions->render(function (QueryException|PDOException $e, Request $request) {
            if ($request->isMethodSafe() || $request->expectsJson() || ! $request->hasSession()) {
                return null;
            }

            $route = (string) $request->route()?->getName();

            $message = match (true) {
                $route === 'logout' => 'Logout gagal, silakan coba kembali.',
                str_starts_with($route, 'profil.') => 'Perubahan profil gagal disimpan. Silakan coba lagi.',
                str_starts_with($route, 'dosen.publikasi.'), str_starts_with($route, 'admin.publications.') => 'Publikasi gagal disimpan. Silakan coba lagi.',
                str_starts_with($route, 'dosen.hki.'), str_starts_with($route, 'admin.hki.') => 'Data HKI gagal disimpan. Silakan coba lagi.',
                default => 'Data gagal disimpan karena terjadi gangguan sistem. Silakan coba lagi.',
            };

            return back()
                ->withInput($request->except(['password', 'password_confirmation', '_token', '_method']))
                ->with('error', $message)
                ->with('retry', true);
        });
    })->create();