<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\ratings;
use App\Models\Menu;
use App\Models\MenuType;

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
        $lon2
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
                    $order = Order::whereIn('status', ['ready', 'onway', 'delivered'])->whereIn('id', $orderIds)->with('orderDetail', function ($query) {
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

    public function filters(Request $request)
    {

        // SORTING
        // => DISTANCE
        // => RATING
        // => RECOMMENDED ( ISOPEN, NEARBY, RATING)
        // => TOP RATED

        $sortType = null;
        $filterData = array();


        if ($request->has('sort')) {
            $sortType = $request['sort'];
            if ($sortType == 'distance' && $request->has('lat') && $request->has('lng')) {
                $allRestaurants = Restaurents::all();
                foreach ($allRestaurants as $key => $value) {
                    $km =  $this->haversineGreatCircleDistance(floatval($value->rest_latitude), floatval($value->rest_longitude), $request->lat, $request->lng);

                    if ($km <= 5) {

                        $filterData[] = $value;
                    }
                }
            } else {
                return response()->json([
                    'success' => false,
                    'data' => [],
                    'message' => 'Lat Lngs are required for distance sorting'
                ]);
            }


            //IF SORT IS RECOMMENDED
            if ($sortType == 'recommended') {

                $recommendedRestaurants = Restaurents::where('rest_isOpen', '1')->orWhere('rest_isTrending', '1')->get();
                foreach ($recommendedRestaurants as $key => $value) {
                    $ratings = ratings::where('rest_id', $value->id)->get();
                    if (count($ratings) > 0) {
                        if ($ratings->avg('rating') >= 3) {
                            $filterData[] = $value;
                        }
                    } else {
                        $filterData[] = $recommendedRestaurants;
                    }
                }
            }
            // IF SORT IS RATINGS
            else if ($sortType == 'ratings') {

                $restaurentRatings = ratings::orderBy('id', 'DESC')->get();
                if ($restaurentRatings) {
                    foreach ($restaurentRatings as $key => $value) {
                        $restaurent = Restaurents::where('id', $value->rest_id)->get();
                    }
                    $filterData = $restaurent;
                }
            } else if ($sortType = 'topRated') {
                $topRatedRestaurants = Restaurents::where('rest_isTrending', '1')->get();
                if (count($topRatedRestaurants) > 0) {
                    $filterData = $topRatedRestaurants;
                }
            }
        }

        // if rating list is sent

        if ($request->has('rating_list')) {

            $ratingList = $request->rating_list;
            $restaurentsRating = ratings::all();
            $allRestaurants = Restaurents::all();


            // agr sorting param available ho tu
            if (!empty($filterData)) {
                foreach ($filterData as $key => $value) {
                    foreach ($restaurentsRating as $key1 => $secondValue) {
                        if ($secondValue->rest_id == $value->id) {

                            $ratingRes = ratings::where('rest_id', $value->id)->get()->avg('rating');
                            if (!in_array($ratingRes, $ratingList))
                                unset($filterData[$key]);
                        }
                    }
                }
            } else {

                foreach ($allRestaurants as $key => $value) {
                    $currentRating = ratings::where('rest_id', $value->id)->get()->avg('rating');
                    if (in_array($currentRating, $ratingList)) {
                        $filterData[] = $value;
                    }
                }
            }
        }

        if ($request->has('categories_list')) {

            $categoriesList = $request->categories_list;
            $resID = array();
            $menusList = Menu::all();
            if (empty($filterData)) {
                foreach ($categoriesList as $key => $value) {
                    $menuType =   MenuType::where('menu_name', $value)->first();
                    if ($menuType) {
                        foreach ($menusList as $key => $menuData) {
                            if ($menuData->menu_type_id == $menuType->id)
                                $resID[] = $menuData->rest_id;
                        }
                    }
                }
                $resID = array_unique($resID);
                foreach ($resID as $key => $value) {
                    $filterData[] = Restaurents::find($value);
                }
            } else {
                foreach ($categoriesList as $key => $value) {

                    $menuType =   MenuType::where('menu_name', $value)->first();

                    if (($menuType)) {
                        foreach ($menusList as $key => $menuData) {
                            if ($menuData->menu_type_id == $menuType->id)
                                $resID[] = $menuData->rest_id;
                        }
                    } else break;
                }
                if (count($resID) > 0) {
                    $resID = array_unique($resID);
                    foreach ($filterData as $key => $value) {
                        if (!($value->id == $resID[$key])) {
                            unset($filterData[$key]);
                        }
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'data' => $filterData,
            'message' => 'Filters Data'
        ]);
    }


    public function getItemsServiceType(Request $request)
    {
        $itemType =  $request['item_type'];
        $filteredItems = Menu::where($itemType,1)->with('restaurant')->get();
        return response()->json([
            'success'=>true,
            'data'=>$filteredItems,
            'message'=>'Restaurant Type Data',
        ]);
    }
}
