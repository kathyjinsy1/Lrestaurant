<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\RestaurantRequest;
use App\Http\Resources\Restaurant\RestaurantCollection;
use App\Http\Resources\Restaurant\RestaurantResource;
use App\Model\Restaurant;
use App\Exceptions\RestaurantNotBelongsToUser;
use Illuminate\Http\Request;
use Auth;

class RestaurantController extends Controller
{

    public function __construct(){
        $this -> middleware('auth:api') -> except('index','show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return RestaurantCollection::collection(Restaurant::paginate(15));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RestaurantRequest $request)
    {
        $restaurant = new Restaurant;
        $restaurant->name = $request->name;
        $restaurant->description = $request->description;
        $restaurant->user_id = Auth::id();
        $restaurant->save();
        return response([
            'data' => new RestaurantResource($restaurant)

        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function show(Restaurant $restaurant)
    {
        return new RestaurantResource($restaurant);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Restaurant $restaurant)
    {
        $this->RestaurantUserCheck($restaurant);
        $restaurant->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Restaurant $restaurant)
    {
        $this->RestaurantUserCheck($restaurant);
        $restaurant->delete();
        return response(null,Response::HTTP_NO_CONTENT);
    }

    public function RestaurantUserCheck($restaurant) {
        if (Auth::id() !== $restaurant->user_id) {
            throw new RestaurantNotBelongsToUser;
        }
    }
}
