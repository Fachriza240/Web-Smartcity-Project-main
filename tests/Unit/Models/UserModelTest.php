<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    public function test_is_pending_mengenali_status_pending(): void
    {
        $user = new User(['registration_status' => User::STATUS_PENDING]);

        $this->assertTrue($user->isPending());
        $this->assertFalse($user->isApproved());
        $this->assertFalse($user->isRejected());
    }

    public function test_is_approved_mengenali_status_approved(): void
    {
        $user = new User(['registration_status' => User::STATUS_APPROVED]);

        $this->assertTrue($user->isApproved());
        $this->assertFalse($user->isPending());
        $this->assertFalse($user->isRejected());
    }

    public function test_is_rejected_mengenali_status_rejected(): void
    {
        $user = new User(['registration_status' => User::STATUS_REJECTED]);

        $this->assertTrue($user->isRejected());
        $this->assertFalse($user->isPending());
        $this->assertFalse($user->isApproved());
    }

    public function test_status_kosong_tidak_dianggap_status_manapun(): void
    {
        $user = new User();

        $this->assertFalse($user->isPending());
        $this->assertFalse($user->isApproved());
        $this->assertFalse($user->isRejected());
    }

    public function test_password_otomatis_di_hash_saat_diisi(): void
    {
        $user = new User(['password' => 'rahasia123']);

        $this->assertNotSame('rahasia123', $user->password);
        $this->assertTrue(password_verify('rahasia123', $user->password));
    }
}