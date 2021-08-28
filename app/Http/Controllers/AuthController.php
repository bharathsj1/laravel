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
        $stripe = new \Stripe\StripeClient(
            'sk_test_51ISmUBHxiL0NyAbFbzAEkXDMDC2HP0apPILEyaIYaUI8ux0yrBkHMI5ikWZ4teMNsixWP2IPv4yw9bvdqb9rTrhA004tpWU9yl'
        );

        if ($request['cust_registration_type'] == 2) {
            $request->merge([
                'cust_phone_number' => '00000000',
            ]);
            $user =   User::create($request->all());

            $customer =  $stripe->customers->create([
                // 'payment_method' => $request->paymentMethodId,
                'description' => 'NEW USER Signed up',
                'email' =>  $request['email'],
                'name' =>  $request['email'],
            ]);

            $user->password = Hash::make($request['password']);
           $user->stripe_cus_id = $customer->id;

            $user->update();

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'data' => $user,
                'access_token' => $token,
            ]);
        }
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


        $customer =  $stripe->customers->create([
            // 'payment_method' => $request->paymentMethodId,
            'description' => 'NEW USER Signed up',
            'email' =>  $request['email'],
            'name' =>  $request['cust_first_name'],
        ]);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Something really bad happens'
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
                'cust_uid' => $request->cust_uid,
                'cust_account_type' => $request['cust_account_type'],
                'cust_registration_type' => $request['cust_registration_type'],
                'stripe_cus_id' => $customer->id,

            ]);
        } else {
            $user =   User::create([
                'cust_first_name' => $request['cust_first_name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'cust_phone_number' => $request['cust_phone_number'],
                'cust_uid' => $request->cust_uid,
                'cust_account_type' => $request['cust_account_type'],
                'cust_registration_type' => $request['cust_registration_type'],
                'stripe_cus_id' => $customer->id,


            ]);
        }


        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => $user,
            'access_token' => $token,

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
        $user = Auth::user();
        if ($user) {
            return response()->json([
                'success' => true,
                'data' => $user,
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

        $user = User::where('cust_uid', $request['uid'])->first();

        if ($user) {
            $userData =     Auth::loginUsingId($user->id);


            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => true,
                'data' => $user,
                'access_token' => $token,

            ]);
        } else {
            return response()->json([
                'success' => false,
                'status' => 1,
                'data' => 'This UID is not available'
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
            'address_type' => $request['address_type'],
            'phone_no' => $request['phone_no'],
            'name' => $request['name'],
            'type' => $request['type'],
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
        $data = UserAddress::where('user_id', $userId)->orderBy('id', 'desc')->limit(5)->get();
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

    public function updateUser(Request $request)
    {
        $user = Auth::user();
        $user = User::find(Auth::user()->id);
        $user->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'User Profile Updated'
        ]);
    }

    public function changePassword(Request $request)
    {

        $input = $request->all();
        $userid = Auth::user()->id;
        $rules = array(
            'old_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $arr = array("success" => false, "message" => $validator->errors()->first(), "data" => array());
        } else {
            try {
                if ((Hash::check(request('old_password'), Auth::user()->password)) == false) {
                    $arr = array("success" => false, "message" => "Check your old password.", "data" => array());
                } else if ((Hash::check(request('new_password'), Auth::user()->password)) == true) {
                    $arr = array("success" => false, "message" => "Please enter a password which is not similar then current password.", "data" => array());
                } else {
                    User::where('id', $userid)->update(['password' => Hash::make($input['new_password'])]);
                    $arr = array("success" => true, "message" => "Password updated successfully.", "data" => array());
                }
            } catch (\Exception $ex) {
                if (isset($ex->errorInfo[2])) {
                    $msg = $ex->errorInfo[2];
                } else {
                    $msg = $ex->getMessage();
                }
                $arr = array("success" => false, "message" => $msg, "data" => array());
            }
        }
        return response($arr);
    }
}
