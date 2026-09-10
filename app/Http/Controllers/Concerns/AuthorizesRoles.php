<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Support\Facades\Auth;

trait AuthorizesRoles
{

    protected function authorizeRole(array $allowedRoles): void
    {
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        if (!in_array($user->role, $allowedRoles, true)) {
            abort(403);
        }
    }

    protected function authorizeAdmin(): void
    {
        $this->authorizeRole(['admin']);
    }

    protected function authorizeContentManager(): void
    {
        $this->authorizeRole(['admin', 'content_creator']);
    }
}