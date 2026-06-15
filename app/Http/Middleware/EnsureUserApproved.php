<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class EnsureUserApproved
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user) {
            return redirect('/login');
        }

        if (($user->role ?? null) !== 'dosen') {
            abort(403);
        }

        if (($user->registration_status ?? null) !== User::STATUS_APPROVED) {
            return redirect('/dosen/status');
        }

        return $next($request);
    }
}
