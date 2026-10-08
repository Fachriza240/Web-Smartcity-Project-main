<?php

namespace App\Notifications;

use App\Models\SuspiciousLoginReport;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SuspiciousLoginNotification extends Notification
{
    public function __construct(public SuspiciousLoginReport $report)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Laporan Aktivitas Login Mencurigakan')
            ->markdown('mail.laporan-aktivitas-mencurigakan', [
                'nama' => $notifiable->fullname,
                'waktu' => $this->report->login_at?->translatedFormat('d F Y, H:i') ?? '-',
                'email' => $this->report->email,
                'perangkat' => $this->report->device ?: '-',
                'ip' => $this->report->ip_address ?: '-',
                'terkunci' => $this->report->locked_until?->translatedFormat('d F Y, H:i') ?? '-',
                'url' => url('/beranda-admin'),
            ]);
    }
}
