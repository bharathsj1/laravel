<?php

namespace App\Http\Controllers;

use App\Models\PaymentIntent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentIntentController extends Controller
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
        $user = Auth::user();

        $paymentIntent =  PaymentIntent::create([
            'user_id' => $user->id,
            'payment_method_id' => $request->payment_method_id,
            'stripe_cus_id' => $user->stripe_cus_id,

        ]);

        if ($paymentIntent) {
            return response()->json([
                'success' => true,
                'data' => $paymentIntent,
                'message' => 'Stripe Payment Method Successfully Added',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Failed to Store the payment method',
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PaymentIntent  $paymentIntent
     * @return \Illuminate\Http\Response
     */
    public function show(PaymentIntent $paymentIntent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PaymentIntent  $paymentIntent
     * @return \Illuminate\Http\Response
     */
    public function edit(PaymentIntent $paymentIntent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PaymentIntent  $paymentIntent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PaymentIntent $paymentIntent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PaymentIntent  $paymentIntent
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaymentIntent $paymentIntent)
    {
        //
    }

    public function getPaymentMethod()
    {
        $user = Auth::user();
        $paymentMethod = PaymentIntent::where('user_id',$user->id)->get();
        if($paymentMethod)
        {
            return response()->json([
                'success'=>true,
                'data'=>$paymentMethod,
                'message'=>'Payment Method Available Here',
            ]);
        }else{
            return response()->json([
                'success'=>false,
                'data'=>[],
                'message'=>'No Payment Method Available Here',
            ]);
        }
    }
}
