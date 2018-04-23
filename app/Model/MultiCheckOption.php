<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MultiCheckOption extends Model
{
     protected $fillable = [
		'option_id', 'name', 'price', 'checked'
	];

	public function option(){
    	return $this->belongsTo(Option::class);
    }
}
