<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\EmergencyServiceResource;

use App\Http\Controllers\Controller;
use App\Models\EmergencyService;
use Illuminate\Http\Request;

class EmergencyServiceController extends Controller
{
    public function index(Request $request)
    {

        $query = EmergencyService::all();
        $emergencyServices = EmergencyServiceResource::collection($query);
        if(count($emergencyServices) > 0){

            return response()->json(['data' => $emergencyServices], 200);
        } else{
            return response()->json([
                'message' => Config('languageString.no_data_found'),
            ], 422);
        }
    }
}
