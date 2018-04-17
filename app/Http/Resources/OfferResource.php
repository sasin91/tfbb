<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
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
            //'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'tagline' => $this->tagline,
            'discount' => $this->discount, 
            'body' => $this->body,
            'poster_url' => $this->poster_url, 
            'banner_url' => $this->banner_url,
            'view' => $this->view,
            
            'links' => [
                'self' => $this->link,
                'product' => $this->offsite_link
            ]
        ];
    }
}
