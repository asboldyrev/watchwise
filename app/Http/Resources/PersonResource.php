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
        return parent::toArray($request) + ['images' => $this->getImages()];
    }
}
