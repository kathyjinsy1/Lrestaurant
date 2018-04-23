<?php

use App\Model\Restaurant;
use App\Model\Customer;
use Faker\Generator as Faker;

$factory->define(App\Model\Order::class, function (Faker $faker) {
    return [
        'restaurant_id' => function() {
        	return Restaurant::all()->random();
    	},
        'customer_id' => function() {
        	return Customer::all()->random();
    	}
    ];
});

