<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
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
        $subscription = Subscription::create([
            'subscription_plan_id' => $request->subscription_plan_id,
            'subscription_status' => $request->subscription_status,
            'subscription_start_date' => $request->subscription_start_date,
            'subscription_end_date' => $request->subscription_end_date,
            'user_id' => Auth::user()->id,
        ]);

        return response()->json([
            'status' => 200,
            'data' => $subscription,
            'message' => 'Successfully Subscribed',

        ]);
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
        if($subscription)
        {
            if($subscription->subscription_status=='canceled')
            {
                return response()->json([
                    'success'=>false,
                    'data'=>[],
                    'message'=>'Subscription already canceled'
                ]);
            }
            $subscription->subscription_status='canceled';
            $subscription->save();
            return response()->json([
                'success'=>true,
                'data'=>$subscription,
                'message'=>'Subscription Canceled Successfully',
            ]);
        }else{
            return response()->json([
                'success'=>false,
                'data'=>[],
                'message'=>'Not Found'
            ]);
        }
    }
}
