<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\AppDefaultImagesResource;
use DB;
use App\Models\AppDefaultImage;
use App\Models\AppTheme;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AppDefaultImageController extends Controller
{
    /**
     * Display a listing of the App Controls.
     * @throws Exception
     */
    public function index(Request $request)
    {
      $device_type = $request->get('device_type');
      if(!empty($device_type)){

      $app_default_image = [];
      $rowtheme = AppTheme::where('status', 'Active')->select('id','theme_name')->first();

      $theme['theme_id'] = $rowtheme->id;
      $theme['theme_name'] = $rowtheme->theme_name;

      $app_default_image = AppDefaultImagesResource::collection(AppDefaultImage::where(['status'=>'Active','app_theme_id'=>$rowtheme->id,'device_type'=>$device_type])->get());


       return response()->json( ['theme'=>$theme,'data' => $app_default_image],200, ['Content-Type' => 'application/json; charset=UTF-8',
                'charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);

      }else{

            $error = ['field'=>'device_type','message'=>'Device type required'];
            $errors =[$error];

           return response()->json( $errors,500, ['Content-Type' => 'application/json; charset=UTF-8',
                'charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
      }
      
    }
}
