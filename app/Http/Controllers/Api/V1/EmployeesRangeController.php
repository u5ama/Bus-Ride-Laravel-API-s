<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeesRangeResource;
use App\Http\Resources\TypeOfBusinessResource;
use App\Models\EmployeesRange;
use App\Models\LanguageString;
use App\Models\TypeOfBusiness;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class EmployeesRangeController extends Controller
{
    public function index(Request $request)
    {
        Log::info('app.requests', ['request' => $request->all()]);
        try{
            $user = JWTAuth::parseToken()->authenticate();
            $locale = $request->header('Accept-Language');
            $employeesRange1 = EmployeesRange::all();
            $employeesRange = EmployeesRangeResource::collection($employeesRange1);
            return response()->json($employeesRange, 200,['Content-Type' => 'application/json; charset=UTF-8',
                'charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
        }catch(\Exception $e){
            $message = LanguageString::translated()->where('name_key','error')->first()->name;
            $error = ['field'=>'language_strings','message'=>$message];
            $errors =[$error];
            return response()->json(['errors' => $errors], 500);
        }
    }
}
