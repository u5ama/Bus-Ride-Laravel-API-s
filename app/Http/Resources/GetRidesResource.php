<?php

namespace App\Http\Resources;
use App\Models\AssignBuses;
use App\Models\Buses;
use App\Models\BusStations;
use App\Models\Driver;
use App\Models\PassengerCurrentLocation;
use App\Models\Routes;
use App\Utility\Utility;
use Illuminate\Http\Resources\Json\JsonResource;

class GetRidesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $bus = Buses::where('id', $this->bus_id)->first();
        $route = AssignBuses::where('id',$this->route_id)->first();
        $driver = Driver::where('id', $route->driver_id)->first();
        $station = BusStations::where('id', $this->station_id)->first();

        $route_station = Routes::with(['routetations'=> function($query) {
            $query->orderBy('id', 'desc')->first();
        }])->where('id', $this->route_id)->get();
        foreach ($route_station as $stat){
            $destination_station = BusStations::where('id', $stat->routetations[0]->station_id)->first();
            $destination_station_name = $destination_station->station_name;
            $destination_station_lat = $destination_station->station_lat;
            $destination_station_long = $destination_station->station_long;
        }

        $passenger =  PassengerCurrentLocation::where('pcl_passenger_id', $this->passenger_id)->first();
        $latitude = $passenger->pcl_lat;
        $longitude = $passenger->pcl_long;
        $selected_for_estimate_dis_time = Utility::timeAndDistance($station->station_lat, $station->station_long, $latitude, $longitude);
        $distance = $selected_for_estimate_dis_time->routes[0]->legs[0]->distance->value/1000;
        $duration = $selected_for_estimate_dis_time->routes[0]->legs[0]->duration->value/60;

        $ride_id = $this->id;
        return
            [
                'id'=> $ride_id,
                'route_id'=> $route->id,
                'bus_id'=> $bus->id,
                'bus_name'=> $bus->name,
                'bus_color'=> $bus->bus_color,
                'bus_registration_number'=> $bus->reg_number,
                'driver_id'=> $driver->id,
                'driver_name'=> $driver->du_full_name,
                'driver_email'=> $driver->email,
                'driver_phone'=> $driver->du_full_mobile_number,
                'total_fare'=> $this->total_fare,
                'number_of_seats_booked'=> $this->number_of_seats,
                'payment_status'=> $this->payment_status,
                'start_time'=> $this->start_time,
                'end_time'=> $this->end_time,
                'station_name'=> $station->station_name,
                'station_distance'=> (isset($distance) && $distance != null) ? number_format((float)$distance , 3, '.', '') : "0.000",
                'station_time'=> (isset($duration) && $duration != null) ? number_format((float)$duration , 0, '.', '') : '1',
                'station_lat'=> $station->station_lat,
                'station_long'=> $station->station_long,
                'destination_station_name'=> $destination_station_name,
                'destination_station_lat'=> $destination_station_lat,
                'destination_station_long'=> $destination_station_long,
                'ride_created_at'=> $this->created_at,
        ];
    }
}
