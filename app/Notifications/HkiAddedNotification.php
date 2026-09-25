<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Hki;

class HkiAddedNotification extends Notification
{
    use Queueable;

    protected $hki;

    public function __construct(Hki $hki)
    {
        $this->hki = $hki;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'hki_id' => $this->hki->id,
            'judul'  => $this->hki->judul_sertifikat,
            'message'=> 'Anda telah ditambahkan menjadi pencipta HKI: ' . $this->hki->judul_sertifikat,
        ];
    }
}
