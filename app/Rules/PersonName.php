<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PersonName implements ValidationRule
{
    public const PATTERN = "/^[\p{L}][\p{L}\p{M}\s.,'\-]*$/u";

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            $fail(':attribute harus berupa teks.');

            return;
        }

        $value = trim($value);

        if (! preg_match(self::PATTERN, $value)) {
            $fail(':attribute hanya boleh berisi huruf, spasi, titik, koma, apostrof, dan tanda hubung.');

            return;
        }

        if (preg_match("/--|''|'\\s|\\s'|'$/u", $value)) {
            $fail(':attribute berisi pola karakter yang tidak diizinkan.');

            return;
        }

        if (preg_match('/\s{2,}/u', $value)) {
            $fail(':attribute tidak boleh berisi spasi berurutan.');
        }
    }
}
