<?php

namespace App\Http\Resources;

use App\Http\Resources\MediaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DietResource extends JsonResource
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
            'title' => $this->title,
            'summary' => $this->summary,
            'goal' => $this->goal,
            'style' => $this->style,
            'link' => $this->link,
            'urls' => $this->urls,
            'summary' => $this->summary,
            'body' => $this->body,
            'banner_url' => $this->banner_url,
            'view' => $this->view,
            'meals_count' => $this->meals_count ?? 0,

            'meals' => $this->whenLoaded('meals', function () {
                return $this->meals->sortBy('type');
            }),

            'files_count' => $this->files_count ?? 0,
            'files' => $this->whenLoaded('media', function () {
                return MediaResource::collection($this->media);
            })
        ];
    }
}
