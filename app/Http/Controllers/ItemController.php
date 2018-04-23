<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use App\Model\Item;
use App\Model\Menu;
use App\Model\Restaurant;
use App\Http\Resources\ItemResource;
use Illuminate\Http\Request;
use App\Http\Requests\ItemRequest;
use App\Exceptions\NoSuchItem;
use App\Exceptions\NoSuchMenuInCurrentRestaurant;
use App\Exceptions\RestaurantNotBelongsToUser;
use Auth;

class ItemController extends Controller
{
    public function __construct(){
        $this -> middleware('auth:api') -> except('index','show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Restaurant $restaurant, Menu $menu)
    {
        return ItemResource::collection($menu->items);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ItemRequest $request, Restaurant $restaurant, Menu $menu)
    {
        $this->RestaurantUserCheck($restaurant);
        $this->MenuIDCheck($restaurant, $menu);
        $item = new Item($request->all());
        $menu->items()->save($item);
         return response([
            'data' => new ItemResource($item)

        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show(Restaurant $restaurant, Menu $menu, Item $item)
    {
        $this->ItemIDCheck($restaurant, $menu,$item);
        return new ItemResource($item);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Restaurant $restaurant, Menu $menu, Item $item)
    {
        $this->RestaurantUserCheck($restaurant);
        $this->MenuIDCheck($restaurant, $menu);
        $this->ItemIDCheck($restaurant, $menu,$item);
        $item->update($request->all());
         return response([
            'data' => new ItemResource($item)

        ], Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Restaurant $restaurant, Menu $menu, Item $item)
    {
        $this->RestaurantUserCheck($restaurant);
        $this->MenuIDCheck($restaurant, $menu);
        $this->ItemIDCheck($restaurant, $menu,$item);
        $item->delete();
        return response(null,Response::HTTP_NO_CONTENT);
    }

     protected function MenuIDCheck($restaurant, $menu) {
        if ($menu->restaurant_id !== $restaurant->id) {
            throw new NoSuchMenuInCurrentRestaurant;
        }
    }

    protected function ItemIDCheck($restaurant, $menu, $item) {
        if ($menu->restaurant_id !== $restaurant->id or 
            $item->menu_id !== $menu->id) {
            throw new NoSuchItem;
        }
    }

    protected function RestaurantUserCheck($restaurant) {
        if (Auth::id() !== $restaurant->user_id) {
            throw new RestaurantNotBelongsToUser;
        }
    }
}
