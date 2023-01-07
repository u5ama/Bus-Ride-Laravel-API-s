<?php

namespace App\Http\Controllers\Company;

use App\Models\BusStations;
use App\Models\Routes;
use App\Models\RouteStations;
use Illuminate\Contracts\Foundation\App\Modelslication;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class RoutesController extends Controller
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
            $routes = Routes::with('routetations')->where('company_id',auth()->guard('company')->user()->id)->get();

            return Datatables::of($routes)
                ->addColumn('stations', function($routes){
                    foreach($routes->routetations as $station){
                        $stations = BusStations::where('id', $station->station_id)->first();
                        $station_names[] = $stations->station_name;
                    }
                    return $station_names;
                })
                ->addColumn('action', function($routes){
//                    $edit_button = '<a href="' . route('company.routes.edit', [$routes->id]) . '" class="btn btn-info btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
                    $edit_button = '<a href="#" class="btn btn-info btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
                    $delete_button = '<button data-id="' . $routes->id . '" class="delete-single btn btn-danger btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '"><i class="bx bx-trash font-size-16 align-middle"></i></button>';
                    return '<div class="btn-icon-list">' . $edit_button . ' ' . $delete_button . '</div>';
                    return '<div class="btn-icon-list">' . $edit_button . '</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('company.routes.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $stations = BusStations::all();
        return view('company.routes.create', compact('stations'));
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
        $stations = $request->station_id;
        if($id == NULL){
            $route = Routes::create([
                'route_name'    => $request->route_name,
                'company_id'    => auth()->guard('company')->user()->id,
            ]);
            foreach ($stations as $station){
                RouteStations::create([
                    'route_id'    => $route->id,
                    'station_id'    => $station,
                ]);
            }
            return response()->json(['success' => true, 'message' => 'Route Added Successfully']);
        } else{
            Routes::where('id', $id)->update([
                'route_name'    => $request->route_name,
                'company_id'    => auth()->guard('company')->user()->id,
            ]);
            foreach ($stations as $station){
                RouteStations::create([
                    'route_id'    => $id,
                    'station_id'    => $station,
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Route Updated Successfully']);
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
        $route = Routes::find($id);
        if($route){
            return view('company.routes.edit', [
                'route'       => $route,
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
        Routes::where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => 'Route Deleted Successfully']);
    }
}
