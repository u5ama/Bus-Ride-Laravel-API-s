<?php

namespace App\Http\Controllers\Admin;


use App\Models\AssignBuses;
use App\Models\Buses;
use App\Models\Driver;
use App\Models\Routes;
use Illuminate\Contracts\Foundation\App\Modelslication;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class AssignBusesController extends Controller
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
            $engines = AssignBuses::all();
            return Datatables::of($engines)
//                ->filter(function($query) use ($request){
////                    if(!empty($request->input('search'))){
////                        $query->whereTranslationLike('name', "%" . $request->input('search') . "%");
////                        $query->orWhere('buses.id', 'LIKE', "%" . $request->input('search') . "%");
////                    }
//                })
                ->addColumn('bus_name', function($engines){
                    $bus = Buses::where('id', $engines->bus_id)->first();
                    if ($bus){
                        $name = $bus->name;
                    }else{
                        $name = 'No Bus';
                    }
                    return $name;
                })
                ->addColumn('driver_name', function($engines){
                    $driver = Driver::where('id', $engines->driver_id)->first();
                    return $driver->du_full_name;
                })
                ->addColumn('route_name', function($engines){
                    $route = Routes::where('id', $engines->route_id)->first();
                    return $route->route_name;
                })
                ->addColumn('status', function ($engines) {
                    if ($engines->status == 'active') {
                        $status = '<a data-id="' . $engines->id . '" data-status="InActive" class="status-change" data-toggle="tooltip" data-placement="top" title="' . config('languageString.inactive') . '" ><span class="badge badge-success">' . config('languageString.active') . '</span></a>';
                    } else {
                        $status = '<span data-id="' . $engines->id . '" data-status="Active"  class="status-change badge badge-danger" data-toggle="tooltip" data-placement="top" title="' . config('languageString.active') . '">' . config('languageString.inactive') . '</span>';
                    }
                    return $status;
                })
                ->addColumn('action', function($engines){
//                    $edit_button = '<a href="' . route('admin.assign_bus.edit', [$engines->id]) . '" class="btn btn-info btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
                    $delete_button = '<button data-id="' . $engines->id . '" class="delete-single btn btn-danger btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '"><i class="bx bx-trash font-size-16 align-middle"></i></button>';
                    return '<div class="btn-icon-list">' . $delete_button . ' ' . '</div>';
                })
                ->rawColumns(['action','status'])
                ->make(true);
        }
        return view('admin.assign_bus.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $buses = Buses::all();
        $drivers = Driver::all();
        $routes = Routes::all();
        return view('admin.assign_bus.create',compact('buses','drivers','routes'));
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
            AssignBuses::create([
                'route_id'    => $request->route_id,
                'driver_id'    => $request->driver_id,
                'bus_id'    => $request->bus_id,
                'start_time'    => $request->start_time,
                'end_time'    => $request->end_time,
            ]);
            return response()->json(['success' => true, 'message' => 'Assigned Bus Added Successfully']);
        } else{
            AssignBuses::where('id', $id)->update([
                'route_id'    => $request->route_id,
                'driver_id'    => $request->driver_id,
                'bus_id'    => $request->bus_id,
                'start_time'    => $request->start_time,
                'end_time'    => $request->end_time,
            ]);

            return response()->json(['success' => true, 'message' => 'Assigned Bus Updated Successfully']);
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
        $bus = AssignBuses::find($id);
        if($bus){
            return view('admin.assign_bus.edit', [
                'bus'       => $bus,
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
        AssignBuses::where('id', $id)->delete();
        return response()->json(['success' => true, 'message' => 'Assigned Bus Deleted Successfully']);
    }

    public function changeStatus($id, $status)
    {
        AssignBuses::where('id', $id)->update(['status' => $status]);
        return response()->json(['success' => true, 'message' => 'Assigned Bus Status Updated Successfully'], 200);
    }

}
