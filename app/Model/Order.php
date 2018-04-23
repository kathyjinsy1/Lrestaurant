<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
		'restaurant_id'
	];

    public function account(){
    	return $this->belongsTo(Customer::class);
    }

    public function items() {
    	return $this->hasMany(ItemOrder::class);
    }
}
