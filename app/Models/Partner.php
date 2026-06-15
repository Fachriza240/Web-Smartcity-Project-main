<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    public const STATUS_DRAFT   = 'Draft';
    public const STATUS_PUBLISH = 'Publish';

    protected $fillable = [
        'nama',
        'deskripsi',
        'logo_path',
        'website',
        'status',
        'urutan',
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
