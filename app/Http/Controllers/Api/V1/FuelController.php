<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Fuel;
use App\Http\Resources\FuelResource;
use App\Http\Controllers\Controller;

class FuelController extends Controller
{
    public function index()
    {
        $fuels = FuelResource::collection(
            Fuel::select('fuels.*')->get()
        );


        if(count($fuels) > 0){

            return response()->json(['data' => $fuels], 200);
        } else{
            return response()->json([
                'message' => Config('languageString.fuel_not_found'),
            ], 422);
        }
    }
}
