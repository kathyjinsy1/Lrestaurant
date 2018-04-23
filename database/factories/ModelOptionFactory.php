<?php

use App\Model\Item;
use Faker\Generator as Faker;

$factory->define(App\Model\Option::class, function (Faker $faker) {
    return [
    	'item_id' => function(){
    		return Item::all()->random();
    	},
        'baseprice' => $faker->numberBetween(1,100),
        'quantity' => 1,
        'description' => $faker->paragraph,
    ];
});
