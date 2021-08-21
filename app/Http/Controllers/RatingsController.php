<?php

namespace App\Http\Controllers;

use App\Models\ratings;
use App\Models\Restaurents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingsController extends Controller
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
        $upload_path = 'uploadedImages/ratings/';

        $rating =  ratings::create($request->except('image'));
        $restaurant = Restaurents::find($request->rest_id);
        if ($restaurant) {

            $restaurant->rating = ratings::where('rest_id', $restaurant->id)->pluck('rating')->avg();
            $restaurant->update();
        }
        if ($request->has('image')) {
            $file_name = $request->image->getClientOriginalName();
            $generated_new_name = time() . '.' . $file_name;
            $request->image->move($upload_path, $generated_new_name);
            $rating->image = $upload_path . $generated_new_name;
            $rating->save();
        }
        if ($rating) {
            return response()->json([
                'success' => true,
                'data' => $rating,
                'message' => 'Rating Stored Successfully',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Something bad happens',
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ratings  $ratings
     * @return \Illuminate\Http\Response
     */
    public function show(ratings $ratings)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ratings  $ratings
     * @return \Illuminate\Http\Response
     */
    public function edit(ratings $ratings)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ratings  $ratings
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ratings $ratings)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ratings  $ratings
     * @return \Illuminate\Http\Response
     */
    public function destroy(ratings $ratings)
    {
        //
    }

    public function getUserRatings()
    {
        $userid = Auth::user()->id;
        $ratings = ratings::where('user_id', $userid)->whereNotNull('image')->with(['order', 'likes', 'comments', 'user'])->get();
        return response()->json([
            'success' => true,
            'data' => $ratings,
            'message' => 'User Specific Reviews'
        ]);
    }

    public function getRestaurentRatings($id)
    {
        $restaurant = Restaurents::find($id);
        if (!$restaurant)
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'No Restaurant Found with given Id',
            ]);
        $ratings = ratings::where('rest_id', $id)->where('is_public', 1)->whereNotNull('image')->with(['user', 'comments', 'likes'])->get();
        return response()->json([
            'success' => true,
            'data' => $ratings,
            'message' => 'All reviews according to restaurant',
        ]);
    }

    public function getRatingById($id)
    {
        // ->whereNotNull('image')
        $ratings = ratings::where('id', $id)->with(['order', 'likes', 'comments', 'user'])->get();
        return response()->json([
            'success' => true,
            'data' => $ratings,
            'message' => 'Review according to id',
        ]);
    }

    public function restaurentReviewLength()
    {
        $ratings = ratings::groupBy('rating')
        ->selectRaw('count(*) as total, rating')
        ->get();
        return $ratings;
    }
}
