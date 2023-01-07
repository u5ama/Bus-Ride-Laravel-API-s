<?php

namespace App\Http\Resources;

use App\BaseAppNotification;
use App\CustomerInvoice;
use App\DriverAccount;
use App\LanguageString;
use App\Models\AssignBuses;
use App\Models\Buses;
use App\RideBookingSchedule;
use App\TransportMake;
use App\TransportModel;
use App\TransportType;
use App\WebPage;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class GetMyDriverProfile extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'company_id'=>$this->du_com_id,
            'name'=>$this->du_full_name,
            'user_name'=>$this->du_user_name,
            'email'=>$this->email,
            'country_code'=>$this->du_country_code,
            'mobile_no'=>$this->du_mobile_number,
            'full_mobile_number'=>$this->du_full_mobile_number,
            'locale'=>$this->locale,
            'profile_pic'=>$this->du_profile_pic,
            'is_driver'=>$this->du_is_driver,
            'email_verified'=>$this->is_email_verified,
            'mobile_number_verified'=>$this->du_mobile_number_verified,
            'status'=>$this->du_driver_status,
            'user_type'=>'driver',
            'is_active'=>$this->du_is_active,
            'lat'=>(isset($this->DriverCurrentLocation->dcl_lat) && $this->DriverCurrentLocation->dcl_lat != null) ? $this->DriverCurrentLocation->dcl_lat : "",
            'long'=>(isset($this->DriverCurrentLocation->dcl_long) && $this->DriverCurrentLocation->dcl_long != null) ? $this->DriverCurrentLocation->dcl_long : "",
            'app_active'=>(isset($this->DriverCurrentLocation->dcl_app_active) && $this->DriverCurrentLocation->dcl_app_active != null) ? $this->DriverCurrentLocation->dcl_app_active : 0,
            'driver_approval_status'=>(isset($this->du_driver_status) && $this->du_driver_status != "driver_status_when_approved") ? false : true,
        ];
    }
}
