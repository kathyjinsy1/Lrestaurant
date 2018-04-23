<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
	protected $fillable = [
		'name', 'description'
	];

    public function restaurant(){
    	return $this->belongsTo(Restaurant::class);
    }

    public function items() {
    	return $this->hasMany(Item::class);
    }
}
