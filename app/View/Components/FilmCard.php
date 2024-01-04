<?php

namespace App\View\Components;

use App\Models\Film;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FilmCard extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Film $film,
        public Carbon $date,
        public int|null $vote = null
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.film-card');
    }
}
