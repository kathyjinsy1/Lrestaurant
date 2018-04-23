<?php

use Faker\Generator as Faker;

$factory->define(App\Model\Customer::class, function (Faker $faker) {
    return [
            'name' => $faker->word,
            'address' => $faker->paragraph,
            'cellphone' => $faker->word,
            'email' => $faker->word,
            'payment' => $faker->paragraph,
            'user_id' => function() {
        		return App\User::all()->random();
        	}	
    ];
});
