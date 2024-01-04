<?php

namespace App\View\Components;

use App\Models\Film;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AgeLimit extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Film $film
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.age-limit');
    }
}
