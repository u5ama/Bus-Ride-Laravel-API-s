<?php

namespace App\Http\Resources;
use App\Models\PassengerCurrentLocation;
use App\Utility\Utility;
use Illuminate\Http\Resources\Json\JsonResource;

class GetTripResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $passenger =  PassengerCurrentLocation::where('pcl_passenger_id', $this->passenger_id)->first();
        $latitude = $passenger->pcl_lat;
        $longitude = $passenger->pcl_long;
        $selected_for_estimate_dis_time = Utility::timeAndDistance($this->station->station_lat, $this->station->station_long, $latitude, $longitude);
        $distance = $selected_for_estimate_dis_time->routes[0]->legs[0]->distance->value/1000;
        $duration = $selected_for_estimate_dis_time->routes[0]->legs[0]->duration->value/60;
        return
            [
                'id'=> $this->id,
                'bus_name'=> $this->bus->name,
                'bus_color'=> $this->bus->bus_color,
                'bus_registration_number'=> $this->bus->reg_number,
                'driver_name'=> $this->driver->du_full_name,
                'driver_email'=> $this->driver->email,
                'driver_phone'=> $this->driver->du_full_mobile_number,
                'total_fare'=> $this->total_fare,
                'number_of_seats_booked'=> $this->number_of_seats,
                'payment_status'=> $this->payment_status,
                'start_time'=> $this->start_time,
                'end_time'=> $this->end_time,
                'station_name'=> $this->station->station_name,
                'station_distance'=> (isset($distance) && $distance != null) ? number_format((float)$distance , 3, '.', '') : "0.000",
                'station_time'=> (isset($duration) && $duration != null) ? number_format((float)$duration , 0, '.', '') : '1',
                'station_lat'=> $this->station->station_lat,
                'station_long'=> $this->station->station_long,
                'destination_station_lat'=> $this->destination_station->station_lat,
                'destination_station_long'=> $this->destination_station->station_long,
                'ride_created_at'=> $this->created_at,
        ];
    }
}
