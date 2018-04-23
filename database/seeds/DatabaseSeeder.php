<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //$this->call(RestaurantTableSeeder::class);
        factory(App\User::class,5)->create();
        factory(App\Model\Restaurant::class, 10)->create()->each(function ($restaurant) {
        $restaurant->contacts()->save(factory(App\Model\Contact::class)->make());
          });
        factory(App\Model\Menu::class,30)->create();
        factory(App\Model\Item::class,100)->create();
        factory(App\Model\Option::class,200)->create();
        factory(App\Model\MultiCheckOption::class, 500)->create();
        factory(App\Model\Customer::class, 100)->create();
        factory(App\Model\Order::class, 300)->create();
    }
}
