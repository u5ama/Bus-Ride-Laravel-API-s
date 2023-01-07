<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\API\UserVehicleStoreRequest;
use App\Http\Requests\API\UserVehicleFilterRequest;
use App\Http\Resources\UserVehicleResource;
use App\Models\UserVehicle;
use App\Models\Vehicle;
use App\Models\VehicleType;
use App\Http\Resources\VehicleTypeResource;
use App\Http\Controllers\Controller;
use http\Env\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class VehicleController extends Controller
{

    public function index(): \Illuminate\Http\JsonResponse
    {
        $user = JWTAuth::parseToken()->authenticate();
        $query = UserVehicle::where('user_id', $user->id)->get();
        $vehicles = UserVehicleResource::collection($query);
        if (count($vehicles) > 0) {
            return response()->json(['data' => $vehicles]);
        } else {
            return response()->json(['message' => config('languageString.no_vehicle_found')]);
        }
    }

    public function vehicleType(): \Illuminate\Http\JsonResponse
    {
        $vehicleTypes = VehicleTypeResource::collection(VehicleType::all());
        return response()->json([
            'data' => $vehicleTypes,
        ]);
    }

    public function store( UserVehicleStoreRequest $request ): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validated();
        $user = JWTAuth::parseToken()->authenticate();
        $userVehicle = UserVehicle::create([
            'user_id'         => $user->id,
            'name'            => $validated['name'],
            'vehicle_type_id' => $validated['vehicle_type_id'],
            'brand_id'        => $validated['brand_id'],
            'car_model_id'    => $validated['car_model_id'],
            'model_year_id'   => $validated['model_year_id'],
            'body_id'         => $request['body_id'],
            'engine_id'       => $request['engine_id'],
            'fuel_id'         => $validated['fuel_id'],
            'is_filter'       => $request['is_filter'] == null ? "no" : $request['is_filter'],
        ]);

        if ($userVehicle) {
            return response()->json(['message' => config('languageString.vehicle_created')]);
        } else {
            return response()->json(['message' => config('languageString.something_went_wrong')]);
        }
    }

    public function addVehicleFilter( UserVehicleFilterRequest $request ): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validated();
        $user = JWTAuth::parseToken()->authenticate();

        UserVehicle::where('user_id', $user->id)->update([
            'is_filter' => 'no'
        ]);
        $vehicle_id=explode(",",$validated['vehicle_id']);
        foreach ($vehicle_id as $value){
            UserVehicle::where('id', $value)->update([
                'is_filter' => 'yes'
            ]);
        }


        return response()->json(['message' => config('languageString.vehicle_filter_added_successfully')]);
    }

    public function destroy( $id )
    {
        $vehicle = UserVehicle::where('id', $id)->first();
        if ($vehicle) {
            UserVehicle::where('id', $id)->delete();
            return response()->json(['message' => config('languageString.vehicle_deleted')]);
        } else {
            return response()->json(['message' => config('languageString.no_vehicle_found')]);
        }
    }
}
