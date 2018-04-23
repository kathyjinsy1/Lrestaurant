<?php

use App\Model\Menu;
use Faker\Generator as Faker;

$factory->define(App\Model\Item::class, function (Faker $faker) {
    return [
    	'menu_id' => function(){
    		return Menu::all()->random();
    	},
        'name' => $faker->word,
        'category' => $faker->word,
        'description' => $faker->paragraph,
    ];
});
