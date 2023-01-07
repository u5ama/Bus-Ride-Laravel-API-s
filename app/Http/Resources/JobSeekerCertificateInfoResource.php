<?php

namespace App\Http\Resources;

use App\Models\LanguageString;
use Illuminate\Http\Resources\Json\JsonResource;

class JobSeekerCertificateInfoResource extends JsonResource
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
            'user_id'=>$this->user_id,
            'certificate_name'=>$this->certificate_name,
            'issuing_organization'=>$this->issuing_organization,
            'credential_id'=>$this->credential_id,
            'credential_url'=>$this->credential_url,
            'issue_date'=>date('M-Y',strtotime($this->issue_date)),
            'expiry_date'=>($this->is_credential_expire == 1) ? LanguageString::translated()->where('name_key','no_expiration_date')->first()->name : date('M-Y',strtotime($this->expiry_date)),
            'credential_id_sting'=>LanguageString::translated()->where('name_key','credential_id')->first()->name ." : ".$this->credential_id,
            'issue_date_sting'=>LanguageString::translated()->where('name_key','issued')->first()->name ." ".date('M-Y',strtotime($this->issue_date)),
            'expiry_date_sting'=>($this->is_credential_expire == 1) ? LanguageString::translated()->where('name_key','no_expiration_date')->first()->name : LanguageString::translated()->where('name_key','expires')->first()->name .' '. date('M-Y',strtotime($this->expiry_date)),
            'is_credential_expire'=>$this->is_credential_expire,
            'description'=>$this->description
        ];
    }
}
