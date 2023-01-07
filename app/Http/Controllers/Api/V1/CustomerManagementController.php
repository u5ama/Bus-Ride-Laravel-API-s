<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\CustomerWallet;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;

class CustomerManagementController extends Controller
{
    public function addCustomerWallet(Request $request){
        try {
            Log::info('app.requests', ['request' => $request->all(),'URL'=>$request->url()]);
            $token = JWTAuth::getToken();
            $user = JWTAuth::toUser($token);

            $wallet = CustomerWallet::updateOrCreate([
                'user_id' => $user->id
            ],[
                'current_balance' => $request->current_balance,
            ]);
            return response()->json(['success' => true,'wallet'=>$wallet], 200);
        }
        catch(\Exception $e){
            $message = $e->getMessage();
            $error = ['field'=>'customer_data','message'=>$message];
            $errors =[$error];
            return response()->json(['success'=>false,'code'=>'500','errors' => $errors], 500);
        }
    }

    public function getCustomerWallet(Request $request){
        try {
            Log::info('app.requests', ['request' => $request->all(),'URL'=>$request->url()]);
            $token = JWTAuth::getToken();
            $user = JWTAuth::toUser($token);

            $wallet = CustomerWallet::where('user_id', $user->id)->first();
            return response()->json(['success' => true,'wallet'=>$wallet], 200);
        }
        catch(\Exception $e){
            $message = $e->getMessage();
            $error = ['field'=>'customer_data','message'=>$message];
            $errors =[$error];
            return response()->json(['success'=>false,'code'=>'500','errors' => $errors], 500);
        }
    }

}
