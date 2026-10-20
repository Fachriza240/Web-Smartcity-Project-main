<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DssAhp extends Model
{
    protected $table = 'dss_ahp';

    protected $fillable = [
        'matriks',
        'bobot',
        'lambda_max',
        'ci',
        'cr',
        'konsisten',
        'aktif',
        'catatan',
        'dibuat_oleh',
    ];

    protected $casts = [
        'matriks' => 'array',
        'bobot' => 'array',
        'lambda_max' => 'float',
        'ci' => 'float',
        'cr' => 'float',
        'konsisten' => 'boolean',
        'aktif' => 'boolean',
    ];

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('aktif', true);
    }

    public static function terpakai(): ?self
    {
        return static::aktif()->latest('id')->first();
    }

    public function jadikanAktif(): void
    {
        DB::transaction(function () {
            static::where('id', '!=', $this->id)->where('aktif', true)->update(['aktif' => false]);
            $this->forceFill(['aktif' => true])->save();
        });
    }
}