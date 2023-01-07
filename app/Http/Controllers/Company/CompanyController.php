<?php

namespace App\Http\Controllers\Company;

use App\Helpers\ImageUploadHelper;
use App\Models\AssignBuses;
use App\Models\Buses;
use App\Models\Company;
use App\Models\Driver;
use App\Models\RideBooking;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class CompanyController extends Controller
{
    public function index()
    {
        $this->middleware('auth:company');

        $drivers = Driver::where('du_com_id', auth()->guard('company')->user()->id)->count();
        $buses = Buses::where('company_id', auth()->guard('company')->user()->id)->count();
        $assign_buses = AssignBuses::where('company_id', auth()->guard('company')->user()->id)->get();
        if (count($assign_buses) > 0){
            foreach ($assign_buses as $assign_bus){
                $earning = RideBooking::where('bus_id', $assign_bus->bus_id)->get()->sum("total_fare");
            }
        }else{
            $earning = 0;
        }

        return view('company.dashboard.index', compact('drivers', 'buses','earning'));
    }

    public function profile()
    {
        $user = Company::where('id', auth()->guard('company')->user()->id)->first();
        if ($user) {
            return view('company.dashboard.profile', ['user' => $user]);
        } else {
            abort(404);
        }
    }

    public function updateProfile(Request $request)
    {
        $id = $request->input('edit_value');
        $user = Company::where('id', $id)->first();
        $user->name = $request->input('name');
        if ($request->hasFile('image')) {
            $files = $request->file('image');
            $user->image = ImageUploadHelper::imageUpload($files);
        }
        $user->save();

        return response()->json(['success' => true, 'message' => config('languageString.user_updated')]);
    }
}
