<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
		'operatinghours','address','cellphone','additionalinformation'
	];

    public function restaurant(){
    	return $this->belongsTo(Restaurant::class);
    }
}
