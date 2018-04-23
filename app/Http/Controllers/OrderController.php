<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Model\Order;
use App\Model\ItemOrder;
use App\Model\Customer;
use App\Model\Restaurant;
use App\Model\Item;
use App\Model\Option;
use App\Model\MulticheckOption;
use App\Http\Resources\ItemOrderResource;
use App\Http\Resources\OrderResource;
use App\Exceptions\MutipleRestaurantOrder;
use App\Exceptions\InvalidOrderItem;
use App\Exceptions\UnauthorizedCustomer;
use App\Http\Requests\ItemOrderRequest;
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
    public function index(Customer $account)
    {
        $this->CustomerUserCheck($account);
        return OrderResource::collection($account->orders);
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

    public function indexItems(Customer $account, Order $order)
    {
        $this->CustomerUserCheck($account);
        $this->CustomerIdCheck($account, $order);
        return ItemOrderResource::collection($order->items);
    }

    public function showItem(Customer $account, Order $order, ItemOrder $item) {
        $this->CustomerUserCheck($account);
        $this->CustomerIdCheck($account, $order);
        $this->ItemIdCheck($order, $item);
        return new ItemOrderResource($item);
    }

    public function storeItem(ItemOrderRequest $request, Customer $account, 
        Order $order, Restaurant $restaurant, 
        Item $item, Option $option, MulticheckOption $multicheckoption) {
        $this->CustomerUserCheck($account);
        $this->CustomerIdCheck($account, $order);
        if ($order->restaurant_id > 0) {
            if ($restaurant->id !== $order->restaurant_id) {
                throw new MutipleRestaurantOrder;
            }
        } else {
            $order->restaurant_id = $restaurant->id;
        }
        $this->ItemCheck($item, $option, $multicheckoption);
        $itemorder = new ItemOrder;
        $itemorder->item_id = $item->id;
        $itemorder->option_id = $option->id;
        $itemorder->option_quantity = $request->quantity;
        $itemorder->multicheckoption_id = $multicheckoption->id;
        $itemorder->multicheckoption_checked = $request->box1checked;
        $order->items()->save($itemorder);
        return response([
            'data' => new ItemOrderResource($itemorder)
        ], Response::HTTP_CREATED);
    }

    public function updateItem(ItemOrderRequest $request, Customer $account, 
        Order $order, ItemOrder $item) {
        $this->CustomerUserCheck($account);
        $this->CustomerIdCheck($account, $order);
        $item->option_quantity = $request->quantity;
        $item->multicheckoption_checked = $request->box1checked;
        $item->save();
        return response([
            'data' => new ItemOrderResource($item)
        ], Response::HTTP_CREATED);
    }

    public function destroyItem(Customer $account, 
        Order $order, ItemOrder $item) {
        $this->CustomerUserCheck($account);
        $this->CustomerIdCheck($account, $order);
        $item->delete();
        return response(null,Response::HTTP_NO_CONTENT);
    }

    public function ItemCheck($item, $option, $multicheckoption) {
        if ($option->item_id !== $item->id or
            $multicheckoption->option_id !== $option->id) {
            throw new InvalidOrderItem;
        }
    }

    public function ItemIdCheck($order, $item) {
        if ($item->order_id !== $order->id) {
            throw new InvalidOrderItem;
        }
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
