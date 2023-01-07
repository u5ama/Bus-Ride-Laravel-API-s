<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\API\UserAddressStoreRequest;
use App\Http\Resources\UserAddressResource;
use App\Models\UserAddress;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;

class AddressController extends Controller
{

    public function index(): \Illuminate\Http\JsonResponse
    {
        $user = JWTAuth::parseToken()->authenticate();
        $query = UserAddress::where('user_id', $user->id)->get();
        $addresses = UserAddressResource::collection($query);
        if(count($addresses) > 0){
            return response()->json(['data' => $addresses]);
        } else{
            return response()->json(['message' => config('languageString.no_address_found')]);
        }
    }


    public function store(UserAddressStoreRequest $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validated();
        $user = JWTAuth::parseToken()->authenticate();
        UserAddress::create([
            'user_id'   => $user->id,
            'address'   => $validated['address'],
            'latitude'  => $validated['latitude'],
            'longitude' => $validated['longitude'],
        ]);

        return response()->json(['message' => config('languageString.address_added')]);
    }

    public function destroy($id)
    {
        $vehicle = UserAddress::where('id', $id)->first();
        if($vehicle){
            UserAddress::where('id', $id)->delete();
            return response()->json(['message' => config('languageString.address_deleted')]);
        } else{
            return response()->json(['message' => config('languageString.no_address_found')]);
        }
    }
}
