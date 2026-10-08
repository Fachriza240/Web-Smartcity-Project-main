<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\CompletesLogin;
use App\Models\User;
use App\Rules\PersonName;
use App\Rules\SafeText;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    use CompletesLogin;

    public function showRegister()
    {
        return view('authorized.registrasi');
    }

    public function register(Request $request)
    {
        $this->normalizeEmail($request);

        $role = $request->input('role') === 'content_creator' ? 'content_creator' : 'dosen';

        $data = $request->validate(array_merge($this->identityRules($role), [
            'email' => ['required', 'string', 'email:rfc', 'min:6', 'max:254', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'max:64', 'confirmed'],
            'role' => ['required', 'in:dosen,content_creator'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]));

        $data['fullname'] = $this->cleanName($data['fullname']);
        $data['role'] = $role;
        $data['registration_status'] = User::STATUS_PENDING;

        if ($role !== 'dosen') {
            unset($data['prodi'], $data['fakultas']);
        }

        unset($data['foto']);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto-users', 'public');
        }

        User::create($data);

        $message = $role === 'dosen'
            ? 'Registrasi berhasil. Akun dosen Anda menunggu validasi admin.'
            : 'Registrasi berhasil. Akun Anda menunggu persetujuan admin sebelum bisa digunakan.';

        return redirect()->route('login')->with('success', $message);
    }

    public function showLogin()
    {
        return view('authorized.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email:rfc', 'min:6', 'max:254'],
            'password' => ['required', 'string', 'min:8', 'max:64'],
        ]);

        $attempt = [
            User::emailCredential($credentials['email']),
            'password' => $credentials['password'],
        ];

        if (! Auth::validate($attempt)) {
            return back()
                ->withErrors(['email' => 'Email atau password salah.'])
                ->withInput($request->only('email', 'remember'));
        }

        $user = Auth::getLastAttempted();
        $remember = $request->boolean('remember');

        if (! $user->isActive()) {
            return back()
                ->withErrors(['email' => User::INACTIVE_MESSAGE])
                ->withInput($request->only('email', 'remember'));
        }

        if ($user->isLocked()) {
            return back()
                ->withErrors(['email' => $user->lockMessage()])
                ->withInput($request->only('email', 'remember'));
        }

        if ($user->isDormant()) {
            $request->session()->put(DormantAccountController::SESSION_KEY, [
                'user_id' => $user->id,
                'remember' => $remember,
                'expires_at' => now()->addMinutes(10)->timestamp,
            ]);

            return redirect()->route('login.keaktifan.tampil');
        }

        return $this->proceedLogin($request, $user, $remember);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda berhasil keluar dari akun.');
    }

    public function showPerbaikan(Request $request)
    {
        $user = $request->user();

        if ($user->registration_status !== User::STATUS_REJECTED) {
            return redirect()->route('dosen.status');
        }

        return view('authorized.registrasi-perbaikan', compact('user'));
    }

    public function updatePerbaikan(Request $request)
    {
        $user = $request->user();

        if ($user->registration_status !== User::STATUS_REJECTED) {
            return redirect()->route('dosen.status');
        }

        $this->normalizeEmail($request);

        $data = $request->validate(array_merge($this->identityRules($user->role, $user), [
            'email' => ['required', 'string', 'email:rfc', 'min:6', 'max:254', Rule::unique('users', 'email')->ignore($user->id)],
        ]));

        $data['fullname'] = $this->cleanName($data['fullname']);
        $data['registration_status'] = User::STATUS_PENDING;
        $data['rejection_reason'] = null;

        $user->forceFill($data)->save();

        return redirect()->route('dosen.status')
            ->with('success', 'Data registrasi berhasil diperbaiki dan diajukan ulang. Silakan tunggu validasi Admin.');
    }

    private function identityRules(string $role, ?User $user = null): array
    {
        $nipUnique = Rule::unique('users', 'nip')->ignore($user?->id);

        $rules = [
            'fullname' => ['required', 'string', 'min:3', 'max:100', new PersonName],
        ];

        if ($role === 'dosen') {
            $rules['nip'] = ['required', 'regex:/^[0-9]+$/', 'digits_between:4,30', $nipUnique];
            $rules['prodi'] = ['nullable', 'string', 'min:2', 'max:100', new SafeText];
            $rules['fakultas'] = ['nullable', 'string', 'min:2', 'max:100', new SafeText];
        } else {
            $rules['nip'] = ['nullable', 'regex:/^[0-9]+$/', 'digits_between:4,30', $nipUnique];
        }

        return $rules;
    }

    private function normalizeEmail(Request $request): void
    {
        $email = $request->input('email');

        if (is_string($email)) {
            $request->merge(['email' => mb_strtolower(trim($email))]);
        }
    }

    private function cleanName(string $name): string
    {
        return preg_replace('/\s+/u', ' ', trim($name));
    }
}
