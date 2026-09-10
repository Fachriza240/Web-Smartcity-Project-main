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

        if (!$user) {
            return redirect('/login');
        }

        if ($user->role !== 'dosen') {
            abort(403);
        }

        if (($user->registration_status ?? null) !== User::STATUS_APPROVED) {
            return redirect()->route('dosen.status');
        }

        return $next($request);
    }
}