<?php

namespace App\Http\Controllers;

use App\Models\ReceipeOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReceipeOrderController extends Controller
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
        $reciepeOrder =   ReceipeOrder::create([
            'user_id' => $user->id,
            'customer_address_id' => $request->customer_address_id,
            'receipe_id' => $request->receipe_id,
            'amount' => $request->amount,
            'payment_intent_id' => $request->payment_intent_id,
            'person_quantity' => $request->person_quantity,
            'status' => $request->status,
        ]);

        if ($reciepeOrder) {
            return response()->json([
                'success' => true,
                'data' => $reciepeOrder,
                'message' => 'Order Placed Succeessfully',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'SOme Error Occured',
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ReceipeOrder  $receipeOrder
     * @return \Illuminate\Http\Response
     */
    public function show(ReceipeOrder $receipeOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ReceipeOrder  $receipeOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(ReceipeOrder $receipeOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ReceipeOrder  $receipeOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReceipeOrder $receipeOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ReceipeOrder  $receipeOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReceipeOrder $receipeOrder)
    {
        //
    }

    public function getReceipeOrders()
    {
        $user = Auth::user();

        $receipeOrder = ReceipeOrder::where('user_id',$user->id)->get();
        return response()->json([
            'success'=>true,
            'data'=>$receipeOrder,
            'message'=>'User Receipies Order',
        ]);
    }
}
