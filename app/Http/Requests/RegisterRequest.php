<?php

namespace App\Http\Requests;

use App\Rules\SafeName;
use App\Rules\SafeText;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $role = $this->input('role', 'dosen');

        $rules = [
            'fullname' => ['required', 'string', 'min:3', 'max:255', new SafeName()],
            'email'    => 'required|email:rfc|max:255',
            'password' => 'required|string|min:6|max:64|confirmed',
            'role'     => 'required|in:dosen,content_creator',
            'foto'     => 'nullable|image|max:2048',
        ];

        if ($role === 'dosen') {
            $rules['nip']      = 'required|numeric|digits_between:5,20';
            $rules['prodi']    = ['nullable', 'string', 'max:255', new SafeText()];
            $rules['fakultas'] = ['nullable', 'string', 'max:255', new SafeText()];
        } else {
            $rules['nip'] = 'nullable|numeric|digits_between:5,20';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'fullname.required'  => 'Nama lengkap wajib diisi.',
            'fullname.min'       => 'Nama lengkap minimal 3 karakter.',
            'fullname.max'       => 'Nama lengkap maksimal 255 karakter.',
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'email.max'          => 'Email maksimal 255 karakter.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 6 karakter.',
            'password.max'       => 'Password maksimal 64 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required'      => 'Silakan pilih role akun.',
            'role.in'            => 'Role yang dipilih tidak valid.',
            'foto.image'         => 'File foto harus berupa gambar.',
            'foto.max'           => 'Ukuran foto maksimal 2MB.',
            'nip.required'       => 'NIP wajib diisi untuk akun dosen.',
            'nip.numeric'        => 'NIP hanya boleh berisi angka.',
            'nip.digits_between' => 'NIP harus terdiri dari 5–20 digit.',
            'prodi.max'          => 'Program studi maksimal 255 karakter.',
            'fakultas.max'       => 'Fakultas maksimal 255 karakter.',
        ];
    }

    public function attributes(): array
    {
        return [
            'fullname' => 'nama lengkap',
            'email'    => 'email',
            'password' => 'password',
            'nip'      => 'NIP',
            'prodi'    => 'program studi',
            'fakultas' => 'fakultas',
        ];
    }
}