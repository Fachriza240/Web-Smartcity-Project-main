<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AktivitasDosen extends Model
{
    public const JENIS_PENELITIAN = 'penelitian';
    public const JENIS_ABDIMAS = 'abdimas';
    public const JENIS_PRODUK = 'produk';
    public const JENIS_KOLABORASI = 'kolaborasi';
    public const JENIS_KEPEMIMPINAN = 'kepemimpinan';

    public const STATUS_DRAFT = 'Draft';
    public const STATUS_PUBLISH = 'Publish';

    protected $table = 'aktivitas_dosen';

    protected $fillable = [
        'user_id',
        'jenis',
        'judul',
        'tahun',
        'skala',
        'peran',
        'sub_jenis',
        'bidang_smart_city',
        'mitra',
        'berkelanjutan',
        'keterangan',
        'status',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'berkelanjutan' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PUBLISH);
    }

    public function scopeMilik(Builder $query, User $user): Builder
    {
        return $query->where('user_id', $user->id);
    }

    public function scopeDalamJendela(Builder $query): Builder
    {
        return $query->where('tahun', '>=', self::tahunMulai());
    }

    public static function tahunMulai(): int
    {
        return now()->year - (int) config('dss.jendela_tahun', 5) + 1;
    }

    public function isOwnedBy(User $user): bool
    {
        return (int) $this->user_id === (int) $user->id;
    }

    public static function statuses(): array
    {
        return [self::STATUS_DRAFT, self::STATUS_PUBLISH];
    }

    public static function jenisList(): array
    {
        return [
            self::JENIS_PENELITIAN => 'Penelitian',
            self::JENIS_ABDIMAS => 'Abdimas',
            self::JENIS_PRODUK => 'Inovasi dan Produk',
            self::JENIS_KOLABORASI => 'Kolaborasi',
            self::JENIS_KEPEMIMPINAN => 'Kepemimpinan Program',
        ];
    }

    public static function jenisKeys(): array
    {
        return array_keys(self::jenisList());
    }

    public static function skalaList(): array
    {
        return [
            'institusi' => 'Institusi/Lokal',
            'nasional' => 'Nasional',
            'internasional' => 'Internasional',
        ];
    }

    public static function peranList(): array
    {
        return [
            'ketua' => 'Ketua',
            'anggota' => 'Anggota',
        ];
    }

    public static function subJenisList(string $jenis): array
    {
        return config('dss.label_sub_jenis.' . $jenis, []);
    }

    public static function bidangSmartCityList(): array
    {
        return collect(config('dss.bidang_smart_city', []))
            ->map(fn ($item) => $item['label'])
            ->all();
    }

    public function getLabelJenisAttribute(): string
    {
        return self::jenisList()[$this->jenis] ?? (string) $this->jenis;
    }

    public function getLabelBidangAttribute(): string
    {
        if (! $this->bidang_smart_city) {
            return '-';
        }

        return self::bidangSmartCityList()[$this->bidang_smart_city] ?? (string) $this->bidang_smart_city;
    }

    public function getRingkasanAttribute(): string
    {
        $bagian = [];

        if ($this->skala) {
            $bagian[] = self::skalaList()[$this->skala] ?? $this->skala;
        }

        if ($this->sub_jenis) {
            $bagian[] = self::subJenisList((string) $this->jenis)[$this->sub_jenis] ?? $this->sub_jenis;
        }

        if (in_array($this->jenis, [self::JENIS_PENELITIAN, self::JENIS_ABDIMAS, self::JENIS_PRODUK], true)) {
            $bagian[] = self::peranList()[$this->peran] ?? $this->peran;
        }

        if ($this->jenis === self::JENIS_ABDIMAS && $this->berkelanjutan) {
            $bagian[] = 'Berkelanjutan';
        }

        return implode(', ', $bagian) ?: '-';
    }
}