<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WatchlistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'films' => FilmResource::collection($this->whenLoaded('films')),
            'film_added' => $this->when(
                $request->input('film_id'),
                $this->films()->where('films.id', $request->input('film_id'))->count() != 0
            ),
        ];
    }
}
