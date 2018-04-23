<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemOrderResource extends JsonResource
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
            'item_id' => $this->item_id,
            'option_id' => $this->option_id,
            'option_quantity' => $this->option_quantity,
            'multicheckoption_id' => $this->multicheckoption_id,
            'multicheckoption_checked' => $this->multicheckoption_checked
        ];
    }
}
