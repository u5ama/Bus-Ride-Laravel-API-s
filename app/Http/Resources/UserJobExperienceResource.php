<?php

namespace App\Http\Resources;

use App\Models\LanguageString;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UserJobExperienceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        Carbon::setLocale($request->header('Accept-Language'));

        if($this->is_currently_working == 1){
            $now = now();
        }else{
            $now = $this->exp_to;
        }

        return [
            'id'=>$this->id,
            'user_id'=>$this->user_id,
            'title'=>$this->title,
            'location'=>$this->location,
            'company_label'=>$this->company_label,
            'location_lat'=>$this->location_lat,
            'location_lng'=>$this->location_lng,
            'is_currently_working'=>$this->is_currently_working,
            'exp_to'=>($this->is_currently_working == 1)? LanguageString::translated()->where('name_key','present')->first()->name :date('M-Y',strtotime($this->exp_to)),
            'exp_from'=>date('M-Y',strtotime($this->exp_from)),
            'experience_duration'=>Carbon::parse($this->exp_from)->longRelativeDiffForHumans(Carbon::parse($now), 2),
            'about_your_certification'=>$this->about_your_certification
        ];
    }
}
