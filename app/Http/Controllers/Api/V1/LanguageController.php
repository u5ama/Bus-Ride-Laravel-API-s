<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\LangaugeResource;
use App\Models\Language;

use App\Http\Controllers\Controller;
use App\Models\LanguageString;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class LanguageController extends Controller
{
    public function index()
    {
        $languages = Cache::get('languages');

        if($languages == null){
            $languages = LangaugeResource::collection(Language::where('status', 'Active')->get());
            Cache::forever('languages', $languages);
        }

        return response()->json( $languages,200, ['Content-Type' => 'application/json; charset=UTF-8',
            'charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
    }

    public function updateLocale(Request $request)
    {
        Log::info('app.requests', ['request' => $request->all()]);
        try{
            $user = JWTAuth::parseToken()->authenticate();
            $locale = $request->header('Accept-Language');
            User::where('id',$user->id)->update(['locale'=>$locale]);
            $message = config('languageString.locale_updated');
            return response()->json([
                'message' => $message
            ], 200);
        }catch(\Exception $e){
            $message = LanguageString::translated()->where('bls_name_key','error')->first()->name;
            $error = ['field'=>'language_strings','message'=>$message];
            $errors =[$error];
            return response()->json(['errors' => $errors], 500);
        }
    }
}
