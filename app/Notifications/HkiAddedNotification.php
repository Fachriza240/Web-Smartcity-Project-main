<?php

namespace App\Notifications;

use App\Models\Hki;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;


class HkiAddedNotification extends Notification
{
    use Queueable;

    public const MESSAGE = 'Anda ditambahkan sebagai pencipta HKI: ';

    public function __construct(
        protected Hki $hki,
        protected ?User $actor = null,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'hki_id'  => $this->hki->id,
            'judul'   => $this->hki->judul_sertifikat,
            'nomor'   => $this->hki->nomor_sertifikat,
            'oleh'    => $this->actor?->fullname,
            'message' => self::MESSAGE.$this->hki->judul_sertifikat,
        ];
    }
}