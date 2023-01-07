<?php

namespace App\Http\Controllers\Company;

use App\Helpers\ImageUploadHelper;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use App\Models\Admin;
use App\Models\Brand;
use App\Models\UserVehicle;
use App\Models\CarModel;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    public function index()
    {
        $this->middleware('auth:company');

        $users = User::count();

        return view('company.dashboard.index', [
            'users' => $users,
        ]);
    }

    public function changeThemes($id)
    {
        Session::put('panel_mode', $id);
        Company::where('id', auth()->guard('company')->user()->id)->update(['com_panel_mode' => $id]);
        return redirect()->route('company.company');
    }

    public function changeThemesMode($local)
    {
        Session::put('locale', $local);

        Company::where('id', auth()->guard('company')->user()->id)->update(['com_locale' => $local]);
        return redirect()->route('company.company');
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

    public function editProfile(Request $request)
    {
        $id = $request->input('edit_value');
        $user = Company::where('id', $id)->first();
        $user->com_name = $request->input('name');
        if ($request->hasFile('image')) {
            $files = $request->file('image');
            $user->com_logo = ImageUploadHelper::imageUpload($files);
        }
        $user->save();

        return response()->json(['success' => true, 'message' => config('languageString.user_updated')]);
    }


}
