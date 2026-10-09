<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class ResolveRolePaths
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $creator = $user?->role === 'content_creator';
        $panel = $creator ? 'creator' : 'admin';
        $peran = $creator ? 'creator' : 'dosen';

        URL::defaults(['panel' => $panel, 'peran' => $peran]);

        $route = $request->route();

        if ($route && $route->hasParameter('peran')) {
            if ($user && $route->parameter('peran') !== $peran) {
                return redirect()->route('dosen.status');
            }

            $route->forgetParameter('peran');
        }

        if ($route && $route->hasParameter('panel')) {
            if ($user && $route->parameter('panel') !== $panel) {
                abort(403);
            }

            $route->forgetParameter('panel');
        }

        return $next($request);
    }
}