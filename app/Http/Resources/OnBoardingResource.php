<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OnBoardingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return
            [
                'id'=>$this->id,
                'slug'=>$this->slug,
                'header_text'=>$this->header_text,
                'description'=>$this->description,
                'business_man_image_path'=>$this->business_man_image_path,
                'job_seeker_image_path'=>$this->job_seeker_image_path,
            ];
    }
}
