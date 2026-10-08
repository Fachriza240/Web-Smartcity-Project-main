<?php

namespace App\Notifications;

use App\Models\LoginConfirmation;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoginConfirmationNotification extends Notification
{
    public function __construct(
        public LoginConfirmation $confirmation,
        public string $token,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Konfirmasi Login Akun '.config('smartcity.short_name'))
            ->markdown('mail.konfirmasi-login', [
                'nama' => $notifiable->fullname,
                'email' => $notifiable->email,
                'waktu' => $this->confirmation->created_at->translatedFormat('d F Y, H:i'),
                'perangkat' => $this->confirmation->deviceLabel(),
                'ip' => $this->confirmation->ip_address ?: '-',
                'menit' => LoginConfirmation::lifetime(),
                'urlIniSaya' => route('login.konfirmasi.tinjau', ['token' => $this->token, 'aksi' => 'ini-saya']),
                'urlBukanSaya' => route('login.konfirmasi.tinjau', ['token' => $this->token, 'aksi' => 'bukan-saya']),
            ]);
    }
}
