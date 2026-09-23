<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Rules\SafeName;
use App\Rules\SafeText;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('halaman-user.kontak-user');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama'   => ['required', 'string', 'min:3', 'max:255', new SafeName()],
            'email'  => ['required', 'string', 'email', 'max:255'],
            'subjek' => ['required', 'string', 'min:5', 'max:255', new SafeText()],
            'pesan'  => ['required', 'string', 'min:10', 'max:2000', new SafeText()],
        ], [
            'nama.required'   => 'Nama wajib diisi.',
            'nama.min'        => 'Nama minimal 3 karakter.',
            'email.required'  => 'Email wajib diisi.',
            'email.email'     => 'Format email tidak valid.',
            'subjek.required' => 'Subjek wajib diisi.',
            'subjek.min'      => 'Subjek minimal 5 karakter.',
            'pesan.required'  => 'Pesan wajib diisi.',
            'pesan.min'       => 'Pesan minimal 10 karakter.',
        ]);

        ContactMessage::create($validated);

        return redirect()
            ->route('contact.index')
            ->with('success', 'Pesan Anda berhasil terkirim. Tim kami akan segera merespons.');
    }
}