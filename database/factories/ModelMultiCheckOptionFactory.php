<?php

use App\Model\Option;
use Faker\Generator as Faker;

$factory->define(App\Model\MultiCheckOption::class, function (Faker $faker) {
    return [
        'option_id' => function(){
    		return Option::all()->random();
    	},
    	'name' => $faker->word,
        'price' => $faker->numberBetween(1,100),
        'checked' => false
    ];
});
