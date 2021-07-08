<?php

namespace App\Http\Controllers;

use App\Models\DeviceToken;
use App\Models\Notifications;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LaravelFCM\Facades\FCM;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

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
        $upload_path = 'uploadedImages/notifications/';

        $user = User::where('cust_account_type', '0')->get();
        foreach ($user as $key => $value) {
            $tokens = DeviceToken::where('user_id', $value->id)->get()->pluck('firebase_token');
            $this->sendSingleNotification($tokens, $request->title, $request->body, $request->data);
            if ($request->has('image')) {
                $file_name = $request->image->getClientOriginalName();
                $generated_new_name = time() . '.' . $file_name;
                $request->photo->move($upload_path, $generated_new_name);
                $notification =   Notifications::create([
                    'title' => $request->title,
                    'subtitle' => $request->body,
                    'data' => $request->data,
                    'image' => $upload_path . $generated_new_name,
                    'user_id' => $value->id,
                ]);
                return response()->json([
                    'success' => true,
                    'data' => $notification,
                    'meesage' => 'Notification Stored',
                ]);
            } else {
                $notification =     Notifications::create([
                    'title' => $request->title,
                    'subtitle' => $request->body,
                    'data' => $request->data,
                    'user_id' => $value->id,

                ]);

                return response()->json([
                    'success' => true,
                    'data' => $notification,
                    'meesage' => 'Notification Stored',
                ]);
            }
        }
    }

    //For Delivery Person

    public function sendNotificationToDeliverBoy(Request $request)
    {
        $upload_path = 'uploadedImages/notifications/';

        $user = User::where('cust_account_type', '3')->get();
        foreach ($user as $key => $value) {
            $tokens = DeviceToken::where('user_id', $value->id)->get()->pluck('firebase_token');
            $this->sendSingleNotification($tokens, $request->title, $request->body, $request->data);
            if ($request->has('image')) {
                $file_name = $request->image->getClientOriginalName();
                $generated_new_name = time() . '.' . $file_name;
                $request->photo->move($upload_path, $generated_new_name);
                $notification =   Notifications::create([
                    'title' => $request->title,
                    'subtitle' => $request->body,
                    'data' => $request->data,
                    'image' => $upload_path . $generated_new_name,
                    'user_id' => $value->id,
                ]);
                return response()->json([
                    'success' => true,
                    'data' => $notification,
                    'meesage' => 'Notification Stored',
                ]);
            } else {
                $notification =     Notifications::create([
                    'title' => $request->title,
                    'subtitle' => $request->body,
                    'data' => $request->data,
                    'user_id' => $value->id,

                ]);

                return response()->json([
                    'success' => true,
                    'data' => $notification,
                    'meesage' => 'Notification Stored',
                ]);
            }
        }
    }

    public function sendNotificationToSpecificUser(Request $request)
    {
        $upload_path = 'uploadedImages/notifications/';

        $tokens =  DeviceToken::where('user_id', $request->user_id)->get()->pluck('firebase_token');
        if ($tokens) {
            $tos =    $this->sendSingleNotification($tokens, $request->title, $request->body, $request->data);
            if ($request->has('image')) {
                $file_name = $request->image->getClientOriginalName();
                $generated_new_name = time() . '.' . $file_name;
                $request->photo->move($upload_path, $generated_new_name);
                $notification =   Notifications::create([
                    'title' => $request->title,
                    'subtitle' => $request->body,
                    'data' => $request->data,
                    'image' => $upload_path . $generated_new_name,
                    'user_id' => $request->user_id,
                ]);
                return response()->json([
                    'success' => true,
                    'data' => $notification,
                    'meesage' => 'Notification Stored',
                ]);
            } else {
                $notification =     Notifications::create([
                    'title' => $request->title,
                    'subtitle' => $request->body,
                    'data' => $request->data,
                    'user_id' => $request->user_id,

                ]);

                return response()->json([
                    'success' => true,
                    'data' => $notification,
                    'meesage' => 'Notification Stored',
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'meesage' => 'No FCM TOKEN AVAILABLE',
            ]);
        }
    }



    public function sendNoticationTOSuperAdmin($tokens, $title, $body)
    {

        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);
        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($body)
            ->setSound('default');
        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData([
            'data_1' => 'first_data'
        ]);


        $data = $dataBuilder->build();


        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();


        $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);
        return $downstreamResponse;
    }

    public function sendSingleNotification($token, $title, $body, $data)
    {

        $SERVER_API_KEY = 'AAAAV0DCJ4I:APA91bGCijvnYnQgphS7rUTDtpfXtVqGcJxWXM8O-nAOZSp3rE9DOUDJav8-B3oaZpZgjI3Engzj_Y3wkxJ26c0X16hv8HOrceP3NVolYel8rE55IHUvVZzD8s-fqQsXasijSUVxJLHn';
        $data = [
            'registration_ids' => $token,
            "notification" => [
                'title' => $title,
                "body" => $body,
                'data' => $data,
            ]
        ];

        $dataString = json_encode($data);
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);

        return $response;
    }

    public function getSpecificNotification()
    {
        $user = Auth::user();
        $notifications = Notifications::where('user_id', $user->id)->get();
        if ($notifications) {
            return response()->json([
                'success' => true,
                'data' => $notifications,
                'message' => 'Notifications'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Not Found'
            ]);
        }
    }
}
