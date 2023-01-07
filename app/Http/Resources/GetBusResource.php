<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class GetBusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $route_id = $this->route_id;
        $station_id = $this->station_id;
        $crr = $this->bus->total_seats - $this->bus->current_seats;
        return
            [
                'route_id'=> $route_id,
                'station_id'=> $station_id,
                'bus_id'=> $this->bus->id,
                'driver_name'=> $this->driver->du_full_name,
                'driver_email'=> $this->driver->email,
                'driver_mobile'=> $this->driver->du_mobile_number,
                'driver_photo'=> $this->driver->du_profile_pic,
                'bus_name'=> $this->bus->name,
                'bus_registration_number'=> $this->bus->reg_number,
                'bus_color'=> $this->bus->bus_color,
                'bus_total_seats'=> $this->bus->total_seats,
                'bus_current_seats'=> $crr,
                'bus_per_seat_charge'=> $this->bus->per_seat_charge,
                'bus_start_time'=> $this->start_time,
                'bus_end_time'=> $this->end_time,
//                'request_body' => $request->all()
        ];
    }
}
