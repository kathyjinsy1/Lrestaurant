<?php

use Faker\Generator as Faker;

$factory->define(App\Model\Restaurant::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->paragraph,
        'user_id' => function() {
        	return App\User::all()->random();
        }	
    ];
});
