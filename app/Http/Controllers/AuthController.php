<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $upload_path = 'uploadedImages/profilePictures/';
        $validatedData   =  Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone_number' => 'required|string|max:15|unique:users'
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validatedData->errors()->first(),
            ]);
        }






        if ($request->has('photo')) {
            $file_name = $request->photo->getClientOriginalName();
            $generated_new_name = time() . '.' . $file_name;
            $request->photo->move($upload_path, $generated_new_name);
            $user =    User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'phone_number' => $request['phone_number'],
                'photo' => $upload_path . $generated_new_name,
                'uID' => $request->uid,
                'type'=>$request->type,
                'device_id'=>$request->device_id,
            ]);
        } else {
            $user =   User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'phone_number' => $request['phone_number'],
                'uID' => $request->uid,
                'type'=>$request->type,
                'device_id'=>$request->device_id,


            ]);
        }


        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success'=>true,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid login details'
            ]);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'data' => $user,
            'access_token' => $token,
            
        ]);
    }

    public function currentUser(Request $request)
    {
        if ($request->user()) {
            return response()->json([
                'success' => true,
                'data' => $request->user(),
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => []
            ]);
        }
    }

    public function checkUidAvailable(Request $request)
    {
        $user = User::where('uID',$request['uid'])->first();
        if($user){
            return response()->json([
                'success' => true,
                'status' =>0,
                'message' => 'Its Available'
            ]);
        }else{
            return response()->json([
                'success' => true,
                'status' =>1,
                'data' => 'No, its not available'
            ]);
        }
    }
}
