<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoriesResource;
use App\Http\Resources\OnBoardingResource;
use App\Models\Category;
use App\Models\FurasJourney;
use App\Models\LanguageString;
use App\Models\OnBoarding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class OnBoardingController extends Controller
{
    public function index(Request $request)
    {
        Log::info('app.requests', ['request' => $request->all()]);
        try{

            $locale = $request->header('Accept-Language');
            $OnBoarding1 = FurasJourney::translated()->get();
            $OnBoarding = OnBoardingResource::collection($OnBoarding1);
            if(isset($OnBoarding[0])){
                $OnBoardingobj = $OnBoarding[0];
            }else{
                $OnBoardingobj = (object)[];
            }
            return response()->json($OnBoardingobj, 200,['Content-Type' => 'application/json; charset=UTF-8',
                'charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
        }catch(\Exception $e){
            $message = LanguageString::translated()->where('name_key','error')->first()->name;
            $error = ['field'=>'language_strings','message'=>$message];
            $errors =[$error];
            return response()->json(['errors' => $errors], 500);
        }
    }

}
