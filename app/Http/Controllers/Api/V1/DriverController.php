<?php

namespace App\Http\Controllers\Api\V1;
use App\FireBase\FireBase;
use App\Http\Resources\GetRoutesResource;
use App\Models\AssignBuses;
use App\Models\BaseNumber;
use App\Models\Buses;
use App\Models\BusStations;
use App\Models\Driver;
use App\Models\DriverCurrentLocation;
use App\Models\Routes;
use App\Utility\Utility;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class DriverController extends Controller
{
    /**
     * Create driver Registration By Mobile Number
     *send SMS on mobile to create otp
     * @param Request $request,country_code,mobile_number
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */

    public function driverLogin(Request $request)
    {
        try {
            Log::info('app.requests', ['request' => $request->all(),'URL'=>$request->url()]);
                $messages = [
                'email' => 'required',
                'password' => 'required',
            ];
            $validator = Validator::make($request->all(), $messages);

             if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()->first()
                ], 401);
            }
            $driver = Driver::where(['email'=>$request->email])->first();
            if (!$driver) {
                return response()->json(['success'=>false, 'message' => 'Login Fail, please check email']);
            }
            $password = $request->password;
            if(!Hash::check($password, $driver->password)) {
                return response()->json(['success'=>false, 'message' => 'Login Fail, please check password']);
            }
            $token=JWTAuth::fromUser($driver);
            $update_token = Driver::where('id',$driver->id)->update(['driver_JWT_Auth_Token'=>$token]);
            $driver_data = Driver::getdriverfull($driver->id);

            return response()->json([
                'success' => true,
                'new_user' => false,
                'driver' => $driver_data,
                'token' => $token
            ], 200);

    }catch(\Exception $e){
            $message = $e->getMessage();
            $error = ['field'=>'driver_login','message'=>$message];
            $errors =[$error];
            return response()->json(['success'=>false,'code'=>'500','errors' => $errors], 500);
            }
    }


     /**
     * Driver edit profile data
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */

    public function driverEditProfile(Request $request)
    {
        try {
            Log::info('app.requests', ['request' => $request->all(),'URL'=>$request->url()]);
            $token=JWTAuth::getToken();
            $driver = \Auth::guard('driver')->user();

            if (!$driver){
                return response()->json([
                    'success' => false,
                    'message' => "Token is Invalid",
                ], 200);
            }

        if(isset($request->profile_pic) && !empty($request->profile_pic) && $request->hasFile('profile_pic')){
            if($driver->profile_pic != "assets/default/driver.png") {
                @unlink(public_path() . '/' . $driver->profile_pic);
            }
            $mime= $request->profile_pic->getMimeType();
            $image = $request->file('profile_pic');
            $image_name =  preg_replace('/\s+/', '', $image->getClientOriginalName());
            $ImageName = time() .'-'.$image_name;
            $image->move('./assets/user/driver/profile_pic/', $ImageName);
            $path_image = 'assets/user/driver/profile_pic/'.$ImageName;
            $update = Driver::where('id', $driver->id)->update(['du_profile_pic' => $path_image]);
            return response()->json([
                'driver' => Driver::getdriver($driver->id),
            ], 200);
        }

        if(isset($request->full_name) && !empty($request->full_name)){
            $update = Driver::where(['id'=>$driver->id])->update(['du_full_name' => $request->full_name]);
        }

        if(isset($request->mobile_no) && !empty($request->mobile_no) && isset($request->country_code) && !empty($request->country_code)){
            $otp = rand(100000,999999);
            if(Driver::where(['du_mobile_number'=>$request->mobile_no,'du_country_code'=>$request->country_code])->exists()){
                $message = "Mobile Number already exists";
                $error = ['field'=>'driver_profile_not_edit','message'=>$message];
                $errors =[$error];
                return response()->json([ 'success' => false,'errors' => $errors], 401);
            }else {
                $base_number = BaseNumber::where(['mobile_number' => $driver->du_mobile_number, 'country_code' => $driver->du_country_code])->first();
                if($base_number) {
                    $base_number_1 = BaseNumber::where(['id' => $base_number->id])->update(['mobile_number' => $request->mobile_no, 'country_code' => $request->country_code, 'full_mobile_number' => $request->country_code . $request->mobile_no, 'verification_code' => $otp]);
                    $update = Driver::where(['id' => $driver->id])->update(['du_mobile_number' => $request->mobile_no, 'du_country_code' => $request->country_code, 'du_mobile_number_verified' => 0]);
                }else{
                    $base_number_1 = BaseNumber::create(['mobile_number' => $request->mobile_no, 'country_code' => $request->country_code, 'full_mobile_number' => $request->country_code . $request->mobile_no, 'verification_code' => $otp]);

                    $update = Driver::where(['id' => $driver->id])->update(['du_mobile_number' => $request->mobile_no, 'du_country_code' => $request->country_code, 'du_mobile_number_verified' => 0]);

                }
//                $message_sms = "<#> Whipp PIN: ".$otp.". Never share this PIN with anyone. Whipp will never call you to ask for this. kNkQivZhomT";
//                $user_number = "96597631404";
//                $sendSMS = Utility::sendSMS($message_sms,$user_number);
            }
            if($update){
                return response()->json([
                    'success' => true,
                    'otp'=> $otp,
                    'id'=>$base_number->id,
                    'mobile_number'=> $request->mobile_no,
                    'country_code'=>$request->country_code,
                    'message' => "Code will be received"
                ], 200);

            }else{
                $message = "Profile not edited";
                $error = ['field'=>'driver_profile_not_edit','message'=>$message];
                $errors =[$error];
                return response()->json(['success' => false,'errors' => $errors], 401);
            }
        }
            if(isset($request->email) && !empty($request->email)) {
                if (Driver::where(['email' => $request->email])->exists()) {
                    $message = "email already exist";
                    $error = ['field' => 'email_already_exist', 'message' => $message];
                    $errors = [$error];
                    return response()->json(['success' => false,'errors' => $errors], 401);
                } else {
                    $messages = [
                        'required' => 'the_field_is_required'
                    ];
                    $validator = Validator::make($request->all(), [
                        'email' => 'required',
                    ], $messages);
                    if ($validator->fails()) {

                        return response()->json([
                            'errors' => $validator->errors()->first()
                        ], 401);
                    }
                    $update = Driver::where(['id' => $driver->id])->update(['email' => $request->email, 'is_email_verified' => 0]);
                    $driverObj = Driver::find($driver->id);
                    $name = $driverObj->du_full_name;
                    $id = $driverObj->id;
//                    $socialLinks = BaseAppSocialLinks::all();
//                    $header = EmailHeader::where('id', 7)->first();
//                    $headerTrans = EmailHeaderTranslation::where(['email_header_id' => 7, 'locale' => $driverObj->locale])->first();
//
//                    $bodyTrans = EmailBodyTranslation::where(['email_body_id' => 7, 'locale' => $driverObj->locale])->first();
//
//                    $footerTrans = EmailFooterTranslation::where(['email_footer_id' => 7, 'locale' => $driverObj->locale])->first();
//                    $user_type = 'driver';
//                    $langtxt = $driverObj->locale;
//                    Mail::to($driverObj->email)->send(new WelcomeDriverEmail($name, $id, $socialLinks, $header, $headerTrans, $bodyTrans, $footerTrans, $langtxt, $user_type));
                }
            }
        if(isset($request->password)){
                $check = Auth::guard('driver')->attempt([
                    'email' => $driver->email,
                    'password' => $request->old_password
                ]);
                if($check) {
                    $update = Driver::where(['id' => $driver->id])->update(['password' => bcrypt($request->password)]);
                }else{
                    $message = "Password Doesn't match";
                    $error = ['field'=>'driver_profile_not_edit','message'=>$message];
                    $errors =[$error];
                    return response()->json(['success' => false,'errors' => $errors], 401);
                }
        }
            return response()->json([
                'driver' => Driver::getdriver($driver->id),
            ], 200, ['Content-Type' => 'application/json; charset=UTF-8',
                'charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);

        }catch(\Exception $e){
            $message = $e->getMessage();
            $error = ['field'=>'error','message'=>$message];
            $errors =[$error];
            return response()->json(['success' => false,'errors' => $errors], 500);
        }
    }

     /**
     * Driver logout
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */

    public function logout(Request $request)
    {
        Log::info('app.requests', ['request' => $request->all(),'URL'=>$request->url()]);
        try {
            $token=JWTAuth::getToken();
            $driver = Auth::guard('driver')->user();
            JWTAuth::invalidate($token);
            $success = true;
            $code = '200';
            $message = "Logout successfully";
            return response()->json(['message'=>$message ], 200);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $error = ['field'=>'language_strings','message'=>$message];
            $errors =[$error];
            return response()->json(['errors' => $errors], 500);
        }
    }

    /**
     * get my (driver) profile
     * @param Request $request
     * @return Response
     * @throws Exception
     */

    public function myProfile(Request $request){
        try{
            Log::info('app.requests', ['request' => $request->all(),'URL'=>$request->url()]);
            $driver = \Auth::guard('driver')->user();
            if (!$driver){
                return response()->json([
                    'success' => false,
                    'message' => "Token is Invalid",
                ], 200);
            }
            $driver_data = Driver::getdriverfull($driver->id);
//            $NodeUser = FireBase::storedriver($driver->id,$driver_data);
            return response()->json([
                'driver' => $driver_data,
            ], 200);
        }catch(\Exception $e){
            $message = LanguageString::translated()->where('bls_name_key','error')->first()->name;
            $error = ['field'=>'language_strings','message'=>$message];
            $errors =[$error];
            return response()->json(['errors' => $errors], 500);
        }
    }


    /**
     * get Driver Wallet,cash,credit Card list
     * @param Request $request
     * @return Response
     * @throws Exception
     * @throws \Throwable
     */

    public function getDriverWallet(){
        try{
            $token = JWTAuth::getToken();
            $driver = Auth::guard('driver')->user();
             if(!empty($driver)) {
                 $crrDate = date('Y-m-d');
                 $driverCrrDate = DriverAccount::leftJoin('ride_booking_schedules', 'driver_accounts.dc_ride_id', '=', 'ride_booking_schedules.id')->where(['driver_accounts.dc_target_id'=> $driver->id, 'driver_accounts.dc_target_type' => 'driver','driver_accounts.dc_operation_type' => 'ride'])
                     ->whereDate('driver_accounts.created_at', $crrDate)->get();

                 if (isset($driverCrrDate)){
                     foreach ($driverCrrDate as $date){

                         $res = app('geocoder')->reverse($date->rbs_source_lat,$date->rbs_source_long)->get()->first();
                         $des = app('geocoder')->reverse($date->rbs_destination_lat,$date->rbs_destination_long)->get()->first();


                         if ($date->rbs_payment_method == 'wallet'){
                             $pay_image = 'assets/creditCard/Wallet.png';
                         }elseif ($date->rbs_payment_method == 'cash'){
                             $pay_image = 'assets/creditCard/Cash.png';
                         }elseif ($date->rbs_payment_method == 'creditCard'){
                             $pay_image = 'assets/creditCard/Visa.png';
                         }

                         $invoice = CustomerInvoice::where(['ci_ride_id' => $date->dc_ride_id])->first();

                         if (isset($invoice) && $invoice != null){
                             $rideCost = number_format((float)$invoice->ci_customer_invoice_amount, 2, '.', '');
                         }else{
                             $rideCost = number_format((float)$date->rbs_estimated_cost, 2, '.', '');
                         }

                         $date->ride_location = [
                             'from'=> $res->getFormattedAddress(),
                             'to'=> $des->getFormattedAddress(),
                         ];

                         $date->driver_income = $rideCost;
                         $date->driver_source = $date->dc_source_type;
                         $date->ride_status =$date->rbs_ride_status;
                         $date->payment_image = $pay_image;
                         $date->date = date('d-m-Y H:i', strtotime($date->rbs_created_at));

                     }
                 }else{
                     $driverCrrDate = [];
                 }

                 $driverLastDate = DriverAccount::leftJoin('ride_booking_schedules', 'driver_accounts.dc_ride_id', '=', 'ride_booking_schedules.id')->where(['driver_accounts.dc_target_id'=> $driver->id, 'driver_accounts.dc_target_type' => 'driver','driver_accounts.dc_operation_type' => 'ride'])
                     ->whereDate('driver_accounts.created_at','>=', Carbon::now()->subDays(7))
                     ->whereDate('driver_accounts.created_at','<', Carbon::now())->get();


                 if (isset($driverLastDate)){
                     foreach ($driverLastDate as $date){

                         $res = app('geocoder')->reverse($date->rbs_source_lat,$date->rbs_source_long)->get()->first();
                         $des = app('geocoder')->reverse($date->rbs_destination_lat,$date->rbs_destination_long)->get()->first();

                         if ($date->rbs_payment_method == 'wallet'){
                             $pay_image = 'assets/creditCard/Wallet.png';
                         }elseif ($date->rbs_payment_method == 'cash'){
                             $pay_image = 'assets/creditCard/Cash.png';
                         }elseif ($date->rbs_payment_method == 'creditcard'){
                             $pay_image = 'assets/creditCard/Visa.png';
                         }

                         $date->ride_location = [
                             'from'=> $res->getFormattedAddress(),
                             'to'=> $des->getFormattedAddress(),
                         ];

                         $date->driver_income = $date->dc_amount;
                         $date->driver_source = $date->dc_source_type;
                         $date->ride_status =$date->rbs_ride_status;
                         $date->payment_image = $pay_image;
                         $date->date = date('d-m-Y H:i', strtotime($date->rbs_created_at));

                     }
                 }else{
                     $driverLastDate = [];
                 }
                 $driverBalance = DriverAccount::where(['driver_accounts.dc_target_id'=> $driver->id, 'driver_accounts.dc_target_type' => 'driver','driver_accounts.dc_operation_type' => 'ride'])->get()->last();
                    if ($driverBalance){
                        $driverBalance = $driverBalance->dc_balance;
                    }

             return response()->json(['wallet' => view('driver.DriverWallet')->with(['crrDateRides' => $driverCrrDate,'lastDateRides' => $driverLastDate,'driverBalance' => $driverBalance])->render()]);

             }
             else{
                 return response()->json((object) null, 200);
             }
        }catch(\Exception $e){
            $message = LanguageString::translated()->where('bls_name_key','error')->first()->name;
            $error = ['field'=>'language_strings','message'=>$message];
            $errors =[$error];
            return response()->json(['errors' => $errors], 500);
        }
    }

    /**
     * create driver Current Location
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */

    public function driverCurrentLocation(Request $request){
        try{
            Log::info('app.requests', ['request' => $request->all(),'URL'=>$request->url()]);
            $token=JWTAuth::getToken();
            $driver = Auth::guard('driver')->user();

            $messages = [
                'required' => 'the_field_is_required',
                'string' => 'the_string_field_is_required',
                'max' => 'the_field_is_out_from_max',
                'min' => 'the_field_is_low_from_min',
                'unique' => 'the_field_should_unique',
                'confirmed' => 'the_field_should_confirmed',
                'email' => 'the_field_should_email',
                'exists' => 'the_field_should_exists',
                'numeric' => 'the_field_should_numeric',
                'gt' => 'the_field_should_greater_than_zero',
                'lt' => 'the_field_should_less_than_180',
            ];
            $validator = Validator::make($request->all(), [

                'lat' => 'required|numeric|gt:0|lt:180',
                'long' => 'required|numeric|gt:0|lt:180',
                'app_active' => 'required',

            ], $messages);
            if ($validator->fails()) {
                $errors = $validator->messages();
                return response()->json(compact('errors'), 401);
            }

            if($driver->du_driver_status == "driver_status_when_approved") {
                if (DriverCurrentLocation::where(['dcl_user_id' => $driver->id, 'dcl_user_type' => 'driver'])->exists()) {
                    $driver_location = DriverCurrentLocation::where(['dcl_user_id' => $driver->id, 'dcl_user_type' => 'driver'])->first();
                } else {
                    $driver_location = new DriverCurrentLocation;
                }

                $city = $request->city;
                $country = $request->country;
                $driver_location->dcl_user_id = $driver->id;
                $driver_location->dcl_user_type = 'driver';
                $driver_location->dcl_lat = $request->lat;
                $driver_location->dcl_long = $request->long;
                $driver_location->dcl_app_active = $request->app_active;
                if (isset($country) && $country != null) {
                    $driver_location->dcl_country = $country;
                }
                if (isset($city) && $city != null) {
                    $driver_location->dcl_city = $city;
                }
                $driver_location->save();

                $driver = Driver::getdriver($driver->id);

                return response()->json($driver, 200);
            }else{
                $message = $driver->du_driver_status;
                $error = ['message'=>$message];
                $errors =[$error];
                return response()->json(['errors' => $errors], 401);
            }
        }catch(\Exception $e){
            $message = $e->getMessage();
            $error = ['field'=>'get_a_bus','message'=>$message];
            $errors =[$error];
            return response()->json(['success'=>false,'code'=>'500','errors' => $errors], 500);
        }
    }

    public function updateBusSeats(Request $request){
        try{
            Log::info('app.requests', ['request' => $request->all(),'URL'=>$request->url()]);
            $token=JWTAuth::getToken();
            $driver = Auth::guard('driver')->user();

            $assignBus = AssignBuses::where(['driver_id' => $driver->id, 'status' => 'active'])->first();
            if($assignBus){
                $bus = Buses::where('id', $request->bus_id)->first();
                $current_seats = $bus->current_seats - $request->no_f_seats;

                Buses::where('id',$request->bus_id)->update([
                    'current_seats' => $current_seats
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'Bus Seats are updated',
                ], 200);
            }else{
                return response()->json([
                    'success' => true,
                    'message' => 'No Bus assigned to Driver',
                ], 200);
            }
        }catch(\Exception $e){
            $message = $e->getMessage();
            $error = ['field'=>'get_a_bus','message'=>$message];
            $errors =[$error];
            return response()->json(['success'=>false,'code'=>'500','errors' => $errors], 500);
        }
    }

    public function driverRoute(Request $request){
        try{
            Log::info('app.requests', ['request' => $request->all(),'URL'=>$request->url()]);
            $token=JWTAuth::getToken();
            $driver = Auth::guard('driver')->user();

            $assignBus = AssignBuses::where(['driver_id' => $driver->id, 'status' => 'active'])->get();

            foreach ($assignBus as $assign){
                $route = Routes::where('id', $assign->route_id)->first();
                $assign['routes'] = $route;
            }
            $routes = GetRoutesResource::collection($assignBus);

            return response()->json([
                'success' => true,
                'routes' => $routes
            ], 200);

        }catch(\Exception $e){
            $message = $e->getMessage();
            $error = ['field'=>'driver_route','message'=>$message];
            $errors =[$error];
            return response()->json(['success'=>false,'code'=>'500','errors' => $errors], 500);
        }
    }

    public function routeStations(Request $request){
        try{
            Log::info('app.requests', ['request' => $request->all(),'URL'=>$request->url()]);
            $token=JWTAuth::getToken();
            $driver = Auth::guard('driver')->user();

            $route = Routes::where('id', $request->route_id)->first();
            $busStations = array();
                foreach($route->routetations as $station){
                    $stations = BusStations::where('id', $station->station_id)->first();
                    $busStations[] = $stations;
                }
            return response()->json([
                'success' => true,
                'busStations' => $busStations
            ], 200);

        }catch(\Exception $e){
            $message = $e->getMessage();
            $error = ['field'=>'driver_route','message'=>$message];
            $errors =[$error];
            return response()->json(['success'=>false,'code'=>'500','errors' => $errors], 500);
        }
    }

}
