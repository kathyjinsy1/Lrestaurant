<?php

namespace App\Http\Resources\Restaurant;

use Illuminate\Http\Resources\Json\Resource;

class RestaurantResource extends Resource
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
            'detail' => $this->description,
            'href' => [
                'menus' => route('menus.index', $this->id)
            ]
        ];
    }
}
