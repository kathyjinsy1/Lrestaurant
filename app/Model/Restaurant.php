<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
	protected $fillable = [
		'name', 'description'
	];

    public function menus() {
    	return $this->hasMany(Menu::class);
    }

    public function contacts(){
    	return $this->hasMany(Contact::class);
    }
}
