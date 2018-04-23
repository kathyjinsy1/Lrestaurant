<?php

use Faker\Generator as Faker;

$factory->define(App\Model\Contact::class, function (Faker $faker) {
    return [
        'operatinghours' => $faker->paragraph,
        'address' => $faker->paragraph,
        'cellphone'=> $faker->word,
        'additionalinformation' => $faker->paragraph,
    ];
});
