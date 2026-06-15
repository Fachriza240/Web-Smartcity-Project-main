<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    public const STATUS_DRAFT   = 'Draft';
    public const STATUS_PUBLISH = 'Publish';
    public const TIPE_STAFF     = 'Staff';
    public const TIPE_INTERN    = 'Intern';

    protected $fillable = [
        'nama',
        'jabatan',
        'bidang',
        'foto_path',
        'email',
        'telepon',
        'linkedin',
        'instagram',
        'github',
        'tipe',
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

    public static function tipes(): array
    {
        return [self::TIPE_STAFF, self::TIPE_INTERN];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PUBLISH);
    }
}
