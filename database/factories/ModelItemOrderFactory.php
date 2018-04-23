<?php

use App\Model\Item;
use Faker\Generator as Faker;

$factory->define(App\Model\ItemOrder::class, function (Faker $faker) {
    return [
        'item_id' => function(){
    		return Item::all()->random();
    	},
    	'option_id' => 1,
    	'option_quantity' => 1, 
		'multicheckoption_id' => 1,
		'multicheckoption_checked' => 1
    ];
});
