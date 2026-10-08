<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RegistrationStatusNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected string $status,
        protected ?string $alasan = null,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $approved = $this->status === User::STATUS_APPROVED;

        return [
            'jenis' => 'registrasi',
            'status' => $this->status,
            'alasan' => $approved ? null : $this->alasan,
            'message' => $approved
                ? 'Registrasi akun Anda telah disetujui oleh Admin.'
                : 'Registrasi akun Anda ditolak oleh Admin.',
        ];
    }
}
