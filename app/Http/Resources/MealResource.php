<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MealResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'type' => $this->type,
            'description' => $this->description,
            'photo_url' => $this->photo_url,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'urls' => $this->urls,
            'foods_count' => $this->foods_count ?? 0,
            'foods' => $this->whenLoaded('foods', function () {
                return $this->foods;
            }),
            'diets' => $this->whenLoaded('diets', function () {
                return $this->diets;
            })
        ];
    }
}
