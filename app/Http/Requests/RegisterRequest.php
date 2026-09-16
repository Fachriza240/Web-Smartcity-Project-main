<?php

namespace App\Http\Requests;

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
            'fullname' => 'required|string|max:255',
            'email'    => 'required|email:rfc,dns|max:255',
            'password' => 'required|string|min:6|confirmed',
            'role'     => 'required|in:dosen,content_creator',
            'foto'     => 'nullable|image|max:2048',
        ];

        if ($role === 'dosen') {
            $rules['nip']      = 'required|numeric|digits_between:5,20';
            $rules['prodi']    = 'nullable|string|max:255';
            $rules['fakultas'] = 'nullable|string|max:255';
        } else {
            $rules['nip'] = 'nullable|numeric|digits_between:5,20';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'fullname.required' => 'Nama lengkap wajib diisi.',
            'fullname.max'      => 'Nama lengkap maksimal 255 karakter.',
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 6 karakter.',
            'password.confirmed'=> 'Konfirmasi password tidak cocok.',
            'role.required'     => 'Silakan pilih role akun.',
            'role.in'           => 'Role yang dipilih tidak valid.',
            'foto.image'        => 'File foto harus berupa gambar.',
            'foto.max'          => 'Ukuran foto maksimal 2MB.',
            'nip.required'      => 'NIP wajib diisi untuk akun dosen.',
            'nip.numeric'       => 'NIP hanya boleh berisi angka.',
            'nip.digits_between'=> 'NIP harus terdiri dari 5–20 digit.',
            'prodi.max'         => 'Program studi maksimal 255 karakter.',
            'fakultas.max'      => 'Fakultas maksimal 255 karakter.',
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