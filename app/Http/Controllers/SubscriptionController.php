<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\ReceipeSubscription;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Stripe\Stripe;

class SubscriptionController extends Controller
{

    public $USERID;
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



        Stripe::setApiKey('sk_test_51ISmUBHxiL0NyAbFbzAEkXDMDC2HP0apPILEyaIYaUI8ux0yrBkHMI5ikWZ4teMNsixWP2IPv4yw9bvdqb9rTrhA004tpWU9yl');
        $userData = Auth::user(); //Auth::loginUsingId($request->user_id);
        $customer = null;
        if ($userData) {
            $stripe = new \Stripe\StripeClient(
                'sk_test_51ISmUBHxiL0NyAbFbzAEkXDMDC2HP0apPILEyaIYaUI8ux0yrBkHMI5ikWZ4teMNsixWP2IPv4yw9bvdqb9rTrhA004tpWU9yl'
            );
            $userObject = User::find(Auth::user()->id);
            if ($userObject->stripe_cus_id == null) {
                // NEW USER 
                $customer =  $stripe->customers->create([
                    'payment_method' => $request->payment_method_id,
                    'description' => 'NEW USER FOR SUBSCRIPTION',
                    'email' => $userObject->email,
                    'name' => $userObject->name,
                ]);
                //adding stripe_customer_id to user profile
                $userObject->stripe_cus_id = $customer->id;
                $userObject->save();
            } else
                $customer['id'] = $userObject->stripe_cus_id;


            // //PRICE ID
            // $priceIntent = $stripe->prices->create([
            //     'unit_amount' => floatval($request->price) * 100,
            //     'currency' => 'gbp',
            //     'recurring' => ['interval' => 'month'],
            //     'product' => $request->plan_id,
            // ]);


            // $payment_methods = \Stripe\PaymentMethod::all([
            //     'customer' => $customer['id'],
            //     'type' => 'card'
            // ]);



            $subscription = $stripe->subscriptions->create([
                'customer' => $customer['id'],
                'items' => [
                    [
                        'price' => 'price_1JQcyGHxiL0NyAbFqIY18LLV'
                    ],
                ],
                'default_payment_method' => $request->payment_method_id, //$payment_methods['data'][0]->id, //$
            ]);
            if ($subscription) {
                // return  

                $subs = Subscription::create([
                    'subscription_plan_id' => $subscription['items']['data'][0]['plan']['product'],
                    'subscription_status' => 'active',
                    'user_id' => Auth::user()->id,
                    'payment_intent' => $subscription['id'] //['data'][0]['price']['id'],
                ]);

                return response()->json([
                    'success' => true,
                    'data' => $subs,
                    'subscription_data' => $subscription,
                    'message' => 'Successfully Subscribed',

                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'Could not subscribed',

                ]);
            }
        } else {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Did not found user',

            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function show(Subscription $subscription)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function edit(Subscription $subscription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subscription $subscription)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscription $subscription)
    {
        //
    }

    //getSpecificUserReceipeSubscription
    public function getSpecificUserSubscription()
    {
        $user =   Auth::user();
        $subscriptionData = Subscription::where('user_id', $user->id)->whereNotNull('payment_intent')->orderBy('created_at', 'DESC')->get('payment_intent');

        $stripe = new \Stripe\StripeClient(
            'sk_test_51ISmUBHxiL0NyAbFbzAEkXDMDC2HP0apPILEyaIYaUI8ux0yrBkHMI5ikWZ4teMNsixWP2IPv4yw9bvdqb9rTrhA004tpWU9yl'
        );
        $subs = array();
        //TODO PRODUCT KA DATA
        if ($subscriptionData) {

            foreach ($subscriptionData as $key => $value) {
                $subsc =   $stripe->subscriptions->retrieve(
                    $value->payment_intent,
                    []

                );

                // return $subsc;

                $product =     $stripe->products->retrieve(
                    $subsc['items']['data'][0]['price']['product'],
                    []
                );

                $subs[] = [
                    'sub' => $subsc,
                    'product' => $product,
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $subs,
                'message' => 'User Subscription Detail'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'No Subscription Details Found'
            ]);
        }
    }

