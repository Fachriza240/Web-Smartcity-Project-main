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
            return;
        }

        if (preg_match('/(--|\/\*|\*\/)/', $trimmed)) {
            $fail('Kolom :attribute mengandung pola karakter yang tidak diizinkan (komentar SQL).');
            return;
        }

        $sqlKeywordPattern = '/\b(union\s+(all\s+)?select|select\b.+\bfrom\b|drop\s+(table|database)|'
            . 'truncate\s+table|alter\s+table|delete\s+from|insert\s+into\b.+\bvalues\b|'
            . 'update\b.+\bset\b|exec(\s|\()|xp_cmdshell|information_schema)\b/i';
        if (preg_match($sqlKeywordPattern, $trimmed)) {
            $fail('Kolom :attribute mengandung pola perintah SQL yang tidak diizinkan.');
            return;
        }

        if (preg_match('/([\'"])\s*(or|and)\s*\1?\s*[\w\'"]+\s*=\s*[\w\'"]+/i', $trimmed)
            || preg_match('/\bor\s+1\s*=\s*1\b/i', $trimmed)) {
            $fail('Kolom :attribute mengandung pola tautology SQL Injection yang tidak diizinkan.');
            return;
        }

        if (substr_count($trimmed, "'") >= 3 || substr_count($trimmed, '"') >= 3) {
            $fail('Kolom :attribute mengandung tanda kutip berulang yang tidak wajar.');
        }
    }
}