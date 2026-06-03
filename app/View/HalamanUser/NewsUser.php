<?php

namespace App\View\Authorized;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\HalamanUser;

class NewsUser extends HalamanUser
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.halaman-user.news-user');
    }
}
