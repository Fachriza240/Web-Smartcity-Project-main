<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class EnsureUserApproved
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->role === 'admin') {
            return $next($request);
        }

        if (in_array($user->role, ['dosen', 'content_creator'], true)) {
            if ($user->registration_status !== User::STATUS_APPROVED) {
                return redirect()->route('dosen.status');
            }

            return $next($request);
        }

        abort(403);
    }
}
