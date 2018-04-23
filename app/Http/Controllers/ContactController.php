<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use App\Model\Contact;
use App\Model\Restaurant;
use Illuminate\Http\Request;
use App\Http\Resources\ContactResource;
use App\Exceptions\RestaurantNotBelongsToUser;
use App\Exceptions\ContactExists;
use App\Http\Requests\ContactRequest;
use Auth;

class ContactController extends Controller
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
        return ContactResource::collection($restaurant->contacts);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Restaurant $restaurant)
    {
        $this->RestaurantUserCheck($restaurant);
        $contact = new Contact($request->all());
        if (count($restaurant->contacts) > 0) {
            $restaurant->contacts()->update($request->all());
            return response([
                'data' => new ContactResource($contact)
            ], Response::HTTP_CREATED);
        }
        $restaurant->contacts()->save($contact);
         return response([
            'data' => new ContactResource($contact)
        ], Response::HTTP_CREATED);
    }   

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contact $contact)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Restaurant $restaurant, Contact $contact)
    {
        //
    }

    protected function RestaurantUserCheck($restaurant) {
        if (Auth::id() !== $restaurant->user_id) {
            throw new RestaurantNotBelongsToUser;
        }
    }
}
