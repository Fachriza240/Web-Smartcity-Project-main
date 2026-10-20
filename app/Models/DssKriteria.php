<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class DssKriteria extends Model
{
    protected $table = 'dss_kriteria';

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'jenis',
        'bobot',
        'urutan',
    ];

    protected $casts = [
        'bobot' => 'float',
        'urutan' => 'integer',
    ];

    public function scopeUrut(Builder $query): Builder
    {
        return $query->orderBy('urutan')->orderBy('id');
    }

    public static function daftar()
    {
        return static::query()->urut()->get();
    }

    public static function bobotSaatIni(): array
    {
        return static::daftar()->pluck('bobot', 'kode')->map(fn ($b) => (float) $b)->all();
    }

    public static function totalBobot(): float
    {
        return round((float) static::sum('bobot'), 6);
    }
}