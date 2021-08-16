<?php

namespace App\Http\Controllers;

use App\Models\Likes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikesController extends Controller
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
        $userId = Auth::user()->id;
        $likePost = Likes::where('user_id', $userId)->where('review_id', $request->review_id);
        if (!$likePost) {
            $like = Likes::create([
                'user_id' => $userId,
                'review_id' => $request->review_id,
            ]);
            return response()->json([
                'success' => true,
                'data' => $like,
                'message' => 'Post is liked',
            ]);
        }

        $likePost->is_like = !$likePost->is_link;
        $likePost->update();
        return response()->json([
            'success' => true,
            'data' =>$likePost,
            'message' => 'Like Update',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Likes  $likes
     * @return \Illuminate\Http\Response
     */
    public function show(Likes $likes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Likes  $likes
     * @return \Illuminate\Http\Response
     */
    public function edit(Likes $likes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Likes  $likes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Likes $likes)
    {
        $user = Auth::user();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Likes  $likes
     * @return \Illuminate\Http\Response
     */
    public function destroy(Likes $likes)
    {
        //
    }
}
