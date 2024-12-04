<?php

namespace App\Http\Resources\Traits;

use App\Http\Resources\MediaResource;

trait Images
{
    protected function getImages(): array
    {
        $images = [];

        if ($this->relationLoaded('media')) {
            foreach ($this->media as $media) {
                $images[$media->collection_name][] = new MediaResource($media);
            }
        }

        return $images;
    }
}
