<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    public const CATEGORY_JOURNAL = 'Jurnal';
    public const CATEGORY_CONFERENCE = 'Conference';
    public const CATEGORY_BOOK = 'Buku';
    public const CATEGORY_PATENT = 'Paten';
    public const CATEGORY_REPORT = 'Laporan Penelitian';

    public const STATUS_DRAFT = 'Draft';
    public const STATUS_PUBLISH = 'Publish';

    protected $fillable = [
        'judul',
        'penulis',
        'tahun',
        'abstrak',
        'kategori',
        'penerbit',
        'doi',
        'pdf_path',
        'thumbnail_path',
        'status',
    ];

    public static function categories(): array
    {
        return [
            self::CATEGORY_JOURNAL,
            self::CATEGORY_CONFERENCE,
            self::CATEGORY_BOOK,
            self::CATEGORY_PATENT,
            self::CATEGORY_REPORT,
        ];
    }

    public static function statuses(): array
    {
        return [
            self::STATUS_DRAFT,
            self::STATUS_PUBLISH,
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PUBLISH);
    }
}
