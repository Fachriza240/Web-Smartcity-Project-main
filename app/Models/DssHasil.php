<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DssHasil extends Model
{
    protected $table = 'dss_hasil';

    protected $fillable = [
        'user_id',
        'ahp_id',
        'poin_mentah',
        'skor_normal',
        'skor_akhir',
        'peringkat',
        'kategori',
        'kekuatan_utama',
        'cluster',
        'rekomendasi_peran',
        'gap',
        'kompetensi',
        'dihitung_pada',
    ];

    protected $casts = [
        'poin_mentah' => 'array',
        'skor_normal' => 'array',
        'gap' => 'array',
        'kompetensi' => 'array',
        'skor_akhir' => 'float',
        'peringkat' => 'integer',
        'dihitung_pada' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ahp()
    {
        return $this->belongsTo(DssAhp::class, 'ahp_id');
    }
}