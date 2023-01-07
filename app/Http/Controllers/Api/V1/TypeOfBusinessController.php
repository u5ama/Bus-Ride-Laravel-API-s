<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\appSliderResource;
use App\Http\Resources\TypeOfBusinessResource;
use App\Models\AppSlider;
use App\Models\LanguageString;
use App\Models\TypeOfBusiness;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class TypeOfBusinessController extends Controller
{
    public function index(Request $request)
    {
        Log::info('app.requests', ['request' => $request->all()]);
        try{
            $user = JWTAuth::parseToken()->authenticate();
            $locale = $request->header('Accept-Language');
            $typeOfBusiness1 = TypeOfBusiness::translated()->get();
            $typeOfBusiness = TypeOfBusinessResource::collection($typeOfBusiness1);
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
