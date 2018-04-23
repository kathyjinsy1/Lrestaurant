<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
		'name','category', 'description'
	];

    public function menu(){
    	return $this->belongsTo(Menu::class);
    }

    public function options() {
    	return $this->hasMany(Option::class);
    }
}
