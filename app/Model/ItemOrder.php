<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ItemOrder extends Model
{
    protected $fillable = [
		'item_id', 'option_id', 'option_quantity', 
		'multicheckoption_id', 'multicheckoption_checked'
	];

	public function order() {
		return $this->belongsTo(Order::class);
	}
}
