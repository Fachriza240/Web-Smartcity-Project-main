<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Hki extends Model
{
    public const STATUS_DRAFT   = 'Draft';
    public const STATUS_PUBLISH = 'Publish';

    public const JENIS = [
        'Hak Cipta',
        'Paten',
        'Paten Sederhana',
        'Merek',
        'Desain Industri',
        'Desain Tata Letak Sirkuit Terpadu',
        'Rahasia Dagang',
        'Varietas Tanaman',
    ];

    protected $fillable = [
        'nomor_sertifikat',
        'tgl_terbit',
        'judul_sertifikat',
        'jenis_sertifikat',
        'pencipta',
        'user_id',
        'recommended_by',
        'submission_type',
        'file_sertifikat',
        'status',
    ];

    protected $casts = [
        'tgl_terbit' => 'date',
    ];

    public function recommender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getRecommenderNameAttribute(): string
    {
        if ($this->submission_type === 'member' && $this->recommender) {
            return $this->recommender->fullname;
        }
        return $this->recommended_by ?? '-';
    }

    public static function statuses(): array
    {
        return [self::STATUS_DRAFT, self::STATUS_PUBLISH];
    }

    public static function submissionTypes(): array
    {
        return ['member', 'non_member'];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PUBLISH);
    }
}
