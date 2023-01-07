<?php

namespace App\Http\Controllers\Company;

use App\Mail\CompanyWelcomeEmail;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Redirect;
class CompanyLoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */


    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'company/company';

    public function __construct()
    {
      $this->middleware('guest:company')->except('logout');
    }


    /**
     * @return \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
     *
     */
    public function guard()
    {
      return auth()->guard('company');
    }

    public function showLoginForm()
    {

//        if(auth()->guard('company')->check() && auth()->guard('company')->user()->user_type == 'company'){
//            return redirect()->away('https://app.ridewhipp.com/company/company');
//        }else {
            return view('company.auth.login');
//        }
    }

    public function register()
    {
       return view('company.auth.register');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()
                ->route('company.auth.login')
                ->withErrors($validator)
                ->withInput();

        }

        $user = Company::where('email', $request->input('email'))
            ->where('com_status', 5)
            ->first();

        if (!$user) {
            $user = Company::where('email', $request->input('email'))
                ->first();
            if (!$user){
                return redirect()
                    ->route('company.login')
                    ->withErrors(['email' => ['Your email is not registered']])
                    ->withInput();
            }
            if ($user->com_status == 0) {
                $message = "Inactive";
            }elseif ($user->com_status == 1){
                $message = "Temporary Inactive";
            }elseif ($user->com_status == 2){
                $message = "Temporary Block";
            }elseif ($user->com_status == 3){
                $message = "Permanent Block";
            }elseif ($user->com_status == 4){
                $message = "Pending for Approval";
            }
            return redirect()
                ->route('company.login')
                ->withErrors(['email' => ['Your account is '.$message]])
                ->withInput();
        }

        if (!Hash::check($request->input('password'), $user->password)) {
            return redirect()
                ->route('company.login')
                ->withErrors(['password' => ['Invalid password']])
                ->withInput();
        } else {
            Auth::guard('company')->login($user);
            return redirect()->route('company.company');
//            return redirect()->away('https://app.ridewhipp.com/company/company');
        }
    }

    public function registerCompany(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'email' => 'required|unique:companies',
            'password' => 'required|min:5|max:16',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator->errors())
                ->withInput($request->input());
        }

        $comlogo = '';
        if ($request->hasFile('com_logo')){
            $mime= $request->com_logo->getMimeType();
            $logo = $request->file('com_logo');
            $logo_name =  preg_replace('/\s+/', '', $logo->getClientOriginalName());
            $logoName = time() .'-'.$logo_name;
            $logo->move('./assets/company/logo/', $logoName);
            $comlogo = 'assets/company/logo/'.$logoName;
        }


        $company = Company::create([
            'com_name' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'com_status' =>4,
            'com_radius' =>1,
            'com_created_by' =>1,
            'com_updated_by'=>1,
        ]);

//        $socialLinks = BaseAppSocialLinks::all();
//
        $name = $request->com_name;
        $id = $company->id;

        Mail::to($request->email)->send(new CompanyWelcomeEmail($id,$name));

        return redirect()->route('company.success');
    }

    public function successMessage(){
        return view('company.auth.registerSuccess');
    }

    public function logout(){
        auth()->guard('company')->logout();
        Session::flush();
        return redirect('/company/login');
    }
}
