<?php

namespace App\Http\Resources;

use App\Http\Resources\Traits\Images;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonResource extends JsonResource
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
            'name' => $this->name,
            'sex' => $this->sex,
            'growth' => $this->growth,
            'birthday' => $this->birthday,
            'death' => $this->death,
            'age' => $this->age,
            'birth_place' => $this->birth_place,
            'death_place' => $this->death_place,
            'spouses' => $this->spouses,
            'awards_count' => $this->awards_count,
            'profession' => $this->profession,
            'facts' => $this->facts,
            'images' => $this->getImages(),
            'films' => FilmResource::collection($this->whenLoaded('films')),
            'pivot' => $this?->pivot,
        ];
    }
}
