<?php

namespace App\Http\Controllers\Api\V1;


use App\FireBase\FireBase;
use App\Mail\WelcomeEmail;
use App\Models\BaseNumber;
use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Utility\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{

    public function registerMobileNumber(Request $request)
    {
        try {
            Log::info('app.requests', ['request' => $request->all(),'URL'=>$request->url()]);
            $validator = Validator::make($request->all(), [
                'country_code' => 'required|max:10',
                'mobile_no' => 'required',
            ]);
            if ($validator->fails()) {
                $errors = $validator->messages();
                return response()->json(compact('errors'), 401);
            }

            if(BaseNumber::where(['country_code'=>$request->country_code,'mobile_number'=>$request->mobile_no])->exists()
                && User::where(['country_code'=>$request->country_code,'mobile_no'=>$request->mobile_no])->exists()){
                $otp = rand(100000, 999999);
                $basenumber = BaseNumber::where(['country_code'=>$request->country_code,'mobile_number'=>$request->mobile_no])
                    ->update(['verification_code'=>$otp,"otp_verified" => '0']);
                $basenumber = BaseNumber::where(['country_code'=>$request->country_code,'mobile_number'=>$request->mobile_no])
                    ->first();
                $message_sms = "<#> GGO PIN: ".$otp.". Never share this PIN with anyone. GGO will never call you to ask for this.";
                $user_number = $request->country_code.$request->mobile_no;

                $sendSMS = Utility::sendSMS($message_sms,$user_number);

                return response()->json([
                    'id' => (int)$basenumber->id,
                    'otp' => $basenumber->verification_code,
                    'country_code' => $basenumber->country_code,
                    'mobile_no' => $basenumber->mobile_number
                ], 200, ['Content-Type' => 'application/json; charset=UTF-8',
                    'charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);

            }else{

                $otp = rand(100000, 999999);
                $basenumber = new BaseNumber();
                $basenumber->country_code = $request->input('country_code');
                $basenumber->mobile_number = $request->input('mobile_no');
                $basenumber->full_mobile_number = $request->input('country_code').''.$request->input('mobile_no');
                $basenumber->otp_api_response = '03 Message (M) is mandatory';
                $basenumber->otp_api_response_status = '1';
                $basenumber->verification_code = $otp;
                $basenumber->otp_verified = '0';
                $basenumber->save();
                $message_sms = "<#> GGO PIN: ".$otp.". Never share this PIN with anyone. GGO will never call you to ask for this.";
                $user_number = $request->country_code.$request->mobile_no;

                $sendSMS = Utility::sendSMS($message_sms,$user_number);

                return response()->json([
                    'id' => (int)$basenumber->id,
                    'otp' => $basenumber->verification_code,
                    'country_code' => $basenumber->country_code,
                    'mobile_no' => $basenumber->mobile_number
                ], 200, ['Content-Type' => 'application/json; charset=UTF-8',
                    'charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
            }
        }catch(\Exception $e){
            $message = $e->getMessage();
            $error = ['field'=>'mobile_number_not_created','message'=>$message];
            $errors =[$error];
            return response()->json(['errors' => $errors], 500);
        }
    }


    /**
     *  get verify code by user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */

    public function getVerifyCode(Request $request)
    {
        try {
            Log::info('app.requests', ['request' => $request->all(),'URL'=>$request->url()]);
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);
            if ($validator->fails()) {
                $errors = $validator->messages();
                return response()->json(compact('errors'), 401);
            }

            $row_code = BaseNumber::where(['id'=>$request->id])->first();
            if($row_code->verification_code){
                return response()->json([
                    'id' => (int)$request->id,
                    'verification_code' => $row_code->verification_code,
                    'country_code' => $row_code->country_code,
                    'mobile_no' => $row_code->mobile_number
                ], 200, ['Content-Type' => 'application/json; charset=UTF-8',
                    'charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
            }else{
                $message = "OPT does not match";
                $error = ['field'=>'otp_not_match','message'=>$message];
                $errors =[$error];
                return response()->json(['errors' => $errors], 500);
            }
        }catch(\Exception $e){
            $message = $e->getMessage();
            $error = ['field'=>'otp_not_match','message'=>$message];
            $errors =[$error];
            return response()->json(['errors' => $errors], 500);
        }
    }

    /**
     *  user verify code or opt
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */

    public function verifyCode(Request $request)
    {
        try {
            Log::info('app.requests', ['request' => $request->all(),'URL'=>$request->url()]);
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'verification_code' => 'required',
                'device_u_id' => 'required',
                'device_type' => 'required',
                'device_token' => 'required',
            ]);
            if ($validator->fails()) {
                $errors = $validator->messages();
                return response()->json(compact('errors'), 401);
            }

            $row_code = BaseNumber::where(['id'=>$request->id])->first();
            if($row_code->verification_code == $request->verification_code){
                $update = BaseNumber::where('id', $request->id)->update(['otp_verified' => 1]);
                if(User::where(['country_code'=>$row_code->country_code,'mobile_no'=>$row_code->mobile_number])->exists()){

                    $user_update = User::where(['country_code'=>$row_code->country_code,'mobile_no'=>$row_code->mobile_number])
                        ->update(['mobile_number_verified'=>1]);
                    $user = User::where(['country_code'=>$row_code->country_code,'mobile_no'=>$row_code->mobile_number])->first();

                    if(isset($user->user_JWT_Auth_Token) && $user->user_JWT_Auth_Token != null) {
                        $newToken = JWTAuth::manager()->invalidate(new \Tymon\JWTAuth\Token($user->user_JWT_Auth_Token), $forceForever = false);
                    }

                    $token=JWTAuth::fromUser($user);
                    $update_token = User::where('id',$user->id)->update(['user_JWT_Auth_Token'=>$token]);
                    if($request->device_type == 1){ $type = 'Android';}
                    if($request->device_type == 2){ $type = 'iOS';}
                    if(Device::where(['device_type'=>$type,'device_token'=>$request->device_token,'user_id'=>$user->id,'app_type'=>'Passenger','device_u_id'=>$request->device_u_id])->exists()){
                        $deleteDevice = Device::where(['user_id'=>$user->id,'app_type'=>'Passenger'])->delete();
                        $deleteDevice = Device::where(['device_u_id'=>$request->device_u_id,'app_type'=>'Passenger'])->delete();
                        $data = ['device_type'=>$request->device_type,'device_token'=>$request->device_token,'user_id'=>$user->id,'app_type'=>'Passenger','device_u_id'=>$request->device_u_id];
                        $device = Device::updateOrCreate(['user_id'=>$user->id],$data);
                    }else{
                        $deleteDevice = Device::where(['user_id'=>$user->id,'app_type'=>'Passenger'])->delete();
                        $deleteDevice = Device::where(['device_u_id'=>$request->device_u_id,'app_type'=>'Passenger'])->delete();
                        $data = ['device_type'=>$request->device_type,'device_token'=>$request->device_token,'user_id'=>$user->id,'app_type'=>'Passenger','device_u_id'=>$request->device_u_id];
                        $device = Device::updateOrCreate(['user_id'=>$user->id],$data);
                    }
                    $user_data = User::getuser($user->id);
//                    $NodeUser = FireBase::storeuser($user->id,$user_data);
                    return response()->json([
                        'new_user'=>false,
                        'user' => $user_data,
                        'token' => $token
                    ], 200, ['Content-Type' => 'application/json; charset=UTF-8',
                        'charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
                }else {
                    return response()->json([
                        'new_user'=>true,
                        'id' => (int)$request->id,
                        'verification_code' => $request->verification_code,
                        'country_code' => $row_code->country_code,
                        'mobile_no' => $row_code->mobile_number

                    ], 200, ['Content-Type' => 'application/json; charset=UTF-8',
                        'charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
                }
            }else{

                $message = "OTP dose not match";
                $error = ['field'=>'otp_not_match','message'=>$message];
                $errors =[$error];
                return response()->json(['errors' => $errors], 401);
            }
        }catch(\Exception $e){
            $message = $e->getMessage();
            $error = ['field'=>'otp_not_match','message'=>$message];
            $errors =[$error];
            return response()->json(['errors' => $errors], 500);
        }
    }

    /**
     * Create user your Identity
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */

    public function yourIdentity(Request $request)
    {
        try {

            Log::info('app.requests', ['request' => $request->all(),'URL'=>$request->url()]);
            $validator = Validator::make($request->all(), [
                'id' => 'required',
//                'email' => 'required',
                'verification_code' => 'required',
                'full_name' => 'required',
                'device_u_id' => 'required',
                'device_type' => 'required',
                'device_token' => 'required',
            ]);
            if ($validator->fails()) {
                $errors = $validator->messages();
                return response()->json(compact('errors'), 401);
            }

            $basenumber = BaseNumber::where(['id'=>$request->id,'verification_code'=>$request->verification_code,'otp_verified'=>1])->first();
            if($basenumber){
                if(User::where(['country_code'=>$basenumber->country_code,'mobile_no'=>$basenumber->mobile_number])->exists()) {

                    $message =  "User already created";
                    $error = ['field'=>'user_exist','message'=>$message];
                    $errors =[$error];
                    return response()->json(['errors' => $errors], 401);

                }else {
                    $user = new User();
                    $user->name = $request->full_name;
                    if ($request->email) {
                        $user->email = $request->email;
                    }
                    $user->country_code = $basenumber->country_code;
                    $user->mobile_no = $basenumber->mobile_number;
//                    $user->panel_mode = '0';
                    $user->user_type = 'user';
                    $user->profile_pic = 'assets/default/user.png';
                    $user->status = 'Active';
                    $user->save();

                    $name = $user->name;
                    $id = $user->id;
                    if ($user->email){
                        Mail::to($request->email)->send(new WelcomeEmail($name,$id));
                    }


                    if(isset($user->user_JWT_Auth_Token) && $user->user_JWT_Auth_Token != null) {
                        $newToken = JWTAuth::manager()->invalidate(new \Tymon\JWTAuth\Token($user->user_JWT_Auth_Token), $forceForever = false);
                    }

                    $token=JWTAuth::fromUser($user);
                    $update_token = User::where('id',$user->id)->update(['user_JWT_Auth_Token'=>$token]);


                    if($request->device_type == 1){ $type = 'Android';}
                    if($request->device_type == 2){ $type = 'iOS';}
                    if(Device::where(['device_type'=>$type,'device_token'=>$request->device_token,'user_id'=>$user->id,'app_type'=>'Passenger','device_u_id'=>$request->device_u_id])->exists()){
//                $deleteDevice = Device::where(['user_id'=>$user->id,'app_type'=>'Passenger'])->delete();
                        $deleteDevice = Device::where(['device_u_id'=>$request->device_u_id,'app_type'=>'Passenger'])->delete();
                        $data = ['device_type'=>$request->device_type,'device_token'=>$request->device_token,'user_id'=>$user->id,'app_type'=>'Passenger','device_u_id'=>$request->device_u_id];
                        $device = Device::updateOrCreate(['user_id'=>$user->id,'app_type'=>'Passenger'],$data);
                    }else{
//                $deleteDevice = Device::where(['user_id'=>$user->id,'app_type'=>'Passenger'])->delete();
                        $deleteDevice = Device::where(['device_u_id'=>$request->device_u_id,'app_type'=>'Passenger'])->delete();
                        $data = ['device_type'=>$request->device_type,'device_token'=>$request->device_token,'user_id'=>$user->id,'app_type'=>'Passenger','device_u_id'=>$request->device_u_id];
                        $device = Device::updateOrCreate(['user_id'=>$user->id,'app_type'=>'Passenger'],$data);
                    }
                    $user_data = User::getuser($user->id);
//                    $NodeUser = FireBase::storeuser($user->id,$user_data);

                    return response()->json([

                        'token' => $token,
                        'user' => $user_data,
                    ], 200, ['Content-Type' => 'application/json; charset=UTF-8',
                        'charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);

                }
            }else{
                $message = "OTP does not match";
                $error = ['field'=>'otp_not_match','message'=>$message];
                $errors =[$error];
                return response()->json(['errors' => $errors], 500);
            }

        }catch(\Exception $e){
            $message = $e->getMessage();
            $error = ['field'=>'user_not_created','message'=>$message];
            $errors =[$error];
            return response()->json(['errors' => $errors], 500);
        }
    }

    /**
     * User edit Profile
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */

    public function editProfile(Request $request)
    {
        try {
            $token = JWTAuth::getToken();
            $user = JWTAuth::toUser($token);
            Log::info('app.requests', ['request' => $request->all(),'URL'=>$request->url()]);
            if(isset($request->profile_pic) && !empty($request->profile_pic) && $request->hasFile('profile_pic')){
                if($user->profile_pic != "assets/default/user.png") {
                    @unlink(public_path() . '/' . $user->profile_pic);
                }
                $mime= $request->profile_pic->getMimeType();
                $image = $request->file('profile_pic');
                $image_name =  preg_replace('/\s+/', '', $image->getClientOriginalName());
                $ImageName = time() .'-'.$image_name;
                $image->move('./assets/user/Passenger/profile_pic/', $ImageName);
                $path_image = 'assets/user/Passenger/profile_pic/'.$ImageName;
                $update = User::where('id', $user->id)->update(['profile_pic' => $path_image]);
            }

            if(isset($request->full_name) && !empty($request->full_name)){
                $update = User::where('id', $user->id)->update(['name' => $request->full_name]);
            }

            if(isset($request->mobile_no) && !empty($request->mobile_no) && isset($request->country_code) && !empty($request->country_code)){


                if(User::where(['mobile_no'=>$request->mobile_no,'country_code'=>$request->country_code])->exists()){
                    $message = "Mobile Number Already exist";
                    $error = ['field'=>'profile_not_edit','message'=>$message];
                    $errors =[$error];
                    return response()->json(['errors' => $errors], 401);
                }else {
                    $otp = rand(100000, 999999);
                    $base_number = BaseNumber::where(['mobile_number' => $user->mobile_no, 'country_code' => $user->country_code])->first();
                    if($base_number) {
                        $base_number_1 = BaseNumber::where(['id' => $base_number->id])->update(['mobile_number' => $request->mobile_no, 'country_code' => $request->country_code, 'full_mobile_number' => $request->country_code . $request->mobile_no, 'verification_code' => $otp]);
                        $update = User::where('id', $user->id)->update(['mobile_no' => $request->mobile_no, 'country_code' => $request->country_code, 'mobile_number_verified' => 0]);
                    }else{

                        $base_number_1 = BaseNumber::create(['mobile_number' => $request->mobile_no, 'country_code' => $request->country_code, 'full_mobile_number' => $request->country_code . $request->mobile_no, 'verification_code' => $otp]);
                        $update = User::where('id', $user->id)->update(['mobile_no' => $request->mobile_no, 'country_code' => $request->country_code, 'mobile_number_verified' => 0]);
                    }
                    $message_sms = "<#> GGO PIN: ".$otp.". Never share this PIN with anyone. GGO will never call you to ask for this.";
                    $user_number = $user->mobile_no;
                    $sendSMS = Utility::sendSMS($message_sms,$user_number);
                }
                if($update){
                    return response()->json([
                        'otp'=> $otp,
                        'id'=>(int)$base_number->id,
                        'mobile_no'=> $request->mobile_no,
                        'country_code'=>$request->country_code
                    ], 200, ['Content-Type' => 'application/json; charset=UTF-8',
                        'charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);

                }else{
                    $message = "Profile not edited";
                    $error = ['field'=>'user_profile_not_edit','message'=>$message];
                    $errors =[$error];
                    return response()->json(['errors' => $errors], 401);
                }
            }
            if(isset($request->email) && !empty($request->email)) {
                if (User::where(['email' => $request->email])->exists()) {
                    $message = "Email already exist";
                    $error = ['field' => 'p_email_already_exist', 'message' => $message];
                    $errors = [$error];
                    return response()->json(['errors' => $errors], 401);
                } else {
                    $messages = [
                        'required' => 'the_field_is_required'
                    ];
                    $validator = Validator::make($request->all(), [
                        'email' => 'required',
                    ], $messages);
                    if ($validator->fails()) {
                        $errors = $validator->messages();
                        return response()->json(compact('errors'), 401);
                    }
                    $update = User::where('id', $user->id)->update(['email' => $request->email]);
                    /*$name = $user->name;
                    $id = $user->id;
                    $socialLinks = BaseAppSocialLinks::all();

                    $header = EmailHeader::where('id', 1)->first();
                    $headerTrans = EmailHeaderTranslation::where(['email_header_id' => 1, 'locale' => $user->locale])->first();

                    $bodyTrans = EmailBodyTranslation::where(['email_body_id' => 1, 'locale' => $user->locale])->first();

                    $footerTrans = EmailFooterTranslation::where(['email_footer_id' => 1, 'locale' => $user->locale])->first();
                    $langtxt = $user->locale;
                    $user_type = "user";
                    Mail::to($request->email)->send(new WelcomeEmail($name, $id, $socialLinks, $header, $headerTrans, $bodyTrans, $footerTrans, $langtxt, $user_type));*/
                }
            }
            /*if(isset($request->password)){
                $check = Auth::guard('web')->attempt([
                    'email' => $user->email,
                    'password' => $request->old_password
                ]);
                if($check) {

                    $update = User::where('id', $user->id)->update(['password' => bcrypt($request->password)]);
                }else{
                    $message = LanguageString::translated()->where('bls_name_key','password_does_not_match')->first()->name;
                    $error = ['field'=>'driver_profile_not_edit','message'=>$message];
                    $errors =[$error];
                    return response()->json(['errors' => $errors], 401);
                }

            }*/

            return response()->json([
                'user' => User::getuser($user->id)
            ], 200, ['Content-Type' => 'application/json; charset=UTF-8',
                'charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);

        }catch(\Exception $e){
            $message = $e->getMessage();
            $error = ['field'=>'error','message'=>$message];
            $errors =[$error];
            return response()->json(['errors' => $errors], 500);
        }
    }

    public function logout(Request $request)
    {
        Log::info('app.requests', ['request' => $request->all()]);
        try {
            $token = JWTAuth::getToken();
            JWTAuth::invalidate($token);
            $message = "User logout successfully!";
            return response()->json(['message'=>$message ], 200);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $error = ['field'=>'error','message'=>$message];
            $errors =[$error];
            return response()->json(['errors' => $errors], 500);
        }

    }

}
