<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $fillable = [
		'baseprice', 'quantity', 'description'
	];

    public function item(){
    	return $this->belongsTo(Item::class);
    }

    public function multicheckoptions() {
    	return $this->hasMany(MultiCheckOption::class);
    }
}
