<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\ExperienceRequest;
use App\Http\Resources\EmployeesRangeResource;
use App\Http\Resources\UserJobExperienceResource;
use App\Models\EmployeesRange;
use App\Models\Experience;
use App\Models\LanguageString;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class ExperienceController extends Controller
{
    public function index(ExperienceRequest $request)
    {
        Log::info('app.requests', ['request' => $request->all()]);
        try{
            $user = JWTAuth::parseToken()->authenticate();
            $locale = $request->header('Accept-Language');
            $validated = $request->validated();

            if($request->is_currently_working == 1){
                $exp_to = null;
            }else{
                $exp_to = Carbon::parse($request->exp_to);
            }
            $expData = [
              'user_id'=>$user->id,
              'title'=>$validated['title'],
                'location'=>$validated['location'],
                'company_label'=>$validated['company_label'],
                'location_lat'=>$request->location_lat,
                'location_lng'=>$request->location_lng,
                'exp_to'=>$exp_to,
                'is_currently_working'=>$request->is_currently_working,
                'exp_from'=>Carbon::parse($validated['exp_from']),
                'about_your_certification'=>$validated['about_your_certification']
            ];
            $expObj = Experience::create($expData);
            $user_experience = UserJobExperienceResource::collection(Experience::where('user_id',$user->id)->get());
            return response()->json($user_experience, 200,['Content-Type' => 'application/json; charset=UTF-8',
                'charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
        }catch(\Exception $e){
            $message = LanguageString::translated()->where('name_key','error')->first()->name;
            $error = ['field'=>'language_strings','message'=>$message];
            $errors =[$error];
            return response()->json(['errors' => $errors], 500);
        }
    }public function editExperience(Request $request)
    {
        Log::info('app.requests', ['request' => $request->all()]);
        try{
            $user = JWTAuth::parseToken()->authenticate();
            $locale = $request->header('Accept-Language');
            $title = $request->title;
                    $location = $request->location;
                    $is_currently_working = $request->is_currently_working;
                    $company_label = $request->company_label;
                    $location_lat = $request->location_lat;
                    $location_lng = $request->location_lng;
                    if(isset($request->exp_to) && $request->exp_to != null) {
                        $exp_to = Carbon::parse($request->exp_to);
                    }
            if(isset($request->exp_from) && $request->exp_from != null) {
                $exp_from = Carbon::parse($request->exp_from);
            }
                    $about_your_certification = $request->about_your_certification;

            if(Experience::where(['id'=>$request->experience_id,'user_id'=>$user->id])->exists()) {
                $experience = Experience::where(['id'=>$request->experience_id,'user_id'=>$user->id])->first();
                    $experience->user_id = $user->id;
                    if(isset($title) && $title != null) {
                        $experience->title = $title;
                        }
                    if(isset($location) && $location != null) {
                        $experience->location = $location;
                        }
                    if(isset($location) && $location != null) {
                        $experience->location = $location;
                        }
                    if(isset($company_label) && $company_label != null) {
                        $experience->company_label = $company_label;
                        }
                    if(isset($location_lat) && $location_lat != null) {
                        $experience->location_lat = $location_lat;
                        }
                    if(isset($location_lng) && $location_lng != null) {
                        $experience->location_lng = $location_lng;
                        }
                    if(isset($exp_to) && $exp_to != null) {
                        $experience->exp_to = $exp_to;
                        }
                    if(isset($exp_from) && $exp_from != null) {
                        $experience->exp_from = $exp_from;
                        }
                    if(isset($exp_from) && $exp_from != null) {
                        $experience->exp_from = $exp_from;
                        }if(isset($is_currently_working)) {
                    $experience->is_currently_working=$is_currently_working;
                        }
                    if(isset($about_your_certification) && $about_your_certification != null) {
                        $experience->about_your_certification = $about_your_certification;
                        }
                $experience->save();
                $user_experience = UserJobExperienceResource::collection(Experience::where('user_id', $user->id)->get());

            return response()->json($user_experience, 200,['Content-Type' => 'application/json; charset=UTF-8',
                'charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
            }else{
                $message = LanguageString::translated()->where('name_key','invalid_experience_id')->first()->name;
                $error = ['field'=>'language_strings','message'=>$message];
                $errors =[$error];
                return response()->json(['errors' => $errors], 403);
            }
        }catch(\Exception $e){
            $message = LanguageString::translated()->where('name_key','error')->first()->name;
            $error = ['field'=>'language_strings','message'=>$message];
            $errors =[$error];
            return response()->json(['errors' => $errors], 500);
        }
    }
    public function getMyExperience(Request $request)
    {
        Log::info('app.requests', ['request' => $request->all()]);
        try{
            $user = JWTAuth::parseToken()->authenticate();
            $locale = $request->header('Accept-Language');
            $user_experience = UserJobExperienceResource::collection(Experience::where('user_id',$user->id)->get());
            return response()->json($user_experience, 200,['Content-Type' => 'application/json; charset=UTF-8',
                'charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
        }catch(\Exception $e){
            $message = LanguageString::translated()->where('name_key','error')->first()->name;
            $error = ['field'=>'language_strings','message'=>$message];
            $errors =[$error];
            return response()->json(['errors' => $errors], 500);
        }
    } public function ExperienceDelete(Request $request,$id)
    {
        Log::info('app.requests', ['request' => $request->all()]);
        try{
            $user = JWTAuth::parseToken()->authenticate();
            $locale = $request->header('Accept-Language');
            if(Experience::where(['id'=>$id,'user_id'=>$user->id])->exists()) {
                Experience::where(['id'=>$id,'user_id'=>$user->id])->delete();
                $message = LanguageString::translated()->where('name_key','experience_deleted_successfully')->first()->name;
            }else{
                $message = LanguageString::translated()->where('name_key','not_allowed')->first()->name;
            }
            return response()->json(['message'=>$message], 200,['Content-Type' => 'application/json; charset=UTF-8',
                'charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
        }catch(\Exception $e){
            $message = LanguageString::translated()->where('name_key','error')->first()->name;
            $error = ['field'=>'language_strings','message'=>$message];
            $errors =[$error];
            return response()->json(['errors' => $errors], 500);
        }
    }
}
