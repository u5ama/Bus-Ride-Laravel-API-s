<?php

namespace App\Http\Controllers\Company;

use App\Models\BaseNumber;
use App\Models\Company;
use App\Models\Driver;
use App\Models\DriverProfile;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;


class DriverController extends Controller
{
    /**
     * Display a listing of the Driver.
     *
     * @param Request $request
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $company = Company::where('id', auth()->guard('company')->user()->id)->first();
        $drivers = Driver::with('DriverProfile')->where('du_com_id', auth()->guard('company')->user()->id)->get();

        return view('company.drivers.index', ['company' => $company, 'drivers' => $drivers]);
    }

    /**
     * Show the form for creating a new Driver.
     *
     * @return Factory|View
     */
    public function create()
    {
        return view('company.drivers.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $id = $request->input('edit_value');
        if ($id == NULL) {
            $validator_array = [
                'du_full_name' => 'required',
                'du_mobile_number' => 'required'
            ];

            $validator = Validator::make($request->all(), $validator_array);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
            }

            $driver = new Driver();

            if ($request->hasFile('du_profile_pic')) {
                $mime = $request->du_profile_pic->getMimeType();
                $pic = $request->file('du_profile_pic');
                $pic_name = preg_replace('/\s+/', '', $pic->getClientOriginalName());
                $picName = time() . '-' . $pic_name;
                $pic->move('./assets/user/driver/', $picName);
                $compic = 'assets/user/driver/' . $picName;
                $driver->du_profile_pic = $compic;
            }
            /*if(BaseNumber::where(["country_code" => $request->country_code,
            "mobile_number" => $request->du_mobile_number])->exists()){
                $basenumber =   BaseNumber::where(["country_code" => $request->country_code,
                    "mobile_number" => $request->du_mobile_number])->first();
            }else {
                $basenumber = new BaseNumber();
            }
            $basenumber->country_code = $request->country_code;
            $basenumber->mobile_number = $request->du_mobile_number;
            $basenumber->full_mobile_number = $request->du_full_mobile_number;
            $basenumber->otp_api_response = '03 Message (M) is mandatory';
            $basenumber->otp_api_response_status = '1';
            $basenumber->verification_code = $request->input('du_otp_manual');;
            $basenumber->otp_verified = 1;
            $basenumber->save();*/

            $driver->du_full_name = $request->input('du_full_name');
            $driver->du_mobile_number = $request->input('du_mobile_number');
            $driver->du_full_mobile_number = $request->input('du_full_mobile_number');
            $driver->du_user_name = $request->input('du_user_name');
            $driver->du_country_code = $request->input('country_code');
            $driver->email = $request->input('email');
            $driver->password = Hash::make($request->input('password'));
            $driver->du_com_id = auth()->guard('company')->user()->id;
            $driver->save();

            $driver_profile = new DriverProfile();
            $driver_profile->car_registration = $request->input('car_registration');
            $driver_profile->dp_date_registration = $request->input('dp_date_registration');
            $driver_profile->dp_user_id = $driver->id;
            $driver_profile->save();

//            $driverObj = Driver::find($driver->id);

//            $name = $driverObj->du_full_name;
//            $id = $driverObj->id;
//            $socialLinks = BaseAppSocialLinks::all();
//            $header = EmailHeader::where('id',7)->first();
//            $headerTrans = EmailHeaderTranslation::where(['email_header_id' => 7, 'locale' => $driverObj->locale])->first();
//
//            $bodyTrans = EmailBodyTranslation::where(['email_body_id'=> 7, 'locale' => $driverObj->locale])->first();
//
//            $footerTrans = EmailFooterTranslation::where(['email_footer_id'=> 7,'locale' => $driverObj->locale])->first();
//            $user_type = 'driver';
//            $langtxt = $driverObj->locale;
//            Mail::to($driverObj->email)->send(new WelcomeDriverEmail($name,$id,$socialLinks,$header,$headerTrans,$bodyTrans,$footerTrans,$langtxt,$user_type));


            return response()->json(['success' => true, 'message' => trans('adminMessages.driver_inserted')]);
        } else {
            $driver = Driver::find($id);
            if ($request->hasFile('du_profile_pic')) {
                $mime = $request->du_profile_pic->getMimeType();
                $pic = $request->file('du_profile_pic');
                $pic_name = preg_replace('/\s+/', '', $pic->getClientOriginalName());
                $picName = time() . '-' . $pic_name;
                $pic->move('./assets/user/driver/', $picName);
                $compic = 'assets/user/driver/' . $picName;
                $driver->du_profile_pic = $compic;
            }
            if(BaseNumber::where(["country_code" => $driver->country_code,
                "mobile_number" => $driver->du_mobile_number])->exists()){
                $basenumber =   BaseNumber::where(["country_code" => $request->country_code,
                    "mobile_number" => $request->du_mobile_number])->first();
            }else {
                $basenumber = new BaseNumber();
            }
            $basenumber->country_code = $driver->du_country_code;
            $basenumber->mobile_number = $driver->du_mobile_number;
            $basenumber->full_mobile_number = $driver->du_full_mobile_number;
            $basenumber->otp_api_response = '03 Message (M) is mandatory';
            $basenumber->otp_api_response_status = '1';
            $basenumber->otp_verified = 1;
            $basenumber->save();

            $driver->du_full_name = $request->input('du_full_name');
            $driver->du_mobile_number = $request->input('du_mobile_number');
            $driver->du_full_mobile_number = $request->input('du_full_mobile_number');
            $driver->du_user_name = $request->input('du_user_name');
            $driver->du_country_code = $request->input('country_code');
            $driver->email = $request->input('email');
            $driver->du_com_id = auth()->guard('company')->user()->id;

            if (!empty($request->password)) {
                $driver->password = Hash::make($request->input('password'));
            }
            $driver->save();

            $driver_profile = new DriverProfile();
            $driver_profile->car_registration = $request->input('car_registration');
            $driver_profile->dp_date_registration = $request->input('dp_date_registration');
            $driver_profile->dp_user_id = $driver->id;
            $driver_profile->save();
            return response()->json(['success' => true, 'message' => trans('adminMessages.driver_updated'), 'company_id' => auth()->guard('company')->user()->id]);
        }
    }


