<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

class MaxTotalUploadSize implements ValidationRule
{
    /**
     * @param int $maxKilobytes Batas total ukuran dalam KB (sama satuan dengan rule "max" bawaan Laravel).
     */
    public function __construct(private int $maxKilobytes)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_array($value)) {
            return;
        }

        $totalBytes = 0;

        foreach ($value as $file) {
            if ($file instanceof UploadedFile) {
                $totalBytes += $file->getSize();
            }
        }

        $totalKilobytes = $totalBytes / 1024;

        if ($totalKilobytes > $this->maxKilobytes) {
            $maxMb = round($this->maxKilobytes / 1024, 1);
            $fail("Total ukuran seluruh file pada :attribute tidak boleh lebih dari {$maxMb} MB.");
        }
    }
}