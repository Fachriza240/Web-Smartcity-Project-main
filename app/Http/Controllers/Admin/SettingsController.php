<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['admin', 'content_creator'], true)) {
            abort(403);
        }

        return view('admin.settings.index');
    }
}
