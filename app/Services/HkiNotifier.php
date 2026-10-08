<?php

namespace App\Services;

use App\Models\Hki;
use App\Models\User;
use App\Notifications\HkiAddedNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Collection;

class HkiNotifier
{
    public function sync(Hki $hki, ?User $actor = null, ?string $previousPencipta = null): void
    {
        $current = $this->listedDosen($hki->pencipta);
        $previous = $this->listedDosen($previousPencipta);

        $this->notificationsOf($hki)
            ->whereNotIn('notifiable_id', $current->keys()->all())
            ->delete();

        $existing = $this->notificationsOf($hki)->get();

        foreach ($existing as $notification) {
            $data = $notification->data;

            if (($data['judul'] ?? null) !== $hki->judul_sertifikat || ($data['nomor'] ?? null) !== $hki->nomor_sertifikat) {
                $data['judul'] = $hki->judul_sertifikat;
                $data['nomor'] = $hki->nomor_sertifikat;
                $data['message'] = HkiAddedNotification::MESSAGE.$hki->judul_sertifikat;
                $notification->forceFill(['data' => $data])->save();
            }
        }

        $alreadyNotified = $existing->pluck('notifiable_id')->map(fn ($id) => (int) $id)->all();

        $current
            ->except($previous->keys()->all())
            ->reject(fn (User $user) => $user->id === $actor?->id)
            ->reject(fn (User $user) => in_array($user->id, $alreadyNotified, true))
            ->each(fn (User $user) => $user->notify(new HkiAddedNotification($hki, $actor)));
    }

    public function forget(Hki $hki): void
    {
        $this->notificationsOf($hki)->delete();
    }

    private function listedDosen(?string $pencipta): Collection
    {
        $list = $this->normalize($pencipta);

        if ($list === '') {
            return collect();
        }

        $haystack = ', '.$list.', ';

        return User::query()
            ->where('role', 'dosen')
            ->where('registration_status', User::STATUS_APPROVED)
            ->get(['id', 'fullname'])
            ->filter(function (User $user) use ($haystack) {
                $name = $this->normalize($user->fullname);

                return $name !== '' && str_contains($haystack, ', '.$name.', ');
            })
            ->keyBy('id');
    }

    private function normalize(?string $value): string
    {
        $value = preg_replace('/\s+/u', ' ', (string) $value);
        $value = preg_replace('/\s*,\s*/u', ', ', $value);

        return mb_strtolower(trim($value, " ,"));
    }

    private function notificationsOf(Hki $hki): Builder
    {
        return DatabaseNotification::query()
            ->where('type', HkiAddedNotification::class)
            ->where('notifiable_type', (new User)->getMorphClass())
            ->where('data->hki_id', $hki->id);
    }
}