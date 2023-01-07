<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppNavSettingResource;
use App\Models\AppNavSetting;
use App\Models\LanguageString;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class AppNavSettingController extends Controller
{
    public function index(Request $request)
    {
        Log::info('app.requests', ['request' => $request->all()]);
        try{
            $user = JWTAuth::parseToken()->authenticate();
            $locale = $request->header('Accept-Language');
            if($user->is_guest == 1){
                $typeOfBusiness1 = AppNavSetting::translated()->where('app_nav_settings.is_guest', 1)->orderBy('app_nav_settings.order_by','asc')->get();
                $typeOfBusiness = AppNavSettingResource::collection($typeOfBusiness1);
            }else {
                $typeOfBusiness1 = AppNavSetting::translated()->where('app_nav_settings.app_mode', $request->app_mode)->where('app_nav_settings.is_guest', 0)->orderBy('app_nav_settings.order_by','asc')->get();
                $typeOfBusiness = AppNavSettingResource::collection($typeOfBusiness1);
            }
            return response()->json($typeOfBusiness, 200,['Content-Type' => 'application/json; charset=UTF-8',
                'charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
        }catch(\Exception $e){
            $message = LanguageString::translated()->where('name_key','error')->first()->name;
            $error = ['field'=>'language_strings','message'=>$message];
            $errors =[$error];
            return response()->json(['errors' => $errors], 500);
        }
    }
}
