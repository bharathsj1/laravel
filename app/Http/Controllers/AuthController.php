<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserAddress;
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
            'cust_first_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'cust_phone_number' => 'required|string|max:15|unique:users'
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
                'cust_first_name' => $request['cust_first_name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'cust_phone_number' => $request['cust_phone_number'],
                'cust_profile_image' => $upload_path . $generated_new_name,
                'cust_uid' => $request->uid,
                'cust_account_status' => $request['cust_account_status'],
                'cust_registration_type' => $request['cust_registration_type']

            ]);
        } else {
            $user =   User::create([
                'cust_first_name' => $request['cust_first_name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'cust_phone_number' => $request['cust_phone_number'],
                'cust_uid' => $request->uid,
                'cust_account_status' => $request['cust_account_status'],
                'cust_registration_type' => $request['cust_registration_type']

            ]);
        }


        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
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
        $user = User::where('uID', $request['uid'])->first();
        if ($user) {
            return response()->json([
                'success' => true,
                'status' => 0,
                'message' => 'Its Available'
            ]);
        } else {
            return response()->json([
                'success' => true,
                'status' => 1,
                'data' => 'No, its not available'
            ]);
        }
    }


    public function addUserAddress(Request $request)
    {
        $userAddress = UserAddress::create([
            'address' => $request['address'],
            'city' => $request['city'],
            'country' => $request['country'],
            'user_id' => Auth::user()->id,
            'user_latitude' => $request['latitude'],
            'user_longitude' => $request['longitude'],
            'address_type'=> $request['address_type'],
            'phone_no'=>$request['phone_no'],
        ]);

        if ($userAddress) {
            return response()->json([
                'success' => true,
                'data' => $userAddress,
                'message' => 'User Address Saved'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'data' => 'Failed'
            ]);
        }
    }

    public function getUserAddress()
    {
        $userId = Auth::user()->id;
        $data = UserAddress::where('user_id',$userId)->orderBy('id','desc')->limit(5)->get();
        if ($data) {
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'User Addresses'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'data' => 'Address is empty'
            ]);
        }
        
    }
}