    public function getSpecificUserReceipeSubscription()
    {
        $user =   Auth::user();
        $subscriptionData = ReceipeSubscription::where('user_id', $user->id)->whereNotNull('payment_intent')->orderBy('created_at', 'DESC')->get('payment_intent');
        $stripe = new \Stripe\StripeClient(
            'sk_test_51ISmUBHxiL0NyAbFbzAEkXDMDC2HP0apPILEyaIYaUI8ux0yrBkHMI5ikWZ4teMNsixWP2IPv4yw9bvdqb9rTrhA004tpWU9yl'
        );
        $subscriptionEnd = 0;

        $subs = array();
        if ($subscriptionData) {
            foreach ($subscriptionData as $key => $value) {
                $subsc =   $stripe->subscriptions->retrieve(
                    $value->payment_intent,
                    []
                );
                $product =     $stripe->products->retrieve(
                    $subsc['items']['data'][0]['price']['product'],
                    []
                );
                if ($product['metadata']['is_receipe'] == 'true') {
                    $subscriptionEnd = $subsc->current_period_end;
                    $subs[] = [
                        'sub' => $subsc,
                        'product' => $product,
                    ];
                }
            }
            $membershipPurchaseDate = null;
            $firstWeek = null;
            $secondWeek = null;
            $thirdWeek = null;
            $fourthWeek = null;
            $alreadySubscribed = false;
            $isFreeMealAlreadyTaken = false;
            $isFirstWeek = false;
            $isSecondWeek = false;
            $isThirdWeek = false;
            $isFourthWeek = false;

            $slotsLeft = 0;

            $receipeSubscriptions = ReceipeSubscription::where('user_id', $user->id)->latest()->first();
            if (($receipeSubscriptions)) {
                $membershipPurchaseDate = $receipeSubscriptions->created_at;
                $membershipPurchaseDate = Carbon::parse($membershipPurchaseDate);
                $firstWeek = $membershipPurchaseDate->addWeek(1);
                $secondWeek = $membershipPurchaseDate->addWeek(2);
                $thirdWeek = $membershipPurchaseDate->addWeek(3);
                $fourthWeek = $membershipPurchaseDate->addWeek(4);
            }


            $order =  Order::where('customer_id', $user->id)->where('is_receipe', 1)->latest()->first();
            // return $order;
            if ($order) {
                $orderDetails = OrderDetails::where('order_id', $order->id)->get();
                if (count($orderDetails)>0) {
                    if ($orderDetails[0]->created_at <= $firstWeek) {
                        $isFirstWeek = true;
                        $slotsLeft = 3;
                    } else if ($orderDetails[0]->created_at > $firstWeek && $orderDetails[0]->created_at <= $secondWeek) {
                        $isSecondWeek = true;
                        $slotsLeft = 2;
                    } else if ($orderDetails[0]->created_at > $secondWeek && $orderDetails[0]->created_at <= $thirdWeek) {
                        $isThirdWeek = true;
                        $slotsLeft = 1;
                    } else if ($orderDetails[0]->created_at > $thirdWeek && $orderDetails[0]->created_at <= $fourthWeek) {
                        $isFourthWeek = true;
                        $slotsLeft = 0;
                    } else {
                    }
                }
            }

            return response()->json([
                'success' => true,
                'data' => $subs,
                'slots_left' => $slotsLeft,
                'subscription_end' => date("d/m/Y H:i:s", $subscriptionEnd),
                'message' => 'User Subscription Detail'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'No Subscription Details Found'
            ]);
        }
    }



    public function checkoutPage($id, $plan_id)
    {

        $this->USERID = $id;
        $stripe = new \Stripe\StripeClient(
            env('STRIPE_TEST_SECRET_KEY')
        );
        $subscriptionPlan =  $stripe->products->retrieve(
            $plan_id,
            []
        );

        return view('checkout')->with(['id' => $id, 'plan' => $subscriptionPlan]);
    }

    public function cancelSubscription($subscriptionId)
    {
        $stripe = new \Stripe\StripeClient(
            env('STRIPE_TEST_SECRET_KEY')
        );
        $stripe->subscriptions->cancel(
            $subscriptionId,
            []
        );

        $sub =    Subscription::where('payment_intent', $subscriptionId)->get()->first();
        if ($sub) {
            $sub->subscription_status = 'canceled';
            $sub->save();
        }

        if ($stripe) {
            return response()->json([
                'success' => true,
                'message' => 'Subscription Cancelled Successfully',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Some Error Occured, Please Try Again Later',
            ]);
        }
    }

