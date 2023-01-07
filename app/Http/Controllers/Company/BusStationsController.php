<?php

namespace App\Http\Controllers\Company;


use App\Models\BusStations;
use App\Models\Language;
use App\Models\VehicleType;
use Illuminate\Contracts\Foundation\App\Modelslication;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class BusStationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            //DB::enableQueryLog();
            $engines = BusStations::where('company_id',auth()->guard('company')->user()->id)->get();
            return Datatables::of($engines)
                ->addColumn('action', function($engines){
                    $edit_button = '<a href="' . route('company.bus_stations.edit', [$engines->id]) . '" class="btn btn-info btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
//                    $delete_button = '<button data-id="' . $engines->id . '" class="delete-single btn btn-danger btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '"><i class="bx bx-trash font-size-16 align-middle"></i></button>';
                    return '<div class="btn-icon-list">' . $edit_button . '</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('company.bus_stations.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('company.bus_stations.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $id = $request->input('edit_value');

        if($id == NULL){
            BusStations::create([
                'station_name'    => $request->station_name,
                'station_lat'    => $request->station_lat,
                'station_long'    => $request->station_long,
                'address'    => $request->address,
                'company_id'    => auth()->guard('company')->user()->id
            ]);
            return response()->json(['success' => true, 'message' => 'Bus Station Added']);
        } else{
            BusStations::where('id', $id)->update([
                'station_name'    => $request->station_name,
                'station_lat'    => $request->station_lat,
                'station_long'    => $request->station_long,
                'address'    => $request->address,
                'company_id'    => auth()->guard('company')->user()->id
            ]);

            return response()->json(['success' => true, 'message' => 'Bus Station Updated']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $bus_station = BusStations::find($id);
        if($bus_station){
            return view('company.bus_stations.edit', [
                'bus_station'       => $bus_station,
            ]);
        } else{
            abort(404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        BusStations::where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => 'Bus Station Deleted']);
    }
}
