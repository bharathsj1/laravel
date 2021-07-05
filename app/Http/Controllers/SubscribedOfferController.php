<?php

namespace App\Http\Controllers;

use App\Models\subscribed_offer;
use Illuminate\Http\Request;

class SubscribedOfferController extends Controller
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
        $data = subscribed_offer::create($request->all());
        if($data)
        {
            return response()->json([
                'success'=>true,
                'data'=>$data,
                'message'=>'Succesfull'
            ]);
        }else{
            return response()->json([
                'success'=>false,
                'data'=>[],
                'message'=>'Failed'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\subscribed_offer  $subscribed_offer
     * @return \Illuminate\Http\Response
     */
    public function show(subscribed_offer $subscribed_offer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\subscribed_offer  $subscribed_offer
     * @return \Illuminate\Http\Response
     */
    public function edit(subscribed_offer $subscribed_offer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\subscribed_offer  $subscribed_offer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, subscribed_offer $subscribed_offer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\subscribed_offer  $subscribed_offer
     * @return \Illuminate\Http\Response
     */
    public function destroy(subscribed_offer $subscribed_offer)
    {
        //
    }
}
