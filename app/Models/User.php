<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use Closure;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    const INACTIVE_MESSAGE = 'Akun Anda telah dinonaktifkan oleh Admin sehingga tidak dapat digunakan untuk login. Silakan hubungi Admin untuk mengaktifkan kembali akun Anda.';

    protected $table = 'users';

    protected $fillable = [
        'fullname',
        'email',
        'nip',
        'prodi',
        'fakultas',
        'bio',
        'bidang_penelitian',
        'password',
        'role',
        'foto',
        'registration_status',
        'rejection_reason',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
        'locked_until' => 'datetime',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public static function emailCredential(string $email): Closure
    {
        $email = mb_strtolower(trim($email));

        return fn ($query) => $query->whereRaw('LOWER(email) = ?', [$email]);
    }

    public function loginConfirmations(): HasMany
    {
        return $this->hasMany(LoginConfirmation::class);
    }

    public function isPending(): bool
    {
        return ($this->registration_status ?? null) === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return ($this->registration_status ?? null) === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return ($this->registration_status ?? null) === self::STATUS_REJECTED;
    }

    public function isActive(): bool
    {
        return $this->is_active !== false;
    }

    public function isLocked(): bool
    {
        return $this->locked_until !== null && $this->locked_until->isFuture();
    }

    public function lockMessage(): string
    {
        $jam = $this->locked_until?->format('H:i') ?? '-';

        return "Akun Anda dikunci sementara karena terdeteksi aktivitas login mencurigakan. Silakan coba lagi setelah pukul {$jam} WIB atau ganti password melalui menu Lupa Password.";
    }

    public function lastActivityAt()
    {
        return $this->last_login_at ?? $this->created_at;
    }

    public function isDormant(): bool
    {
        $days = (int) config('login.dormant_days', 90);
        $last = $this->lastActivityAt();

        return $days > 0 && $last !== null && $last->lt(now()->subDays($days));
    }
}
