<?php

namespace App\View\Layout;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Layout;

class Home extends Layout
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
        return view('components.layout.home');
    }
}