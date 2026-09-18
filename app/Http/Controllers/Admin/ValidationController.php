<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\AuthorizesRoles;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\SafeText;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidationController extends Controller
{
    use AuthorizesRoles;

    public function index()
    {
        $this->authorizeAdmin();

        $pendaftar = User::whereIn('role', ['dosen', 'content_creator'])
            ->when(request('role'),   fn ($q) => $q->where('role', request('role')))
            ->when(request('status'), fn ($q) => $q->where('registration_status', request('status')))
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('admin.validasi-registrasi.index', compact('pendaftar'));
    }

    public function approve($id)
    {
        $this->authorizeAdmin();

        $user = User::whereIn('role', ['dosen', 'content_creator'])->findOrFail($id);

        if ($user->registration_status === User::STATUS_APPROVED) {
            return redirect()->route('admin.validasi.index')
                ->with('success', "Akun {$user->fullname} memang sudah berstatus approved.");
        }

        $user->registration_status = User::STATUS_APPROVED;
        $user->rejection_reason    = null;
        $user->reviewed_by         = Auth::id();
        $user->reviewed_at         = now();
        $user->save();

        return redirect()->route('admin.validasi.index')
            ->with('success', "Akun {$user->fullname} ({$user->role}) berhasil di-approve.");
    }

    public function reject(Request $request, $id)
    {
        $this->authorizeAdmin();

        $request->validate([
            'rejection_reason' => ['required', 'string', 'min:10', 'max:1000', new SafeText()],
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi.',
            'rejection_reason.min'      => 'Alasan penolakan minimal 10 karakter, tolong jelaskan lebih detail.',
        ]);

        $user = User::whereIn('role', ['dosen', 'content_creator'])->findOrFail($id);

        $user->registration_status = User::STATUS_REJECTED;
        $user->rejection_reason    = $request->input('rejection_reason');
        $user->reviewed_by         = Auth::id();
        $user->reviewed_at         = now();
        $user->save();

        return redirect()->route('admin.validasi.index')
            ->with('success', "Akun {$user->fullname} ({$user->role}) berhasil di-reject.");
    }
}