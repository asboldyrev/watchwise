<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $urls = [];

        foreach ($this->generated_conversions as $conversion_name => $has_conversion) {
            if ($has_conversion) {
                $urls[$conversion_name] = $this->getUrl($conversion_name);
            }
        }

        return [
            'media' => [
                'id' => $this->id,
                'collection_name' => $this->collection_name,
                'name' => $this->name,
                'file_name' => $this->file_name,
                'mime_type' => $this->mime_type,
                'size' => $this->size,
                'custom_properties' => $this->custom_properties,
                'generated_conversions' => $this->generated_conversions,
                '_lft' => $this->_lft,
                '_rgt' => $this->_rgt,
                'parent_id' => $this->parent_id,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'original_url' => $this->original_url,
                'preview_url' => $this->preview_url,
            ],
            'urls' => $urls
        ];
    }
}
