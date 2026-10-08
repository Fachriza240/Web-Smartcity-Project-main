<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\CompletesLogin;
use App\Models\User;
use Illuminate\Http\Request;

class DormantAccountController extends Controller
{
    use CompletesLogin;

    public const SESSION_KEY = 'login_reactivation';

    public function show(Request $request)
    {
        $user = $this->pendingUser($request);

        if (! $user) {
            return $this->expired($request);
        }

        return view('authorized.konfirmasi-keaktifan', [
            'user' => $user,
            'terakhir' => $user->lastActivityAt(),
            'hari' => (int) config('login.dormant_days', 90),
        ]);
    }

    public function confirm(Request $request)
    {
        $user = $this->pendingUser($request);

        if (! $user) {
            return $this->expired($request);
        }

        $state = $request->session()->pull(self::SESSION_KEY);

        if (! $user->isActive()) {
            return redirect()->route('login')->withErrors(['email' => User::INACTIVE_MESSAGE]);
        }

        if ($user->isLocked()) {
            return redirect()->route('login')->withErrors(['email' => $user->lockMessage()]);
        }

        return $this->proceedLogin($request, $user, (bool) ($state['remember'] ?? false));
    }

    public function cancel(Request $request)
    {
        $request->session()->forget(self::SESSION_KEY);

        return redirect()->route('login')->with('info', 'Login dibatalkan.');
    }

    private function pendingUser(Request $request): ?User
    {
        $state = $request->session()->get(self::SESSION_KEY);

        if (! is_array($state) || (int) ($state['expires_at'] ?? 0) < now()->timestamp) {
            return null;
        }

        return User::find($state['user_id'] ?? null);
    }

    private function expired(Request $request)
    {
        $request->session()->forget(self::SESSION_KEY);

        return redirect()->route('login')->with('warning', 'Waktu konfirmasi keaktifan akun telah habis. Silakan login kembali.');
    }
}
