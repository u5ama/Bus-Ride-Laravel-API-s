<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Country;
use App\Http\Resources\CountryResource;
use App\Models\Setting;
use App\Http\Controllers\Controller;

class CountryController extends Controller
{
    public function index()
    {
        $countries = CountryResource::collection(
            Country::where('status', 'Active')->orderBy('country_order', 'ASC')->get()
        );


        if (count($countries) > 0) {

            return response()->json($countries, 200);
        } else {
            return response()->json([
                'message' => Config('languageString.country_not_found')
            ], 422);
        }
    }
}
