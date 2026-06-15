<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public const STATUS_DRAFT = 'Draft';
    public const STATUS_PUBLISH = 'Publish';

    protected $fillable = [
        'judul',
        'thumbnail_path',
        'deskripsi',
        'kategori',
        'partner',
        'tahun',
        'gallery_paths',
        'dokumen_path',
        'status',
    ];

    protected $casts = [
        'gallery_paths' => 'array',
    ];

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
