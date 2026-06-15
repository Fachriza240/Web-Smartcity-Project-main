<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    public const STATUS_DRAFT   = 'Draft';
    public const STATUS_PUBLISH = 'Publish';

    protected $fillable = [
        'judul',
        'deskripsi',
        'thumbnail_path',
        'urutan',
        'status',
    ];

    protected $casts = [
        'urutan' => 'integer',
    ];

    public static function statuses(): array
    {
        return [self::STATUS_DRAFT, self::STATUS_PUBLISH];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PUBLISH);
    }
}
