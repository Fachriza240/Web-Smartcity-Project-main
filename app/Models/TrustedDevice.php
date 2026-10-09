<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class TrustedDevice extends Model
{
    public const COOKIE = 'sc_perangkat';

    protected $fillable = [
        'user_id',
        'token',
        'ip_address',
        'user_agent',
        'last_used_at',
    ];

    protected $hidden = ['token'];

    protected $casts = [
        'last_used_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function lifetimeDays(): int
    {
        return max(0, (int) config('login.trusted_device_days', 30));
    }

    public static function recognize(User $user, Request $request): ?self
    {
        $token = static::tokenFrom($request);

        if (static::lifetimeDays() === 0 || $token === null || $user->isDormant()) {
            return null;
        }

        return static::where('user_id', $user->id)
            ->where('token', hash('sha256', $token))
            ->where('last_used_at', '>=', now()->subDays(static::lifetimeDays()))
            ->first();
    }

    public static function remember(User $user, Request $request): void
    {
        if (static::lifetimeDays() === 0) {
            return;
        }

        $token = static::tokenFrom($request) ?? Str::random(64);

        static::updateOrCreate(
            ['user_id' => $user->id, 'token' => hash('sha256', $token)],
            [
                'ip_address' => $request->ip(),
                'user_agent' => Str::limit((string) $request->userAgent(), 250, ''),
                'last_used_at' => now(),
            ]
        );

        static::queueCookie($token);
    }

    public static function forgetFor(User $user): void
    {
        static::where('user_id', $user->id)->delete();
    }

    public function markUsed(Request $request): void
    {
        $this->forceFill([
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 250, ''),
            'last_used_at' => now(),
        ])->save();

        $token = static::tokenFrom($request);

        if ($token !== null) {
            static::queueCookie($token);
        }
    }

    private static function tokenFrom(Request $request): ?string
    {
        $token = $request->cookie(self::COOKIE);

        return is_string($token) && preg_match('/^[A-Za-z0-9]{64}$/', $token) ? $token : null;
    }

    private static function queueCookie(string $token): void
    {
        Cookie::queue(self::COOKIE, $token, static::lifetimeDays() * 1440);
    }
}