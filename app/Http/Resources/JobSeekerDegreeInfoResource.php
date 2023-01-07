<?php

namespace App\Http\Resources;

use App\Models\Degree;
use App\Models\LanguageString;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class JobSeekerDegreeInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $degreeObj = DegreeResource::collection(Degree::translated()->where('id',$this->degree_id)->get());
        if ($degreeObj[0]){
            $degreeObject = $degreeObj[0];
        }else{
            $degreeObject = null;
        }

        if($this->currently_studying == 1){
            $now = now();
        }else{
            $now = $this->degree_to;
        }

        return [
            'id'=>$this->id,
            'user_id'=>$this->user_id,
            'degree_id'=>$degreeObject,
            'field_of_study'=>$this->field_of_study,
            'school_or_university'=>$this->school_or_university,
            'degree_to'=>($this->currently_studying == 1) ? LanguageString::translated()->where('name_key','present')->first()->name : date('M-Y',strtotime($this->degree_to)),
            'degree_from'=>date('M-Y',strtotime($this->degree_from)),
            'degree_duration'=>Carbon::parse($this->exp_from)->longRelativeDiffForHumans(Carbon::parse($now), 2),
            'description'=>$this->description,
            'currently_studying'=>$this->currently_studying
        ];
    }
}
