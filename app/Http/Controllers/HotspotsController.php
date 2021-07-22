<?php

namespace App\Http\Controllers;

use App\Models\Hotspots;
use Illuminate\Http\Request;

class HotspotsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $hotspots = Hotspots::all();
        return response()->json([
            'success'=>true,
            'data'=>$hotspots,
            'meessag'=>'All Hotspot'
        ]);
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
        $hotspots = null;
        $upload_path = 'uploadedimages/hotspot';
        if ($request->has('image')) {
            $file_name = $request->photo->getClientOriginalName();
            $generated_new_name = time() . '.' . $file_name;
            $request->photo->move($upload_path, $generated_new_name);
            $hotspots = Hotspots::create([
                'name' => $request['name'],
                'address' => $request['address'],
                'lat' => $request['lat'],
                'lng' => $request['lng'],
                'distance' => $request['distance'],
                'image' => $upload_path . $generated_new_name,
                'delivery_time' => $request['delivery_time'],
                'hotspot_detail' => $request['hotspot_detail'],

            ]);
        } else {
            $hotspots = Hotspots::create([
                'name' => $request['name'],
                'address' => $request['address'],
                'lat' => $request['lat'],
                'lng' => $request['lng'],
                'distance' => $request['distance'],
                'delivery_time' => $request['delivery_time'],
                'hotspot_detail' => $request['hotspot_detail'],

            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $hotspots,
            'message' => 'Hotspot Saved Successfully'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Hotspots  $hotspots
     * @return \Illuminate\Http\Response
     */
    public function show(Hotspots $hotspots)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Hotspots  $hotspots
     * @return \Illuminate\Http\Response
     */
    public function edit(Hotspots $hotspots)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Hotspots  $hotspots
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Hotspots $hotspots)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Hotspots  $hotspots
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hotspots $hotspots)
    {
        //
    }
}