    public function checkAlreadySubscribed(Request $request)
    {
        $allUserSubs = Subscription::where('user_id', $request->user_id)->where('subscription_plan_id', $request->plan_id)->where('subscription_status', 'active')->get();

        if (count($allUserSubs) > 0) {
            return response()->json([
                'success' => false,
                'message' => 'You have already subscribed for this subscription'
            ]);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'You can applied for this subscription'
            ]);
        }
    }

    public function checkAllMealSubscription()
    {
        // $user = Auth::user();
        // $membershipPurchaseDate = null;
        // $nextMonthDate = null;
        // $alreadySubscribed = false;
        // $isFreeMealAlreadyTaken = false;
        // $allUserSubs = Subscription::where('user_id', $user->id)->where('subscription_plan_id', 'prod_K4mX6kbfk8c9Vm')->where('subscription_status', 'active')->latest()->first();
        // if ($allUserSubs) {
        //     $alreadySubscribed = true;
        //     $membershipPurchaseDate = $allUserSubs->created_at;
        //     $membershipPurchaseDate = Carbon::parse($membershipPurchaseDate);
        //     $nextMonthDate = $membershipPurchaseDate->addMonth(1);
        // }

        // // if ($alreadySubscribed) {
        // $mytime = Carbon::now()->subDays(7);
        // $order =  Order::where('customer_id', $user->id)->where('is_receipe', 0)->latest()->first();
        // if ($order) {
        //     $orderDetails = OrderDetails::where('order_id', $order->id)->get();
        //     if ($orderDetails[0]->created_at > $mytime) {
        //         foreach ($orderDetails as $key => $value) {
        //             $menuItem = Menu::find($value->rest_menuId);
        //             if ($menuItem->is_free == 1) {
        //                 $isFreeMealAlreadyTaken = true;
        //             }
        //         }
        //     }
        // }
        // // }
        // return response()->json([
        //     'success' => true,
        //     'already_subscribed' => $alreadySubscribed,
        //     'free_meal_taken' => $isFreeMealAlreadyTaken,
        // ]);

        $user = Auth::user();
        $membershipPurchaseDate = null;
        $firstWeek = null;
        $secondWeek = null;
        $thirdWeek = null;
        $fourthWeek = null;
        $alreadySubscribed = false;
        $isFreeMealAlreadyTaken = false;
        $allUserSubs = Subscription::where('user_id', $user->id)->where('subscription_plan_id', 'prod_K4mX6kbfk8c9Vm')->where('subscription_status', 'active')->latest()->first();
        if ($allUserSubs) {
            $alreadySubscribed = true;
            $membershipPurchaseDate = $allUserSubs->created_at;
            $membershipPurchaseDate = Carbon::parse($membershipPurchaseDate);
            $firstWeek = $membershipPurchaseDate->addWeek(1);
            $secondWeek = $membershipPurchaseDate->addWeek(2);
            $thirdWeek = $membershipPurchaseDate->addWeek(3);
            $fourthWeek = $membershipPurchaseDate->addWeek(4);
        }


        $order =  Order::where('customer_id', $user->id)->where('is_receipe', 0)->latest()->first();
        if ($order) {
            $orderDetails = OrderDetails::where('order_id', $order->id)->get();
            if ($orderDetails[0]->created_at <= $firstWeek) {
                foreach ($orderDetails as $key => $value) {
                    $menuItem = Menu::find($value->rest_menuId);
                    if ($menuItem->is_free == 1) {
                        $isFreeMealAlreadyTaken = true;
                    }
                }
            } else if ($orderDetails[0]->created_at > $firstWeek && $orderDetails[0]->created_at <= $secondWeek) {
                foreach ($orderDetails as $key => $value) {
                    $menuItem = Menu::find($value->rest_menuId);
                    if ($menuItem->is_free == 1) {
                        $isFreeMealAlreadyTaken = true;
                    }
                }
            } else if ($orderDetails[0]->created_at > $secondWeek && $orderDetails[0]->created_at <= $thirdWeek) {
                foreach ($orderDetails as $key => $value) {
                    $menuItem = Menu::find($value->rest_menuId);
                    if ($menuItem->is_free == 1) {
                        $isFreeMealAlreadyTaken = true;
                    }
                }
            } else if ($orderDetails[0]->created_at > $thirdWeek && $orderDetails[0]->created_at <= $fourthWeek) {
                foreach ($orderDetails as $key => $value) {
                    $menuItem = Menu::find($value->rest_menuId);
                    if ($menuItem->is_free == 1) {
                        $isFreeMealAlreadyTaken = true;
                    }
                }
            } else {
                $isFreeMealAlreadyTaken = false;
            }
        }

        return response()->json([
            'success' => true,
            'already_subscribed' => $alreadySubscribed,
            'free_meal_taken' => $isFreeMealAlreadyTaken,
        ]);
    }
}
