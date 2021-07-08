<?php

namespace App\Http\Controllers;

use App\Models\DeviceToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DeviceTokenController extends Controller
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
        $validatedData   =  Validator::make($request->all(), [
            'device_id' => 'required|string',
            'firebase_token' => 'required',


        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validatedData->errors()->first(),
            ]);
        }

        $data =    DeviceToken::create([
            'device_id' => $request['device_id'],
            'firebase_token' => $request['firebase_token'],
            'user_id' => Auth::user()->id,
        ]);

        if ($data) {
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Mobile ID Saved Successfully',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Some Error occured',
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DeviceToken  $deviceToken
     * @return \Illuminate\Http\Response
     */
    public function show(DeviceToken $deviceToken)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DeviceToken  $deviceToken
     * @return \Illuminate\Http\Response
     */
    public function edit(DeviceToken $deviceToken)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DeviceToken  $deviceToken
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DeviceToken $deviceToken)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DeviceToken  $deviceToken
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeviceToken $deviceToken)
    {
        //
    }

    public function deleteToken()
    {
        $user = Auth::user();
        $isDeleted =  DeviceToken::where('user_id', $user->id)->delete();
        if ($isDeleted) {
            return response()->json([
                'success' => true,
                'message' => 'Delted'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Delted'
            ]);
        }
    }
}
