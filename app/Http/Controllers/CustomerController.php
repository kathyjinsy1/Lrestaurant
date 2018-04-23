<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use App\Model\Customer;
use Illuminate\Http\Request;
use App\Http\Resources\CustomerResource;
use App\Exceptions\UnauthorizedCustomer;
use App\Http\Requests\CustomerRequest;
use Auth;

class CustomerController extends Controller
{

    public function __construct(){
        $this -> middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerRequest $request)
    {
        $customer = new Customer;
        $customer->name = $request->name;
        $customer->address = $request->address;
        $customer->cellphone = $request->cellphone;
        $customer->email = $request->email;
        $customer->payment = $request->payment;
        $customer->user_id = Auth::id();
        $customer->save();
        return response([
            'data' => new CustomerResource($customer)
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $account)
    {
        $this->CustomerUserCheck($account);
        return new CustomerResource($account);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $account)
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

    public function CustomerUserCheck($customer) {
        if (Auth::id() !== $customer->user_id) {
            throw new UnauthorizedCustomer;
        }
    }
}

