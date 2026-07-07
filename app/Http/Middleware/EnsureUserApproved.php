<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class EnsureUserApproved
{
    /**
     * Pastikan user sudah login dan approved.
     * - Dosen: harus registration_status = approved
     * - Admin / content_creator: langsung lolos (selalu approved)
     * - Guest: redirect ke login
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Belum login → ke halaman login
        if (!$user) {
            return redirect('/login');
        }

        // Admin selalu lolos
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Content creator: cek status
        if ($user->role === 'content_creator') {
            if (($user->registration_status ?? null) !== User::STATUS_APPROVED) {
                return redirect()->route('dosen.status');
            }
            return $next($request);
        }

        // Dosen: cek status registrasi
        if ($user->role === 'dosen') {
            if (($user->registration_status ?? null) !== User::STATUS_APPROVED) {
                return redirect()->route('dosen.status');
            }
            return $next($request);
        }

        // Role lain tidak dikenal → tolak
        abort(403);
    }
}
