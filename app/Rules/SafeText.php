<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SafeText implements ValidationRule
{
    private const EMOJI = '/[\x{1F000}-\x{1FAFF}\x{2600}-\x{27BF}\x{2B00}-\x{2BFF}\x{FE00}-\x{FE0F}\x{200D}\x{20E3}\x{E0020}-\x{E007F}\x{3030}\x{303D}\x{3297}\x{3299}]/u';

    private const HTML = '/<\s*\/?\s*[a-z!?][^>]*>|javascript\s*:|\bon[a-z]+\s*=|&#x?[0-9a-f]+;?/i';

    private const SQL_STRICT = [
        '/[\'"`]\s*(or|and)\b[\s\S]*?=/i',
        '/[\'"`]\s*(--|#|;)/',
        '/--\s*$/',
    ];

    private const SQL = [
        '/\bunion\b[\s\S]*\bselect\b/i',
        '/\bselect\s+(\*|count\s*\(|@@)/i',
        '/[;\'"`)]\s*select\b/i',
        '/\binsert\s+into\b/i',
        '/\bdelete\s+from\b/i',
        '/\bupdate\s+\w+\s+set\b/i',
        '/\b(drop|truncate|alter)\s+(table|database|schema)\b/i',
        '/\b(exec|execute)\s*(\(|\s+xp_)/i',
        '/\b(sleep|benchmark|pg_sleep)\s*\(/i',
        '/\b(or|and)\s+\d+\s*=\s*\d+/i',
        '/;\s*--/',
        '/\/\*[\s\S]*?\*\//',
    ];

    public function __construct(private bool $strict = true)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null || $value === '') {
            return;
        }

        if (! is_string($value)) {
            $fail(':attribute harus berupa teks.');

            return;
        }

        if (preg_match(self::EMOJI, $value)) {
            $fail(':attribute tidak boleh berisi emoji atau simbol khusus.');

            return;
        }

        if (preg_match(self::HTML, $value)) {
            $fail(':attribute tidak boleh berisi tag HTML atau skrip.');

            return;
        }

        $patterns = $this->strict ? array_merge(self::SQL, self::SQL_STRICT) : self::SQL;

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $value)) {
                $fail(':attribute berisi pola karakter yang tidak diizinkan.');

                return;
            }
        }
    }
}
