<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkoutResource extends JsonResource
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
            'link' => route('workouts.show', $this->resource),
            'title' => $this->title,
            'level' => $this->level, 
            'type' => $this->type,
            'summary' => $this->type,
            'body' => $this->body,

            'documents' => $this->whenLoaded('media', function () {
                return $this->getMedia('documents')->map(function ($media) {
                    return [
                        'download_link' => $media->getUrl()
                    ];
                });
            }),

            'videos' => $this->whenLoaded('media', function () {
                return $this->getMedia('videos')->map(function ($media) {
                    return [
                        'thumbnail' => $media->getUrl('thumbnail'),
                        'url' => $media->getUrl()
                    ];
                });
            }),
            
            'photos' => $this->whenLoaded('media', function () {
                return $this->getMedia('photos')->map(function ($media) {
                    return [
                        'thumbnail' => $media->getUrl('thumbnail'),
                        'url' => $media->getUrl()
                    ];
                });
            }),
        ];
    }
}
