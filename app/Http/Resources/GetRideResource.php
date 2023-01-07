<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class GetRideResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $duration = $this->station_time;
        $distance = $this->station_distance;
        $station_id = $this->id;
        return
            [
                'id'=> $station_id,
                'station_name'=> $this->station_name,
                'station_address'=> $this->address,
                'station_distance'=> (isset($distance) && $distance != null) ? number_format((float)$distance , 3, '.', '') : "0.000",
                'station_time'=> (isset($duration) && $duration != null) ? number_format((float)$duration , 0, '.', '') : '1',
                'station_lat'=> $this->station_lat,
                'station_long'=> $this->station_long,
                'route_id'=> $this->route_id,
//                'request_body' => $request->all()
        ];
    }
}
