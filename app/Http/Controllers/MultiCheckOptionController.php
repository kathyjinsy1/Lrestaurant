<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use App\Model\MultiCheckOption;
use App\Model\Option;
use App\Model\Item;
use App\Model\Menu;
use App\Model\Restaurant;
use App\Http\Resources\MultiCheckOptionResource;
use Illuminate\Http\Request;
use App\Http\Requests\MultiCheckOptionRequest;
use App\Exceptions\NoSuchItem;
use App\Exceptions\RestaurantNotBelongsToUser;
use Auth;

class MultiCheckOptionController extends Controller
{
    public function __construct(){
        $this -> middleware('auth:api') -> except('index','show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Restaurant $restaurant, Menu $menu, Item $item, Option $option)
    {
        return MultiCheckOptionResource::collection($option->multicheckoptions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MultiCheckOptionRequest $request, Restaurant $restaurant, Menu $menu, Item $item, Option $option)
    {
        $this->RestaurantUserCheck($restaurant);
        $this->OptionIDCheck($restaurant, $menu, $item, $option);
        $multicheckoption = new MultiCheckOption($request->all());
        $option->multicheckoptions()->save($multicheckoption);
         return response([
            'data' => new MultiCheckOptionResource($multicheckoption)

        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show(Restaurant $restaurant, Menu $menu, Item $item, Option $option, MultiCheckOption $multicheckoption)
    {
        $this->MultiCheckOptionIDCheck($restaurant, $menu,$item,$option,$multicheckoption);
        return new MultiCheckOptionResource($multicheckoption);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Restaurant $restaurant, Menu $menu, Item $item, Option $option, MultiCheckOption $multicheckoption)
    {
        $this->RestaurantUserCheck($restaurant);
        $this->OptionIDCheck($restaurant, $menu, $item, $option);
        $this->MultiCheckOptionIDCheck($restaurant, $menu, $item, $option, $multicheckoption);
        $multicheckoption->update($request->all());
         return response([
            'data' => new MultiCheckOptionResource($multicheckoption)

        ], Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Restaurant $restaurant, Menu $menu, Item $item, Option $option, MultiCheckOption $multicheckoption)
    {
        $this->RestaurantUserCheck($restaurant);
        $this->OptionIDCheck($restaurant, $menu, $item, $option);
        $this->MultiCheckOptionIDCheck($restaurant, $menu, $item, $option, $multicheckoption);
        $multicheckoption->delete();
        return response(null,Response::HTTP_NO_CONTENT);
    }

    protected function OptionIDCheck($restaurant, $menu, $item, $option) {
        if ($menu->restaurant_id !== $restaurant->id or 
            $item->menu_id !== $menu->id or
            $option->item_id !== $item->id) {
            throw new NoSuchItem;
        }
    }

    protected function MultiCheckOptionIDCheck($restaurant, $menu, $item, $option, $multicheckoption) {
         if ($menu->restaurant_id !== $restaurant->id or 
            $item->menu_id !== $menu->id or
            $option->item_id !== $item->id or
            $multicheckoption->option_id !== $option->id) {
            throw new NoSuchItem;
        }
    }

    protected function RestaurantUserCheck($restaurant) {
        if (Auth::id() !== $restaurant->user_id) {
            throw new RestaurantNotBelongsToUser;
        }
    }
}
