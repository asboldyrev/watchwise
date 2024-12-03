<?php

namespace App\Http\Resources;

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
        $images = [];

        if ($this->relationLoaded('media')) {
            foreach ($this->media as $media) {
                $images[$media->collection_name][] = new MediaResource($media);
            }
        }

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
            'images' => $images,
            'countries' => $this->whenLoaded('countries'),
            'genres' => $this->whenLoaded('genres'),
        ];
    }
}