    /**
     * Display the specified Driver.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified Driver.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $driver = Driver::find($id);
        if ($driver) {
            $driver_profile = DriverProfile::where('dp_user_id', $driver->id)->first();
            return view('company.drivers.edit', ['driver' => $driver, 'company_id' => auth()->guard('company')->user()->id, 'driver_profile' => $driver_profile]);
        } else {
            abort(404);
        }
    }

    /**
     * Update the specified Driver in storage.
     *
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified Driver from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        return response()->json(['success' => true, 'message' => trans('adminMessages.country_deleted')]);
    }

    /**
     * Change the status for Driver
     * @param $id
     * @param $status
     * @return JsonResponse
     */
    public function status($id, $status)
    {
        $driver = Driver::where('id', $id)->update(['du_driver_status' => $status]);
        $driver = Driver::where('id', $id)->first();

        if ($driver->du_driver_status == 'driver_status_when_approved'){

//            $driver_name = $driver->du_full_name;
//            $socialLinks = BaseAppSocialLinks::all();
//            $header = EmailHeader::where('id',8)->first();
//            $headerTrans = EmailHeaderTranslation::where(['email_header_id' => 8, 'locale' => $driver->locale])->first();
//
//            $bodyTrans = EmailBodyTranslation::where(['email_body_id'=> 8, 'locale' => $driver->locale])->first();
//
//            $footerTrans = EmailFooterTranslation::where(['email_footer_id'=> 8,'locale' => $driver->locale])->first();
//            $langtxt = $driver->locale;
//            $user_type = "driver";
//
//            Mail::to($driver->email)->send(new DriverStatusEmail($driver_name,$driver->id,$socialLinks,$header,$headerTrans,$bodyTrans,$footerTrans,$langtxt,$user_type));
        }
        return response()->json(['success' => true, 'message' => trans('adminMessages.driver_status_updated')]);
    }

