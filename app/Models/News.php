<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    public const STATUS_DRAFT   = 'Draft';
    public const STATUS_PUBLISH = 'Publish';

    protected $fillable = [
        'judul',
        'slug',
        'thumbnail_path',
        'konten',
        'kategori',
        'status',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (News $news) {
            if (empty($news->slug)) {
                $news->slug = static::uniqueSlug($news->judul);
            }
            if ($news->status === self::STATUS_PUBLISH && empty($news->published_at)) {
                $news->published_at = now();
            }
        });

        static::updating(function (News $news) {
            if ($news->isDirty('status') && $news->status === self::STATUS_PUBLISH && empty($news->published_at)) {
                $news->published_at = now();
            }
        });
    }

    private static function uniqueSlug(string $judul): string
    {
        $base = Str::slug($judul);
        $slug = $base;
        $i    = 1;
        while (static::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }
        return $slug;
    }

    public static function statuses(): array
    {
        return [self::STATUS_DRAFT, self::STATUS_PUBLISH];
    }

    public static function categories(): array
    {
        return ['Teknologi', 'Inovasi', 'Riset', 'Kegiatan', 'Pengumuman'];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PUBLISH);
    }
}
