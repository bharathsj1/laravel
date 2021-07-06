<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Restaurents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RestaurentsController extends Controller
{
    public   $resID = 0;

    public $orderID = 0;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $restaurents = Restaurents::all();
        return response()->json([
            'success' => true,
            'data' => $restaurents,
            'message' => 'All restaurents',

        ]);
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
        $upload_path = 'uploadedImages/restaurents/';

        $validatedData   =  Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'close_time' => 'required|string|min:8',
            'phone_number' => 'required|string|max:15|unique:users',
            'country' => 'required|string',
            'open' => 'required',
            'open_time' => 'required',
            'phone' => 'required',
            'type' => 'required',
            'zipcode' => 'required'
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validatedData->errors()->first(),
            ]);
        }


        $file_name = $request->image->getClientOriginalName();
        $generated_new_name = time() . '.' . $file_name;
        $request->photo->move($upload_path, $generated_new_name);
        $request['image'] = $upload_path . $generated_new_name;
        Restaurents::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Restaurent Saved Successfully',

        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Restaurents  $restaurents
     * @return \Illuminate\Http\Response
     */
    public function show(Restaurents $restaurents)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Restaurents  $restaurents
     * @return \Illuminate\Http\Response
     */
    public function edit(Restaurents $restaurents)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Restaurents  $restaurents
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Restaurents $restaurents)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Restaurents  $restaurents
     * @return \Illuminate\Http\Response
     */
    public function destroy(Restaurents $restaurents)
    {
        //
    }

    public function getOrdersForSpecificRes()
    {

        $user = Auth::user();
        if ($user) {
            if ($user->cust_account_type == '4') {
                $restaurent = Restaurents::where('user_id', $user->id)->get();
                $orderDetails = array();
                foreach ($restaurent as $key => $value) {
                    $this->resID = $value->id;
                    $this->orderID = OrderDetails::where('rest_id', $this->resID)->get()->pluck('order_id');
                    // return $this->orderID;
                    // $order = OrderDetails::where('rest_id', $value->id)->with('order', function ($query) {
                    //     $query->where('super_admin', 'approved')->get();
                    // })->get();
                    // if (count($order)>0) {
                    //     $orderDetails = $order;
                    // } 

                    $orderIds = OrderDetails::where('rest_id', $value->id)->get()->pluck('order_id');
                    $order = Order::where('super_admin', 'approved')->whereIn('id', $orderIds)->with('orderDetail', function ($query) {
                        $query->where('rest_id', $this->resID)->get();
                    })->with('user_address')->get();

                    if (count($order) > 0) {
                        $orderDetails = $order;
                    }
                }
                return response()->json([
                    'success' => true,
                    'data' => $orderDetails,
                    'message' => 'Order List',
                ]);
            } else if ($user->cust_account_type == '3') {
                $restaurent = Restaurents::where('user_id', $user->id)->get();

                $orderDetails = array();
                // foreach ($restaurent as $key => $value) {
                //     $orderDetails = OrderDetails::where('rest_id', $value->id)->with('order', function ($query) {
                //         $query->where('super_admin', 'ready')->get();
                //     })->get();
                // }

                foreach ($restaurent as $key => $value) {
                    $this->resID = $value->id;
                    $this->orderID = OrderDetails::where('rest_id', $this->resID)->get()->pluck('order_id');
                    // return $this->orderID;
                    // $order = OrderDetails::where('rest_id', $value->id)->with('order', function ($query) {
                    //     $query->where('super_admin', 'ready')->get();
                    // })->get();
                    // if (count($order)>0) {
                    //     $orderDetails = $order;
                    // } 

                    $orderIds = OrderDetails::where('rest_id', $value->id)->get()->pluck('order_id');
                    $order = Order::where('super_admin', 'ready')->whereIn('id', $orderIds)->with('orderDetail', function ($query) {
                        $query->where('rest_id', $this->resID)->get();
                    })->with('user_address')->get();

                    if (count($order) > 0) {
                        $orderDetails = $order;
                    }
                }
                return response()->json([
                    'success' => true,
                    'data' => $orderDetails,
                    'message' => 'Order List',
                ]);
            }
        } else {

            return response()->json([
                'success' => false,
                'data' => [],
                'message', 'Please send access token',
            ]);
        }
    }
}
