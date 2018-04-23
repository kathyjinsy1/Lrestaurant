<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
		'name', 'address', 'cellphone', 'email', 'payment'
	];

	 public function orders(){
    	return $this->hasMany(Order::class);
    }
}
