<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $userData = Auth::loginUsingId($request->user_id);
        $customer = null;
        if ($userData) {
            $stripe = new \Stripe\StripeClient(
                'sk_test_51ISmUBHxiL0NyAbFbzAEkXDMDC2HP0apPILEyaIYaUI8ux0yrBkHMI5ikWZ4teMNsixWP2IPv4yw9bvdqb9rTrhA004tpWU9yl'
            );
            $userObject = User::find(Auth::user()->id);
            if ($userObject->stripe_cus_id == null) {
                // NEW USER 
                $customer =  $stripe->customers->create([
                    'payment_method' => $request->paymentMethodId,
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


            $payment_methods = \Stripe\PaymentMethod::all([
                'customer' => $customer['id'],
                'type' => 'card'
            ]);


            $subscription = $stripe->subscriptions->create([
                'customer' => $customer['id'],
                'items' => [
                    [
                        'price' => 'price_1JQcyGHxiL0NyAbFqIY18LLV'
                    ],
                ],
                'default_payment_method' => $payment_methods->data[0]->id,
            ]);
            $subs = Subscription::create([
                'subscription_plan_id' => $request->plan_id,
                'subscription_status' => 'active',
                'user_id' => Auth::user()->id,
                'payment_intent' => $subscription->id
            ]);

            return response()->json([
                'success' => true,
                'data' => $subs,
                'message' => 'Successfully Subscribed',

            ]);
        } else {
            return 'kkk';
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

    public function checkAllMealSubscription(Request $request)
    {
        $alreadySubscribed = false;
        $isFreeMealAlreadyTaken = false;
        $allUserSubs = Subscription::where('user_id', $request->user_id)->where('subscription_plan_id', 'prod_K4mX6kbfk8c9Vm')->where('subscription_status', 'active')->get();
        if ($allUserSubs) {
            $alreadySubscribed = true;
        }
        $mytime = Carbon::now()->subDays(7);
        $order =  Order::where('customer_id', $request->user_id)->latest()->first();
        if ($order) {
            $orderDetails = OrderDetails::where('order_id', $order->id)->get();
            if ($orderDetails[0]->created_at > $mytime) {
                foreach ($orderDetails as $key => $value) {
                    $menuItem = Menu::find($value->rest_menuId);
                    if ($menuItem->is_free == 1) {
                        $isFreeMealAlreadyTaken = true;
                    }
                }
            }
        }
        return response()->json([
            'success' => true,
            'already_subscribed' => $alreadySubscribed,
            'free_meal_taken' => $isFreeMealAlreadyTaken,
        ]);
    }
}
