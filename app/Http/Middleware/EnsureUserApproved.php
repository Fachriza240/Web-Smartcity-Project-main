<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // Admin & content_creator selalu approved — langsung lolos
        if (in_array($user->role, ['admin', 'content_creator'], true)) {
            return $next($request);
        }

        // Dosen: cek status registrasi
        if ($user->role === 'dosen') {
            if (($user->registration_status ?? null) !== User::STATUS_APPROVED) {
                // Jika masih pending/rejected, arahkan ke halaman status
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('dosen.status');
            }

            return $next($request);
        }

        // Role lain tidak dikenal → tolak
        abort(403);
    }
}
