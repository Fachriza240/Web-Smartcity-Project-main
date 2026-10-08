<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->guest(route('login'))
                ->with('warning', 'Sesi Anda telah berakhir, silakan login kembali.');
        }

        abort_unless(in_array($user->role, $roles, true), 403);

        return $next($request);
    }
}
