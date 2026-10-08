<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LoginConfirmation extends Model
{
    public const SESSION_KEY = 'login_confirmation_id';

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_USED = 'used';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_EXPIRED = 'expired';

    protected $fillable = [
        'user_id',
        'token',
        'status',
        'remember',
        'ip_address',
        'user_agent',
        'expires_at',
        'responded_at',
    ];

    protected $hidden = ['token'];

    protected $casts = [
        'remember' => 'boolean',
        'expires_at' => 'datetime',
        'responded_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function lifetime(): int
    {
        return max(1, (int) config('login.confirmation_expire', 15));
    }

    public static function issue(User $user, Request $request, bool $remember): array
    {
        static::where('user_id', $user->id)
            ->where('created_at', '<', now()->subDay())
            ->delete();

        $token = Str::random(64);

        $confirmation = static::create([
            'user_id' => $user->id,
            'token' => hash('sha256', $token),
            'status' => self::STATUS_PENDING,
            'remember' => $remember,
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 250, ''),
            'expires_at' => now()->addMinutes(static::lifetime()),
        ]);

        return [$confirmation, $token];
    }

    public static function findByToken(string $token): ?self
    {
        return static::with('user')->where('token', hash('sha256', $token))->first();
    }

    public static function cancelPendingFor(User $user): void
    {
        static::where('user_id', $user->id)
            ->whereIn('status', [self::STATUS_PENDING, self::STATUS_APPROVED])
            ->update(['status' => self::STATUS_CANCELLED]);
    }

    public function refreshToken(): string
    {
        $token = Str::random(64);

        $this->forceFill([
            'token' => hash('sha256', $token),
            'expires_at' => now()->addMinutes(static::lifetime()),
        ])->save();

        return $token;
    }

    public function state(): string
    {
        if (in_array($this->status, [self::STATUS_PENDING, self::STATUS_APPROVED], true)
            && ($this->expires_at === null || $this->expires_at->isPast())) {
            return self::STATUS_EXPIRED;
        }

        return $this->status;
    }

    public function deviceLabel(): string
    {
        $agent = (string) $this->user_agent;

        $browser = match (true) {
            str_contains($agent, 'Edg/') => 'Microsoft Edge',
            str_contains($agent, 'OPR/'), str_contains($agent, 'Opera') => 'Opera',
            str_contains($agent, 'SamsungBrowser') => 'Samsung Internet',
            str_contains($agent, 'Firefox/') => 'Mozilla Firefox',
            str_contains($agent, 'Chrome/') => 'Google Chrome',
            str_contains($agent, 'Safari/') => 'Safari',
            default => 'Browser tidak dikenal',
        };

        $system = match (true) {
            str_contains($agent, 'Windows') => 'Windows',
            str_contains($agent, 'Android') => 'Android',
            str_contains($agent, 'iPhone'), str_contains($agent, 'iPad') => 'iOS',
            str_contains($agent, 'Macintosh'), str_contains($agent, 'Mac OS X') => 'macOS',
            str_contains($agent, 'Linux') => 'Linux',
            default => 'sistem operasi tidak dikenal',
        };

        return "{$browser} di {$system}";
    }
}
