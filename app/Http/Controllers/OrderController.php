<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Orderitems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $order = Order::create([
          
            'total_amount' => $request['total_amount'],
            'payment_method' => $request['payment_method'],
            'payment_id' => $request['payment_id'],
            'customer_id'=>Auth::user()->id,

        ]);

        if($order){
            return response()->json([
                'success'=>true,
                'data' => $order,
                'message' => 'Order created successfully',
            ]);
        }else{
            return response()->json([
                'success'=>false,
                'data' => [],
                'message' => 'Failed, due to some reasons',
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }

    public function addOrderTime(Request $request){
        $order = Order::latest()->first();
        $orderId = $order->id;
       $price = $order->total_amount;

       foreach ($request->data as $key => $value) {
         Orderitems::create([
             'order_id'=>$orderId,
             'price'=>$value['price'],
             'rest_id'=>$value['restaurantId'],
             'product_id'=>$value['id'],
         ]);
       }
    }
}
