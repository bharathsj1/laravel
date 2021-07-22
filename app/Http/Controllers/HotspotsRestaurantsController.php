<?php

namespace App\Http\Controllers;

use App\Models\HotspotsRestaurants;
use Illuminate\Http\Request;

class HotspotsRestaurantsController extends Controller
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
        $hotspot = HotspotsRestaurants::create(
            [
                'restaurent_id' => $request['restaurent_id'],
                'hotspot_id' => $request['hotspot_id'],

            ]
        );

        return response()->json([
            'success'=>true,
            'data'=>$hotspot,
            'message'=>'Hotspot Saved Successfully',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\HotspotsRestaurants  $hotspotsRestaurants
     * @return \Illuminate\Http\Response
     */
    public function show(HotspotsRestaurants $hotspotsRestaurants)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\HotspotsRestaurants  $hotspotsRestaurants
     * @return \Illuminate\Http\Response
     */
    public function edit(HotspotsRestaurants $hotspotsRestaurants)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\HotspotsRestaurants  $hotspotsRestaurants
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HotspotsRestaurants $hotspotsRestaurants)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\HotspotsRestaurants  $hotspotsRestaurants
     * @return \Illuminate\Http\Response
     */
    public function destroy(HotspotsRestaurants $hotspotsRestaurants)
    {
        //
    }

    public function getHotspotRestaurentById($id)
    {
       $hotspotRestaurent = HotspotsRestaurants::where('hotspot_id',$id)->with(['hotspot','restaurentHotspot'])->get();
       return $hotspotRestaurent;
    }
}
