<?php

namespace App\Http\Controllers\Admin;


use App\Models\BusStations;
use App\Models\Company;
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
            $engines = BusStations::all();
            return Datatables::of($engines)
//                ->filter(function($query) use ($request){
//                    if(!empty($request->input('search'))){
////                        $query->where('station_name', "%" . $request->input('search') . "%");
//                        $query->where('station_name', 'LIKE', "%" . $request->input('search') . "%");
//                    }
//                })
                ->addColumn('company', function($engines) {
                    $company = Company::where('id', $engines->company_id)->first();
                    return $company->com_name;
                })
                ->addColumn('action', function($engines){
                    $edit_button = '<a href="' . route('admin.bus_stations.edit', [$engines->id]) . '" class="btn btn-info btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
//                    $delete_button = '<button data-id="' . $engines->id . '" class="delete-single btn btn-danger btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '"><i class="bx bx-trash font-size-16 align-middle"></i></button>';
//                    return '<div class="btn-icon-list">' . $edit_button . ' ' . $delete_button . '</div>';
                    return '<div class="btn-icon-list">' . $edit_button . ' ' . '</div>';
                })
                ->rawColumns(['action','company'])
                ->make(true);
        }
        return view('admin.bus_stations.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $companies = Company::all();
        return view('admin.bus_stations.create',compact('companies'));
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
                'company_id'    => $request->company_id,
            ]);
            return response()->json(['success' => true, 'message' => 'Bus Station Added']);
        } else{
            BusStations::where('id', $id)->update([
                'station_name'    => $request->station_name,
                'station_lat'    => $request->station_lat,
                'station_long'    => $request->station_long,
                'address'    => $request->address,
                'company_id'    => $request->company_id,
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
            $companies = Company::all();
            return view('admin.bus_stations.edit', [
                'bus_station'       => $bus_station,
                'companies'       => $companies,
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
