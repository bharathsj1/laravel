<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
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
        $stripe = new \Stripe\StripeClient(
            'sk_test_51ISmUBHxiL0NyAbFbzAEkXDMDC2HP0apPILEyaIYaUI8ux0yrBkHMI5ikWZ4teMNsixWP2IPv4yw9bvdqb9rTrhA004tpWU9yl'
        );

        $customer =  $stripe->customers->create([
            'payment_method' => $request->paymentMethodId,
            'description' => 'NEW USER  SUBSCRIPTION',
        ]);
     
        Stripe::setApiKey('sk_test_51ISmUBHxiL0NyAbFbzAEkXDMDC2HP0apPILEyaIYaUI8ux0yrBkHMI5ikWZ4teMNsixWP2IPv4yw9bvdqb9rTrhA004tpWU9yl');
        $userData = Auth::loginUsingId($request->user_id);
        $customer = null;
        if ($userData) {
            $stripe = new \Stripe\StripeClient(
                'sk_test_51ISmUBHxiL0NyAbFbzAEkXDMDC2HP0apPILEyaIYaUI8ux0yrBkHMI5ikWZ4teMNsixWP2IPv4yw9bvdqb9rTrhA004tpWU9yl'
            );
            $userObject = User::find($request->user_id);
            if ($userObject->stripe_cus_id == null) {
                // NEW USER 
                $customer =  $stripe->customers->create([
                    'payment_method' => $request->paymentMethodId,
                    'description' => 'NEW USER FOR SUBSCRIPTION',
                ]);
                //adding stripe_customer_id to user profile
                $userObject->stripe_cus_id = $customer->id;
                $userObject->save();
            } else
                $customer['id'] = $userObject->stripe_cus_id;


            // //PRICE ID
            $priceIntent = $stripe->prices->create([
                'unit_amount' => floatval($request->price),
                'currency' => 'gbp',
                'recurring' => ['interval' => 'month'],
                'product' => 'prod_Jrb4bhZcdDxhaP',
            ]);

            $payment_methods = \Stripe\PaymentMethod::all([
                'customer' => $customer['id'],
                'type' => 'card'
            ]);


            $subscription =        $stripe->subscriptions->create([
                'customer' => $customer['id'],
                'items' => [
                    [
                        'price' => $priceIntent->id,
                    ],
                ],
                'default_payment_method' => $payment_methods->data[0]->id,
            ]);

        } else {
            return 'kkk';
        }




        // $subscription = Subscription::create([
        //     'subscription_plan_id' => $request->subscription_plan_id,
        //     'subscription_status' => $request->subscription_status,
        //     'subscription_start_date' => $request->subscription_start_date,
        //     'subscription_end_date' => $request->subscription_end_date,
        //     'user_id' => Auth::user()->id,
        // ]);

        // return response()->json([
        //     'status' => 200,
        //     'data' => $subscription,
        //     'message' => 'Successfully Subscribed',

        // ]);
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
        $subscriptionData = Subscription::where('user_id', $user->id)->with('subscription_plan')->get();
        if ($subscriptionData) {
            return response()->json([
                'success' => true,
                'data' => $subscriptionData,
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

    public function cancelSubscription($id)
    {
        $user = Auth::user();
        $subscription = Subscription::find($id);
        if ($subscription) {
            if ($subscription->subscription_status == 'canceled') {
                return response()->json([
                    'success' => false,
                    'data' => [],
                    'message' => 'Subscription already canceled'
                ]);
            }
            $subscription->subscription_status = 'canceled';
            $subscription->save();
            return response()->json([
                'success' => true,
                'data' => $subscription,
                'message' => 'Subscription Canceled Successfully',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Not Found'
            ]);
        }
    }

    public function checkoutPage($id, $plan_id)
    {
        $this->USERID = $id;
        $subscriptionPlan = SubscriptionPlan::find($plan_id);
        if ($subscriptionPlan)
            return view('checkout')->with(['id' => $id, 'plan' => $subscriptionPlan]);
        else
            return 'Error: Subscription Plan Not Found';
    }
}
