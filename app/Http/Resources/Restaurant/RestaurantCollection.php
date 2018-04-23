<?php

namespace App\Http\Resources\Restaurant;

use Illuminate\Http\Resources\Json\Resource;

class RestaurantCollection extends Resource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'detail' => $this->description,
            'href' => [
                'link' => route('restaurants.show', $this->id)
            ]
        ];
    }
}
