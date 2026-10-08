<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPassword
{
    protected function buildMailMessage($url)
    {
        $menit = config('auth.passwords.'.config('auth.defaults.passwords').'.expire');

        return (new MailMessage)
            ->subject('Reset Password Akun '.config('smartcity.short_name'))
            ->greeting('Halo!')
            ->line('Kami menerima permintaan untuk mengatur ulang password akun Anda.')
            ->action('Reset Password', $url)
            ->line("Tautan reset password ini berlaku selama {$menit} menit.")
            ->line('Jika Anda tidak merasa meminta reset password, abaikan email ini.')
            ->salutation('Salam, Tim '.config('smartcity.name'));
    }
}
