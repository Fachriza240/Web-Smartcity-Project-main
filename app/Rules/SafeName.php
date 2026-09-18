<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SafeName implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            $fail('Kolom :attribute harus berupa teks.');
            return;
        }

        $trimmed = trim($value);

        if ($trimmed === '') {
            $fail('Kolom :attribute tidak boleh kosong atau hanya berisi spasi.');
            return;
        }

        if (! preg_match('/^[\pL\s\.\'\-,]+$/u', $trimmed)) {
            $fail('Kolom :attribute hanya boleh berisi huruf, spasi, titik, apostrof, tanda hubung, dan koma (tidak boleh angka, simbol, emoji, atau tag HTML).');
            return;
        }
        
        if (strip_tags($trimmed) !== $trimmed) {
            $fail('Kolom :attribute tidak boleh mengandung tag HTML atau script.');
        }
    }
}