<?php

namespace App\Http\Controllers;

use App\Models\ReceipeSubscription;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;

class ReceipeSubscriptionController extends Controller
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



            $payment_methods = \Stripe\PaymentMethod::all([
                'customer' => $customer['id'],
                'type' => 'card'
            ]);


            $subscription = $stripe->subscriptions->create([
                'customer' => $customer['id'],
                'items' => [
                    [
                        'price' =>$request->price_id,
                    ],
                ],
                'default_payment_method' => $payment_methods->data[0]->id,
            ]);
            $subs = ReceipeSubscription::create([
                'subscription_plan_id' => $request->plan_id,
                'total_receipes'=>$request->total_receipes,
               'user_id' => Auth::user()->id,
                'payment_intent' => $subscription->id,
                'subscription_start_date'=>Carbon::now(),
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
     * @param  \App\Models\ReceipeSubscription  $receipeSubscription
     * @return \Illuminate\Http\Response
     */
    public function show(ReceipeSubscription $receipeSubscription)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ReceipeSubscription  $receipeSubscription
     * @return \Illuminate\Http\Response
     */
    public function edit(ReceipeSubscription $receipeSubscription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ReceipeSubscription  $receipeSubscription
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReceipeSubscription $receipeSubscription)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ReceipeSubscription  $receipeSubscription
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReceipeSubscription $receipeSubscription)
    {
        //
    }
}
