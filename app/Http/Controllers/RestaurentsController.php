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
    //TODO THE NEARBY RESTAURENT

    public   $resID = 0;
    public $orderID = 0;


    public function haversineGreatCircleDistance(
        $lat1,
        $lon1,
        $lat2,
        $lon2,
    ) {
        $pi80 = M_PI / 180;
        $lat1 *= $pi80;
        $lon1 *= $pi80;
        $lat2 *= $pi80;
        $lon2 *= $pi80;
        $r = 6372.797; // mean radius of Earth in km
        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;
        $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $km = $r * $c;
        return $km;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('user_latitude') && $request->has('user_longitude')) {
            $userLat = $request->user_latitude;
            $userLng = $request->user_longitude;
            $restaurents =      Restaurents::all();
            $nearBy = array();
            foreach ($restaurents as $key => $value) {
                $distance = ($this->haversineGreatCircleDistance(floatval($value->rest_latitude), floatval($value->rest_longitude), floatval($request->user_latitude), floatval($userLng)));
                if ($distance <= 150) {
                    $nearBy[] = $value;
                }
            }

            return response()->json([
                'success' => true,
                'data' => $nearBy,
                'message' => 'All nearby restaurents',

            ]);
        } else {
            $restaurents = Restaurents::all();
            return response()->json([
                'success' => true,
                'data' => $restaurents,
                'message' => 'All restaurents',

            ]);
        }
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
                $restaurent = Restaurents::all();

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
                    $order = Order::where('status', 'ready')->whereIn('id', $orderIds)->with('orderDetail', function ($query) {
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

    public function updateDriverLatLng(Request $request)
    {

        $order = Order::find($request->order_id);
        if ($order) {
            $order->driver_lat = $request->driver_lat;
            $order->driver_lng = $request->driver_lng;
            $order->save();
            return response()->json([
                'success' => true,
                'data' => $order,
                'message' => 'Latitude and Longitude Updated ',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Did not find any order',
            ]);
        }
    }
}