    /** Update Driver Status of Company
     * @param Request $request
     * @param $id
     * @param $status
     * @param $company_id
     * @return JsonResponse
     */
    public function updateDriverStatus(Request $request, $id, $status, $company_id)
    {
        $driver = Driver::find($id);
        if ($status == "driver_status_when_approved") {
//            App::setLocale($driver->locale);
//            $title =  LanguageString::translated()->where('bls_name_key', 'driver_status_when_approved_admin')->first()->name;
//            $body = $driver->du_full_name . " " . LanguageString::translated()->where('bls_name_key', 'driver_status_when_approved_admin_desc')->first()->name;
//            App::setLocale('en');
//            $driver = Driver::where('id', $id)->first();

//                $driver_name = $driver->du_full_name;
//                $socialLinks = BaseAppSocialLinks::all();
//                $header = EmailHeader::where('id',8)->first();
//                $headerTrans = EmailHeaderTranslation::where(['email_header_id' => 8, 'locale' => $driver->locale])->first();
//
//                $bodyTrans = EmailBodyTranslation::where(['email_body_id'=> 8, 'locale' => $driver->locale])->first();
//
//                $footerTrans = EmailFooterTranslation::where(['email_footer_id'=> 8,'locale' => $driver->locale])->first();
//                $langtxt = $driver->locale;
//                $user_type = "driver";
//
//                Mail::to($driver->email)->send(new DriverStatusEmail($driver_name,$driver->id,$socialLinks,$header,$headerTrans,$bodyTrans,$footerTrans,$langtxt,$user_type));
        }
//        if ($status == "driver_status_when_block") {
//            App::setLocale($driver->locale);
//            $title =  LanguageString::translated()->where('bls_name_key', 'driver_status_when_block_admin')->first()->name;
//            $body = $driver->du_full_name . " " . LanguageString::translated()->where('bls_name_key', 'driver_status_when_block_admin_desc')->first()->name;
//            App::setLocale('en');
//        }
//        if ($status == "driver_status_when_pending") {
//            App::setLocale($driver->locale);
//            $title =  LanguageString::translated()->where('bls_name_key', 'driver_status_when_pending_admin')->first()->name;
//            $body = $driver->du_full_name . " " . LanguageString::translated()->where('bls_name_key', 'driver_status_when_pending_admin_desc')->first()->name;
//            App::setLocale('en');
//        }

//        $tokensand = Device::where(['user_id' => $driver->id, 'device_type' => "Android", 'app_type' => 'Driver'])->pluck('device_token')->toArray();
//        $tokensios = Device::where(['user_id' => $driver->id, 'device_type' => "iOS", 'app_type' => 'Driver'])->pluck('device_token')->toArray();

//        $sound = 'default';
//        $action = 'Admin';
//        $type = 'pushNotification';
//        $notifications = Notification::sendnotifications($tokensios, $tokensand, $title, $body, $sound, $action, $id, $type, $driver->id, Auth::guard('admin')->user()->id, null, $drivers = 1);
//
//        $noti_data = [
//            'ban_sender_id' => Auth::guard('admin')->user()->id,
//            'ban_recipient_id' => $id,
//            'ban_sender_type' => 'Admin',
//            'ban_recipient_type' => 'Driver',
//            'ban_type_of_notification' => $type,
//            'ban_title_text' => $title,
//            'ban_body_text' => $body,
//            'ban_activity' => $action,
//            'ban_notifiable_type' => 'App\Admin',
//            'ban_notifiable_id' => $id,
//            'ban_notification_status' => $notifications,
//            'ban_created_at' => now(),
//            'ban_updated_at' => now()
//        ];
//        BaseAppNotification::create($noti_data);
        Driver::where('id', $id)->update(['du_driver_status' => $status]);
        return response()->json(['success' => true, 'message' => 'Driver status is successfully Updated']);
    }
}
