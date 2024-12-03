<?php

namespace App\Http\Resources\KinopoiskUnofficial;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FilmResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'last_page' => $this->pagesCount,
            'total' => $this->searchFilmsCountResult,
            'films' => $this->films,
        ];
    }
}
