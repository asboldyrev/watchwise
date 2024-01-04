<?php

namespace App\View\Components;

use App\Models\WatchList;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;

class Menu extends Component
{

    public $currentRouteName;

    public $firstWatchList;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->currentRouteName = Route::currentRouteName();
        $this->firstWatchList = WatchList::orderBy('name')->first();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.menu');
    }
}
