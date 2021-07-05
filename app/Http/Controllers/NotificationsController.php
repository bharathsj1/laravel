<?php

namespace App\Http\Controllers;

use App\Models\Notifications;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationsController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Notifications  $notifications
     * @return \Illuminate\Http\Response
     */
    public function show(Notifications $notifications)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Notifications  $notifications
     * @return \Illuminate\Http\Response
     */
    public function edit(Notifications $notifications)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notifications  $notifications
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notifications $notifications)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notifications  $notifications
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notifications $notifications)
    {
        //
    }

    public function sendNotificationToSuperAdmin(Request $request)
    {
        $user = User::where('cust_account_type', '0')->get();
        foreach ($user as $key => $value) {
         return $value->id;
        }
    }

    public function sendSingleNotification($title, $body)
    {
        // $SERVER_API_KEY = 'AAAAaepY2kM:APA91bGm3awJEG97z75oAaGMXtmUhzxnSH3h1OsUkTa1ACfn54roan0-13HqLrT0TzfsVHm5PSLVRBKgtoVi-5hl0zUSujrJyUeU9VD20HM7iqYTlVEc8lXijzYsh2e7XGyLhEnp9oza';
        // $firebaseToken = DeviceTokens::where('user_id', Auth::user()->id)->get()->pluck('device_token');
        // $data = [
        //     'registration_ids' => $firebaseToken,
        //     "notification" => [
        //         'title' => $title,
        //         "body" => $body
        //     ]
        // ];

        // $dataString = json_encode($data);
        // $headers = [
        //     'Authorization: key=' . $SERVER_API_KEY,
        //     'Content-Type: application/json',
        // ];

        // $ch = curl_init();

        // curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        // $response = curl_exec($ch);

        // return $response;
    }
}
