<?php

namespace App\Http\Controllers;

use App\Models\freeMeal;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FreeMealController extends Controller
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

        if ($user->stripe_cus_id != null) {
            $freeMeal =   freeMeal::create([
                'user_id' => $user->id,
                'meal_id' => $request->menu_id,
            ]);
            return response()->json([
                'success' => true,
                'data' => $freeMeal,
                'message' => 'Free meal saved successfully',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'User is not subscribed user'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\freeMeal  $freeMeal
     * @return \Illuminate\Http\Response
     */
    public function show(freeMeal $freeMeal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\freeMeal  $freeMeal
     * @return \Illuminate\Http\Response
     */
    public function edit(freeMeal $freeMeal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\freeMeal  $freeMeal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, freeMeal $freeMeal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\freeMeal  $freeMeal
     * @return \Illuminate\Http\Response
     */
    public function destroy(freeMeal $freeMeal)
    {
        //
    }

    public function getFreeMealById($id)
    {
        
        //CURRENT USER
        $currentUser = User::find($id);

        // USER IS NOT SUBSCRIBED
        if ($currentUser->stripe_cus_id == null) {
            return response()->json([
                'status' => false,
                'data' => [],
                'message' => 'User is not a subscribed user',
            ]);
        }

        $freeMeal = freeMeal::where('user_id', $id)->latest()->first();

        if ($freeMeal == null) {
            return response()->json([
                'status' => true,
                'data' => [],
                'message' => 'One free meal is available',
            ]);
        }
     //   $freeMealAvailabel = false;

        //7DAYS OLD DATE-TIME
        $mytime = Carbon::now()->subDays(7);
       
        if ($freeMeal->created_at < $mytime) {
            return response()->json([
                'status' => true,
                
                'message' => 'One free meal is available',
            ]);
        } else {
            return response()->json([
                'status' => false,
               
                'message' => 'You already took the free meal for this week',
            ]);
        }
    }
}
