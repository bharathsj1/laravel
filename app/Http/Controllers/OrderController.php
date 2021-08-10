<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Orderitems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
            'customer_id' => $request['user_id'],
            'customer_addressId' => $request['customer_addressId'],
            'is_receipe' => $request['is_receipe'],

            'method_id' => $request['method_id'],
            
            'is_subscribed_user'=>$request['is_subscribed_user'],



        ]);


        if ($order) {
            return response()->json([
                'success' => true,
                'data' => $order,
                'message' => 'Order created successfully',
            ]);
        } else {
            return response()->json([
                'success' => false,
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

    public function addOrderItem(Request $request)
    {
        $order = Order::latest()->first();
        $orderId = $order->id;
        $price = $order->total_amount;

        foreach ($request->data as $key => $value) {
            Orderitems::create([
                'order_id' => $orderId,
                'price' => $value['price'],
                'rest_id' => $value['restaurantId'],
                'product_id' => $value['id'],

            ]);
            $menuItem = Menu::find($value['id']);
            $menuItem->order_count = $menuItem->order_count + 1;
            $menuItem->save();
        }
    }

    public function addOrderDetails(Request $request)
    {
        $validatedData   =  Validator::make($request->all(), [
            // 'quantity' => 'required',
            'total_price' => 'required',
            'order_id' => 'required',
            // 'rest_menuId' => 'required'
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validatedData->errors()->first(),
            ]);
        }

        if ($request->has('receipe_id')) {

            $order =   OrderDetails::create([
                'quantity' => $request['quantity'],
                'total_price' => $request['total_price'],
                'order_id' => $request['order_id'],
                'receipe_id' => $request['receipe_id'],
                'person_quantity' => $request['person_quantity']

            ]);
        } else {

            $order =   OrderDetails::create([
                'quantity' => $request['quantity'],
                'total_price' => $request['total_price'],
                'order_id' => $request['order_id'],
                'rest_menuId' => $request['rest_menuId'],
                'rest_id'=>$request['rest_Id']

            ]);
        }




        if ($order) {
            response()->json([
                'success' => true,
                'data' => $order,
                'message' => 'Details added',
            ]);
        } else {
            response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Error',
            ]);
        }
    }

    public function getUserOrders()
    {
        $user = Auth::user();
        if ($user) {
            $order = Order::where('customer_id', $user->id)->with(['orderDetail', 'customerAddress', 'receipe'])->orderBy('id', 'DESC')->get();
            return response()->json([
                'success' => true,
                'data' => $order,
                'message' => 'Your Orders List'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'You are not authorized',
            ]);
        }
    }

    public function getAllOrders()
    {

        $order = Order::with(['orderDetail', 'user_address', 'receipe'])->orderBy('id', 'desc')->get();

        if ($order) {
            return response()->json([
                'success' => true,
                'data' => $order,
                'message' => 'All Orders'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'No Orders'
            ]);
        }
    }

    public function changeSuperAdmin(Request $request)
    {
        $id = $request->order_id;
        $order = Order::find($id);

        if ($order) {
            $order->super_admin = $request->super_admin_status;
            $order->save();
            return response()->json([
                'success' => true,
                'data' => $order,
                'message' => 'Super Admin Status Changed Successfully',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Did not found any order id',
            ]);
        }
    }

    public function changeOrderStatus(Request $request)
    {
        $id = $request->order_id;
        $order = Order::find($id);

        if ($order) {
            $order->status = $request->status;
            $order->save();
            return response()->json([
                'success' => true,
                'data' => $order,
                'message' => 'Status Changed Successfully',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Did not found any order ',
            ]);
        }
    }

    public function getSpecificOrder($id)
    {
        $order = Order::where('id', $id)->with(['orderDetail', 'user_address'])->first();
        if ($order) {
            return response()->json([
                'success' => true,
                'data' => $order,
                'message' => 'Order details',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Did not found any order ',
            ]);
        }
    }


}
