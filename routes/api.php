<?php

use App\Utility\Utility;
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
Route::prefix('v1')->name('api.v1.')->namespace('Api\V1')->group(function(){


    Route::get('/sendSms', function (Request $request) {
        $message_sms = "<#> GGO PIN: 123 Never share this PIN with anyone. GGO will never call you to ask for this.";
        $user_number = $request->number;

        $sendSMS = Utility::sendSMS($message_sms,$user_number);
        return $sendSMS;

    });

    // User Register Mobile Number
    Route::post('registerMobileNumber', 'UserController@registerMobileNumber')->name('registerMobileNumber');
    // Create  verify Code
    Route::post('verifyCode', 'UserController@verifyCode')->name('verifyCode');
    // Create your Identity
    Route::post('yourIdentity', 'UserController@yourIdentity')->name('yourIdentity');
    // Get Verify Code
    Route::get('getOTPCode', 'UserController@getVerifyCode')->name('getOTPCode');
    // Menu list
    Route::get('getMenu', 'HomeController@getMenu')->name('getMenu');

    Route::get('socialLinks', 'SocialLinkController');
    Route::post('facebookLogin', 'AuthController@facebookLogin')->name('facebookLogin');
    Route::post('googleLogin', 'AuthController@googleLogin')->name('googleLogin');
    Route::post('appleLogin', 'AuthController@appleLogin')->name('appleLogin');

    // Pages
    Route::get('allPages', 'PageController@allPages')->name('allPages');
    Route::get('page/{id}', 'PageController@index')->name('index');

    // Driver Register Mobile Number
    Route::post('loginDriver', 'DriverController@driverLogin')->name('loginDriver');

    Route::group(['middleware' => ['jwt.verify']], function(){
            // user edit profile
            Route::post('editProfile', 'UserController@editProfile')->name('editProfile');
            // log out
            Route::get('logoutPassenger', 'UserController@logout')->name('logoutPassenger');

            // get current stations
            Route::post('getCurrentStation', 'BookARIdeController@getCurrentStations')->name('getCurrentStation');

            // get destination stations
            Route::post('getDestinationStation', 'BookARIdeController@getDestinationStation')->name('getDestinationStation');

            // get stations
            Route::post('getAStation', 'BookARIdeController@getRide')->name('getAStation');
            // get buses
            Route::post('getBuses', 'BookARIdeController@getAllBuses')->name('getBuses');
            // book rides
            Route::post('bookARide', 'BookARIdeController@BookARide')->name('bookARide');
            // get all rides
            Route::get('getAllRides', 'BookARIdeController@getAllRides')->name('getAllRides');
            // get ride detail
            Route::get('getRideDetail', 'BookARIdeController@getRideDetail')->name('getRideDetail');
            // complete ride
            Route::post('completeRide', 'BookARIdeController@CompleteRide')->name('completeRide');
            // Cancel ride
            Route::post('CancelRide', 'BookARIdeController@CancelRide')->name('CancelRide');
            //get wallet
            Route::get('getWallet', 'CustomerManagementController@getCustomerWallet')->name('getWallet');
            //add new wallet
            Route::post('addWallet', 'CustomerManagementController@addCustomerWallet')->name('addWallet');
            //get all stations
            Route::get('getAllStations', 'BookARIdeController@getAllStationsData')->name('getAllStations');
        });
    Route::group(['middleware' => ['drivers']], function(){
            // driver edit profile route
            Route::post('driverEditProfile', 'DriverController@driverEditProfile')->name('driverEditProfile');
            // get my driver profile
            Route::get('myDriverProfile', 'DriverController@myProfile')->name('myProfile');
            //driver current status
            Route::post('driverCurrentStatus', 'DriverController@driverCurrentLocation')->name('driverCurrentStatus');
            //update bus seats
            Route::post('updateSeats', 'DriverController@updateBusSeats')->name('updateSeats');
            // log out
            Route::get('logoutDriver', 'DriverController@logout')->name('logoutDriver');

            Route::get('routeDriver', 'DriverController@driverRoute')->name('routeDriver');

            Route::get('routeStations', 'DriverController@routeStations')->name('routeStations');

        });


});
