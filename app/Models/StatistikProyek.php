<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatistikProyek extends Model
{
    public const TIPE_INTERNASIONAL = 'internasional';
    public const TIPE_NASIONAL = 'nasional';
    public const TIPE_PRODUK = 'produk';

    protected $table = 'statistik_proyek';

    protected $fillable = [
        'tipe',
        'nama',
        'urutan',
    ];

    protected $casts = [
        'urutan' => 'integer',
    ];

    public static function tipes(): array
    {
        return [
            self::TIPE_INTERNASIONAL => 'Proyek Internasional',
            self::TIPE_NASIONAL => 'Proyek Nasional',
            self::TIPE_PRODUK => 'Produk Unggulan',
        ];
    }

    public static function tipeKeys(): array
    {
        return array_keys(self::tipes());
    }

    public function labelTipe(): string
    {
        return self::tipes()[$this->tipe] ?? $this->tipe;
    }
}