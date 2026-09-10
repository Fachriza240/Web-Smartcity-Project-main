<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\AuthorizesRoles;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    use AuthorizesRoles;

    public function index()
    {
        $this->authorizeContentManager();

        return view('admin.settings.index');
    }
}