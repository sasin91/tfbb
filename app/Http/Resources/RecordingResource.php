<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;

class RecordingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'category' => $this->category, 
            'title' => $this->title,  
            'slug' => $this->slug,
            'link' => $this->link,
            'urls' => $this->urls,
            'summary' => $this->summary, 
            'body' => $this->body,
            'media_count' => $this->whenLoaded('media', function () {
                return $this->media->count();
            }),
            'media' => $this->whenLoaded('media', function () {
                return $this->media->map(function ($media) {
                    return [
                        'url' => $media->getUrl(),
                        'poster' => !empty($media->getUrl('thumbnail')) ?: new MissingValue,
                        'name' => $media->name,
                        'mime_type' => $media->mime_type,
                        'created_at' => $media->created_at->format('Y-m-d H:i:s')
                    ];
                });
            })
        ];
    }
}
