<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Body;
use App\Http\Resources\BodyResource;
use App\Http\Controllers\Controller;
use App\Models\CarModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BodyController extends Controller
{
    public function index(Request $request)
    {
        $validator_array = [
            'vehicle_type_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $validator_array);
        if($validator->fails()){
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }
        $query = Body::where('vehicle_type_id',$request->input('vehicle_type_id'))->select('bodies.*')->get();
        $bodies = BodyResource::collection($query);

        if(count($bodies) > 0){

            return response()->json(['data' => $bodies], 200);
        } else{
            return response()->json([
                'message' => Config('languageString.body_not_found'),
            ], 422);
        }
    }
}
