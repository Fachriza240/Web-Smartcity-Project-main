<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;


class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fullname'            => fake()->name(),
            'email'               => fake()->unique()->safeEmail(),
            'nip'                 => fake()->unique()->numerify('##########'),
            'prodi'               => 'Teknik Informatika',
            'fakultas'            => 'Fakultas Informatika',
            'bio'                 => null,
            'bidang_penelitian'   => null,
            'password'            => 'password',
            'role'                => 'dosen',
            'foto'                => null,
            'registration_status' => User::STATUS_APPROVED,
        ];
    }

    /**
     * State: akun admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role'                => 'admin',
            'nip'                 => fake()->unique()->numerify('##########'),
            'prodi'               => null,
            'fakultas'            => null,
            'registration_status' => User::STATUS_APPROVED,
        ]);
    }

    /**
     * State: akun dosen (default, disediakan agar penulisan test lebih jelas).
     */
    public function dosen(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'dosen',
        ]);
    }

    /**
     * State: akun content creator (tanpa NIP/prodi/fakultas).
     */
    public function contentCreator(): static
    {
        return $this->state(fn (array $attributes) => [
            'role'     => 'content_creator',
            'nip'      => null,
            'prodi'    => null,
            'fakultas' => null,
        ]);
    }

    /**
     * State: status registrasi pending (menunggu validasi admin).
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'registration_status' => User::STATUS_PENDING,
        ]);
    }

    /**
     * State: status registrasi approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'registration_status' => User::STATUS_APPROVED,
        ]);
    }

    /**
     * State: status registrasi rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'registration_status' => User::STATUS_REJECTED,
        ]);
    }
}