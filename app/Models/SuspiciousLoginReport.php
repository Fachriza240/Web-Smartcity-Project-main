<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuspiciousLoginReport extends Model
{
    protected $fillable = [
        'user_id',
        'email',
        'device',
        'ip_address',
        'user_agent',
        'login_at',
        'locked_until',
    ];

    protected $casts = [
        'login_at' => 'datetime',
        'locked_until' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function fromConfirmation(LoginConfirmation $confirmation): self
    {
        $user = $confirmation->user;

        return static::create([
            'user_id' => $user?->id,
            'email' => (string) $user?->email,
            'device' => $confirmation->deviceLabel(),
            'ip_address' => $confirmation->ip_address,
            'user_agent' => $confirmation->user_agent,
            'login_at' => $confirmation->created_at,
            'locked_until' => $user?->locked_until,
        ]);
    }
}
