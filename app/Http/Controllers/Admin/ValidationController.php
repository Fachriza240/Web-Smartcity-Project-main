<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\AuthorizesRoles;
use App\Http\Controllers\Controller;
use App\Models\User;
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
        $user->registration_status = User::STATUS_APPROVED;
        $user->save();

        return redirect()->route('admin.validasi.index')
            ->with('success', "Akun {$user->fullname} ({$user->role}) berhasil di-approve.");
    }

    public function reject($id)
    {
        $this->authorizeAdmin();

        $user = User::whereIn('role', ['dosen', 'content_creator'])->findOrFail($id);
        $user->registration_status = User::STATUS_REJECTED;
        $user->save();

        return redirect()->route('admin.validasi.index')
            ->with('success', "Akun {$user->fullname} ({$user->role}) berhasil di-reject.");
    }
}