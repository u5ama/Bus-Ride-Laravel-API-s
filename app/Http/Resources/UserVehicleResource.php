<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserVehicleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray( $request )
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'brand'      => $this->brand->name,
            'brand_logo' => $this->brand->image,
            'car_model'  => $this->carModel->name,
            'body'       => $this->body->name,
            'engine'     => $this->engine->name,
            'fuel'       => $this->fuel->name,
            'is_filter'  => $this->is_filter,
            'year'       => $this->modelYear->name,
        ];
    }
}
