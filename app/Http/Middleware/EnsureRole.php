<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;


class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!in_array($user->role, $roles, true)) {
            abort(403);
        }

        if ($user->role !== 'admin' && !$user->isApproved()) {
            return redirect()->route('dosen.status');
        }

        return $next($request);
    }
}