<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use App\Model\Option;
use App\Model\Item;
use App\Model\Menu;
use App\Model\Restaurant;
use App\Http\Resources\OptionResource;
use Illuminate\Http\Request;
use App\Http\Requests\OptionRequest;
use App\Exceptions\NoSuchItem;
use App\Exceptions\RestaurantNotBelongsToUser;
use Auth;

class OptionController extends Controller
{
    public function __construct(){
        $this -> middleware('auth:api') -> except('index','show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Restaurant $restaurant, Menu $menu, Item $item)
    {
        return OptionResource::collection($item->options);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OptionRequest $request, Restaurant $restaurant, Menu $menu, Item $item)
    {
        $this->RestaurantUserCheck($restaurant);
        $this->ItemIDCheck($restaurant, $menu, $item);
        $option = new Option($request->all());
        $item->options()->save($option);
         return response([
            'data' => new OptionResource($option)

        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show(Restaurant $restaurant, Menu $menu, Item $item, Option $option)
    {
        $this->OptionIDCheck($restaurant, $menu,$item,$option);
        return new OptionResource($option);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Restaurant $restaurant, Menu $menu, Item $item, Option $option)
    {
        $this->RestaurantUserCheck($restaurant);
        $this->ItemIDCheck($restaurant, $menu,$item);
        $this->OptionIDCheck($restaurant, $menu, $item, $option);
        $option->update($request->all());
         return response([
            'data' => new OptionResource($option)

        ], Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Restaurant $restaurant, Menu $menu, Item $item, Option $option)
    {
        $this->RestaurantUserCheck($restaurant);
        $this->ItemIDCheck($restaurant, $menu,$item);
        $this->OptionIDCheck($restaurant, $menu, $item, $option);
        $option->delete();
        return response(null,Response::HTTP_NO_CONTENT);
    }

     protected function ItemIDCheck($restaurant, $menu, $item) {
        if ($menu->restaurant_id !== $restaurant->id or 
            $item->menu_id !== $menu->id) {
            throw new NoSuchItem;
        }
    }

    protected function OptionIDCheck($restaurant, $menu, $item, $option) {
        if ($menu->restaurant_id !== $restaurant->id or 
            $item->menu_id !== $menu->id or
            $option->item_id !== $item->id) {
            throw new NoSuchItem;
        }
    }

    protected function RestaurantUserCheck($restaurant) {
        if (Auth::id() !== $restaurant->user_id) {
            throw new RestaurantNotBelongsToUser;
        }
    }
}
