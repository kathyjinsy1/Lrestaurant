<?php

use App\Model\Restaurant;
use Faker\Generator as Faker;

$factory->define(App\Model\Menu::class, function (Faker $faker) {
    return [
    	'restaurant_id' => function(){
    		return Restaurant::all()->random();
    	},
        'name' => $faker->word,
        'description' => $faker->paragraph,
    ];
});
