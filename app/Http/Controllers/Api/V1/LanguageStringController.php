<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\LanguageStringResource;
use DB;
use App\Models\LanguageString;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LanguageStringController extends Controller
{
    public function index()
    {

        try{
            $language_strings = [];

            $language_strings = LanguageStringResource::collection(
                LanguageString::translated()->whereHas('language_screen', function ($query) {
                    $query->where('app_or_panel', 1);
                })->orderBy('id','DESC')->get()
            );
            return response()->json( $language_strings,200, ['Content-Type' => 'application/json; charset=UTF-8',
                'charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
        }catch(\Exception $e){
            $message = LanguageString::translated()->where('name_key','error')->first()->name;
            $error = ['field'=>'language_strings','message'=>$message];
            $errors =[$error];
            return response()->json(['errors' => $errors], 500);
        }
    }
}
