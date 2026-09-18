<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SafeText implements ValidationRule
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

        if (strip_tags($trimmed) !== $trimmed) {
            $fail('Kolom :attribute tidak boleh mengandung tag HTML atau script.');
            return;
        }

        if (preg_match('/[\x{1F000}-\x{1FFFF}\x{2600}-\x{27BF}\x{2190}-\x{21FF}\x{2B00}-\x{2BFF}]/u', $trimmed)) {
            $fail('Kolom :attribute tidak boleh mengandung emoji.');
        }
    }
}