<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

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
        'registration_status'
    ];

    protected $hidden = ['password'];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

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


    public function dashboardUrl(): string
    {
        if (in_array($this->role, ['dosen', 'content_creator'], true) && !$this->isApproved()) {
            return route('dosen.status');
        }

        return match ($this->role) {
            'admin'           => '/beranda-admin',
            'dosen'           => '/beranda-dosen',
            'content_creator' => '/beranda-creator',
            default           => '/',
        };
    }
}