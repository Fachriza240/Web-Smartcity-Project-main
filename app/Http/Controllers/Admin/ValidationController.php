<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ValidationController extends Controller
{
    public function index()
    {
        $this->authorizeAdmin();

        $pendaftar = User::where('role', 'dosen')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.validasi-registrasi.index', compact('pendaftar'));
    }

    public function approve($id)
    {
        $this->authorizeAdmin();

        $user = User::where('role', 'dosen')->findOrFail($id);
        $user->registration_status = User::STATUS_APPROVED;
        $user->save();

        return redirect()->route('admin.validasi.index')->with('success', 'Pendaftar berhasil di-approve.');
    }

    public function reject($id)
    {
        $this->authorizeAdmin();

        $user = User::where('role', 'dosen')->findOrFail($id);
        $user->registration_status = User::STATUS_REJECTED;
        $user->save();

        return redirect()->route('admin.validasi.index')->with('success', 'Pendaftar berhasil di-reject.');
    }

    private function authorizeAdmin(): void
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403);
        }
    }
}
