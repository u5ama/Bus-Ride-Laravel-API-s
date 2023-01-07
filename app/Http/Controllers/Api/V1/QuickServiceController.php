<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuickServiceResource;
use App\Models\QuickService;
use Illuminate\Http\Request;

class QuickServiceController extends Controller
{
    public function index(Request $request)
    {

        $query = QuickService::all();
        $quickServices = QuickServiceResource::collection($query);
        if(count($quickServices) > 0){

            return response()->json(['data' => $quickServices], 200);
        } else{
            return response()->json([
                'message' => Config('languageString.no_data_found'),
            ], 422);
        }
    }
}
