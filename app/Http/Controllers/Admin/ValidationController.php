<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginConfirmation;
use App\Models\User;
use App\Notifications\RegistrationStatusNotification;
use App\Rules\SafeText;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ValidationController extends Controller
{
    public function index()
    {
        $this->authorizeAdmin();

        $pendaftar = User::whereIn('role', ['dosen', 'content_creator'])
            ->when(request('role'), fn ($q) => $q->where('role', request('role')))
            ->when(request('status'), function ($q, $status) {
                if ($status === 'inactive') {
                    return $q->where('is_active', false);
                }

                return $q->where('registration_status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('admin.validasi-registrasi.index', compact('pendaftar'));
    }

    public function approve($id)
    {
        $this->authorizeAdmin();

        [$user, $changed] = $this->changeStatus($id, User::STATUS_APPROVED);

        if (! $changed) {
            return redirect()->route('admin.validasi.index')
                ->with('info', "Akun {$user->fullname} sudah disetujui. Notifikasi tidak dikirim ulang.");
        }

        return redirect()->route('admin.validasi.index')
            ->with('success', "Akun {$user->fullname} ({$user->role}) berhasil di-approve. Notifikasi telah dikirim ke pengguna.");
    }

    public function reject(Request $request, $id)
    {
        $this->authorizeAdmin();

        $user = User::whereIn('role', ['dosen', 'content_creator'])->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'alasan' => ['required', 'string', 'min:10', 'max:500', new SafeText],
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->only('alasan'))
                ->with('tolak_id', $user->id);
        }

        $alasan = $validator->validated()['alasan'];

        [$user, $changed] = $this->changeStatus($id, User::STATUS_REJECTED, $alasan);

        if (! $changed) {
            return redirect()->route('admin.validasi.index')
                ->with('info', "Penolakan akun {$user->fullname} sudah diproses. Notifikasi tidak dikirim ulang.");
        }

        return redirect()->route('admin.validasi.index')
            ->with('success', "Akun {$user->fullname} ({$user->role}) berhasil di-reject. Notifikasi beserta alasan penolakan telah dikirim ke pengguna.");
    }

    public function deactivate($id)
    {
        $this->authorizeAdmin();

        $user = User::whereIn('role', ['dosen', 'content_creator'])->findOrFail($id);

        $user->forceFill([
            'is_active' => false,
            'remember_token' => Str::random(60),
        ])->save();

        LoginConfirmation::cancelPendingFor($user);

        if (config('session.driver') === 'database') {
            DB::table(config('session.table', 'sessions'))
                ->where('user_id', $user->id)
                ->delete();
        }

        return back()
            ->with('success', "Akun {$user->fullname} berhasil dinonaktifkan. Pengguna tidak dapat login sampai akun diaktifkan kembali.");
    }

    public function activate($id)
    {
        $this->authorizeAdmin();

        $user = User::whereIn('role', ['dosen', 'content_creator'])->findOrFail($id);

        $user->forceFill(['is_active' => true])->save();

        return back()
            ->with('success', "Akun {$user->fullname} berhasil diaktifkan kembali dan sudah dapat digunakan untuk login.");
    }

    private function changeStatus(int|string $id, string $status, ?string $reason = null): array
    {
        return DB::transaction(function () use ($id, $status, $reason) {
            $user = User::whereIn('role', ['dosen', 'content_creator'])
                ->lockForUpdate()
                ->findOrFail($id);

            if ($user->registration_status === $status && $user->rejection_reason === $reason) {
                return [$user, false];
            }

            $user->forceFill([
                'registration_status' => $status,
                'rejection_reason' => $reason,
            ])->save();

            $user->notify(new RegistrationStatusNotification($status, $reason));

            return [$user, true];
        });
    }

    private function authorizeAdmin(): void
    {
        if (! Auth::check() || Auth::user()->role !== 'admin') {
            abort(403);
        }
    }
}