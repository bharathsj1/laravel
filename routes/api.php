<?php

use App\Http\Controllers\AccountTypeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeviceTokenController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RestaurentsController;
use App\Http\Controllers\SubcategoriesController;
use App\Http\Controllers\SubscribedOfferController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SubscriptionPlanController;
use App\Models\DeviceToken;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register_user', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/check-uid', [AuthController::class, 'checkUidAvailable']);
Route::post('/current-user', [AuthController::class, 'currentUser'])->middleware(('auth:sanctum'));
Route::get('get-all-orders',[OrderController::class,'getAllOrders']);
Route::post('/get-restaurents', [RestaurentsController::class, 'index']);
Route::get('getOrdersForSpecificOwnerRestaurent',[RestaurentsController::class,'getOrdersForSpecificRes'])->middleware(('auth:sanctum'));
Route::post('/make-payment', [PaymentController::class, 'store'])->middleware(('auth:sanctum'));
Route::post('/make-order', [OrderController::class, 'store'])->middleware(('auth:sanctum'));
Route::post('/make-menu', [MenuController::class, 'store'])->middleware(('auth:sanctum'));
Route::post('/addOrderItem', [OrderController::class, 'addOrderTime'])->middleware(('auth:sanctum'));
Route::post('/addOrderDetails', [OrderController::class, 'addOrderDetails'])->middleware(('auth:sanctum'));
Route::post('/addMenuTypes', [MenuController::class, 'addMenuTypes']);
Route::get('/get-menu-types',[MenuController::class,'getMenuTypes']);
Route::get('/get-menus/{id}',[MenuController::class,'getMenus']);
ROute::get('get-account-types',[AccountTypeController::class,'index']);
Route::get('/get-user-address',[AuthController::class,'getUserAddress'])->middleware(('auth:sanctum'));
Route::post('/addUserAddress', [AuthController::class, 'addUserAddress'])->middleware(('auth:sanctum'));
Route::post('/addDeviceToken', [DeviceTokenController::class, 'store'])->middleware(('auth:sanctum'));
Route::get('/get-user-orders', [OrderController::class, 'getUserOrders'])->middleware(('auth:sanctum'));
Route::post('/change-super-admin',[OrderController::class,'changeSuperAdmin']);
Route::post('/change-order-status',[OrderController::class,'changeOrderStatus']);
Route::post('/subscribed_offer',[SubscribedOfferController::class,'store']);
Route::post('/update-driver-lat-lng',[RestaurentsController::class,'updateDriverLatLng']);
Route::get('/get-specific-order/{id}',[OrderController::class,'getSpecificOrder']);
Route::post('/send-notification-to-specific-user',[NotificationsController::class,'sendNotificationToSpecificUser']);
Route::get('/getSpecificNotification', [NotificationsController::class, 'getSpecificNotification'])->middleware(('auth:sanctum'));

//DELETING FCM TOKEN

Route::delete('/deleteFCM',[DeviceTokenController::class,'deleteToken'])->middleware('auth:sanctum');


//Sending Notification to SuperAdminRole

Route::post('/sendNotificationToSuperAdmin',[NotificationsController::class,'sendNotificationToSuperAdmin']);
Route::post('/sendNotificationToDeliverBoy',[NotificationsController::class,'sendNotificationToDeliverBoy']);

//SUBSCRIPTION
Route::post('/storeSubscription',[SubscriptionController::class,'store']);
Route::get('/get-all-subscription-plans',[SubscriptionPlanController::class,'index']);
Route::get('/get-specific-user-subs',[SubscriptionController::class,'getSpecificUserSubscription'])->middleware('auth:sanctum');
Route::get('/cancel-subscription/{id}',[SubscriptionController::class,'cancelSubscription'])->middleware('auth:sanctum');


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
