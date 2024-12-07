<?php

namespace App\Http\Resources;

use App\Http\Resources\Traits\Images;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FilmResource extends JsonResource
{
    use Images;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'imdb_id' => $this->imdb_id,
            'name' => $this->name,
            'rating' => $this->rating,
            'year' => $this->year,
            'length' => $this->length,
            'slogan' => $this->slogan,
            'description' => $this->description,
            'short_description' => $this->short_description,
            'type' => $this->type,
            'mpaa' => $this->mpaa,
            'age_limits' => $this->age_limits,
            'start_year' => $this->start_year,
            'end_year' => $this->end_year,
            'serial' => $this->serial,
            'short' => $this->short,
            'completed' => $this->completed,
            'age' => __($this->age_limits),
            'images' => $this->getImages(),
            'countries' => CountryResource::collection($this->whenLoaded('countries')),
            'genres' => GenreResource::collection($this->whenLoaded('genres')),
            'theaters' => TheaterResource::collection($this->whenLoaded('onlineTheaters')),
            'seasons' => SeasonResource::collection($this->whenLoaded('seasons')),
            'related_films' => self::collection($this->whenLoaded('relatedFilms')),
            'pivot' => $this?->pivot,
        ];
    }
}
