<?php

namespace App\View\Authorized;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Authorized;

class Login extends Authorized
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
        return view('components.authorized.login');
    }
}
