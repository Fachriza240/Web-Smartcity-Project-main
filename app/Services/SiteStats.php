<?php

namespace App\Services;

use App\Models\Partner;
use App\Models\Program;
use App\Models\Publication;
use App\Models\StatistikProyek;
use App\Models\User;
use Illuminate\Support\Collection;

class SiteStats
{

    public static function anggotaAktif(): int
    {
        return User::query()
            ->where('role', 'dosen')
            ->where('registration_status', User::STATUS_APPROVED)
            ->where(function ($query) {
                $query->where('is_active', true)->orWhereNull('is_active');
            })
            ->count();
    }

    public static function publikasi(): int
    {
        return Publication::where('status', Publication::STATUS_PUBLISH)->count();
    }

    public static function proyek(): int
    {
        return StatistikProyek::whereIn('tipe', [
            StatistikProyek::TIPE_INTERNASIONAL,
            StatistikProyek::TIPE_NASIONAL,
        ])->count();
    }

    public static function program(): int
    {
        return Program::published()->count();
    }

    public static function mitra(): int
    {
        return Partner::published()->count();
    }

    public static function ringkasan(): array
    {
        return [
            'anggota' => static::anggotaAktif(),
            'proyek' => static::proyek(),
            'publikasi' => static::publikasi(),
            'program' => static::program(),
            'mitra' => static::mitra(),
        ];
    }

    public static function daftarProyek(): Collection
    {
        return StatistikProyek::query()
            ->orderBy('urutan')
            ->orderBy('id')
            ->get()
            ->groupBy('tipe');
    }

    public static function tahunBerdiri(): int
    {
        return (int) config('smartcity.founded_year', 2025);
    }

    public static function tahunPengalaman(): int
    {
        return max(1, now()->year - static::tahunBerdiri());
    }
}