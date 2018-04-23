<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Model\Order;
use App\Model\Customer;
use App\Http\Resources\OrderResource;
use App\Exceptions\UnauthorizedCustomer;
use App\Http\Requests\OrderRequest;
use Auth;

class OrderController extends Controller
{
    public function __construct(){
        $this -> middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Customer $account, Order $order)
    {
        $this->CustomerUserCheck($account);
        return ItemResource::collection($order->items);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderRequest $request, Customer $account)
    {
        $this->CustomerUserCheck($account);
        $order = new Order($request->all());
        $account->orders()->save($order);
        return response([
            'data' => new OrderResource($order)

        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $account, Order $order)
    {
        $this->CustomerUserCheck($account);
        $this->CustomerIdCheck($account, $order);
        return new OrderResource($order);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $account, Order $order)
    {
        $this->CustomerUserCheck($account);
        $this->CustomerIdCheck($account, $order);
        $order->update($request->all());
        return response([
            'data' => new OrderResource($order)
        ], Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Customer $account, Order $order)
    {
        $this->CustomerUserCheck($account);
        $this->CustomerIdCheck($account, $order);
        $order->delete();
        return response(null,Response::HTTP_NO_CONTENT);
    }

    public function CustomerIdCheck($customer, $order) {
        if ($order->customer_id !== $customer->id) {
            throw new UnauthorizedCustomer;
        }
    }

    public function CustomerUserCheck($customer) {
        if (Auth::id() !== $customer->user_id) {
            throw new UnauthorizedCustomer;
        }
    }
}

