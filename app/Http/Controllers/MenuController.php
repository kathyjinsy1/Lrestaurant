<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use App\Model\Menu;
use App\Model\Restaurant;
use App\Http\Resources\MenuResource;
use Illuminate\Http\Request;
use App\Exceptions\NoSuchMenuInCurrentRestaurant;
use App\Exceptions\RestaurantNotBelongsToUser;
use App\Http\Requests\MenuRequest;
use Auth;

class MenuController extends Controller
{
    public function __construct(){
        $this -> middleware('auth:api') -> except('index','show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Restaurant $restaurant)
    {
        return MenuResource::collection($restaurant->menus);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MenuRequest $request, Restaurant $restaurant)
    {
        $this->RestaurantUserCheck($restaurant);
        $menu = new Menu($request->all());
        $restaurant->menus()->save($menu);
         return response([
            'data' => new MenuResource($menu)

        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show(Restaurant $restaurant, Menu $menu)
    {
        $this->MenuIDCheck($restaurant, $menu);
        return new MenuResource($menu);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Restaurant $restaurant, Menu $menu)
    {
        $this->RestaurantUserCheck($restaurant);
        $this->MenuIDCheck($restaurant, $menu);
        $menu->update($request->all());
         return response([
            'data' => new MenuResource($menu)

        ], Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Restaurant $restaurant, Menu $menu)
    {
        $this->RestaurantUserCheck($restaurant);
        $this->MenuIDCheck($restaurant, $menu);
        $menu->delete();
        return response(null,Response::HTTP_NO_CONTENT);
    }

    protected function MenuIDCheck($restaurant, $menu) {
        if ($menu->restaurant_id !== $restaurant->id) {
            throw new NoSuchMenuInCurrentRestaurant;
        }
    }

    protected function RestaurantUserCheck($restaurant) {
        if (Auth::id() !== $restaurant->user_id) {
            throw new RestaurantNotBelongsToUser;
        }
    }
}
