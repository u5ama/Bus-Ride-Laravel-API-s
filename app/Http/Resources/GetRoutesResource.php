<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class GetRoutesResource extends JsonResource
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
                'route_id'=> $this->route_id,
                'route_name'=> $this->routes->route_name,
                'start_time'=> $this->start_time,
                'end_time'=> $this->end_time,
                'bus_id'=> $this->bus_id,
        ];
    }
}
