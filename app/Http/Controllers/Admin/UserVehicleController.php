<?php

namespace App\Http\Controllers\Admin;


use App\Models\UserVehicle;
use App\Http\Requests\UserVehicleStoreRequest;
use Illuminate\Http\Request;
use App\Models\Body;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\Engine;
use App\Models\Fuel;
use App\Models\ModelYear;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\VehicleType;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class UserVehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\App\Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $userVehicles = UserVehicle::select('user_vehicles.*');
            return Datatables::of($userVehicles)
                ->addColumn('user_name', function ($userVehicles) {
                    return $userVehicles->user->name;
                })
                ->addColumn('car_model', function ($userVehicles) {
//                    dd($userVehicle->carModel);
                    if ($userVehicles->carModel) {
                        return $userVehicles->carModel->name;
                    }
                })
                ->addColumn('body', function ($userVehicles) {
                    if ($userVehicles->body) {
                        return $userVehicles->body->name;
                    }
                })
                ->addColumn('year', function ($userVehicles) {
                    if ($userVehicles->modelYear) {
                        return $userVehicles->modelYear->name;
                    }
                })
                ->addColumn('engine', function ($userVehicles) {
                    if ($userVehicles->engine) {
                        return $userVehicles->engine->name;
                    }
                })
                ->addColumn('fuel', function ($userVehicles) {
                    if ($userVehicles->fuel) {
                        return $userVehicles->fuel->name;
                    }
                })
                ->addColumn('creation_time', function ($userVehicles) {
                    return date('d-m-Y H:i:s', strtotime($userVehicles->created_at));
                })
                ->addColumn('updation_time', function ($userVehicles) {
                    return date('d-m-Y H:i:s', strtotime($userVehicles->updated_at));
                })
                ->addColumn('status', function ($userVehicles) {
                    if ($userVehicles->status == 'approved') {
                        $status = '<span class=" badge badge-success">' . config('languageString.approved') . '</span>';
                    } else if ($userVehicles->status == 'rejected') {
                        $status = '<span class=" badge badge-danger">' . config('languageString.rejected') . '</span>';
                    } else {
                        $status = '<span  class=" badge badge-warning">' . config('languageString.pending') . '</span>';
                    }
                    return $status;
                })
                ->addColumn('action', function ($userVehicles) {
                    $status_button = '';
                    $edit_button = '<a href="' . route('admin.user-vehicle.edit', [$userVehicles->id]) . '" class="btn btn-icon btn-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="' . config('languageString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
                    $delete_button = '<button data-id="' . $userVehicles->id . '" class="delete-single btn btn-danger btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '"><i class="bx bx-trash font-size-16 align-middle"></i></button>';
                    $status = 'Active';
//                    $status_button = '<button data-id="' . $userVehicle->id . '" data-status="' . $status . '"
//                     class="status-change btn btn-warning btn-icon" data-effect="effect-fall"
//                      data-toggle="tooltip" data-placement="top" title="' . $status . '">
//                      <i class="bx bx-refresh font-size-16 align-middle"></i>
//                      </button>';

                    return '<div class="btn-icon-list">' . $edit_button . ' ' . $delete_button . '  ' . $status_button . '</div>';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('admin.userVehicle.index');
    }

    public function store(UserVehicleStoreRequest $request)
    {
        $insert = UserVehicle::find($request['edit_value']);
        $insert->name = $request['name'];
        $insert->vehicle_type_id = $request['type'];
        $insert->brand_id = $request['brand'];
        $insert->car_model_id = $request['vehicle_model'];
        $insert->model_year_id = $request['year'];
        $insert->body_id = $request['body'];
        $insert->fuel_id = $request['fuel'];
        $insert->engine_id = $request['engine'];
        $insert->save();
        return response()->json(['success' => true, 'message' => config('languageString.vehicle_updated')], 200);
    }

    public function userVehicleChangeStatus($id, $status): \Illuminate\Http\JsonResponse
    {
        UserVehicle::where('id', $id)->update(['status' => $status]);
        return response()->json(['success' => true, 'message' => config('languageString.change_status_message')]);
    }

    public function edit($id)
    {
        $userVehicle = UserVehicle::where('id', $id)->first();
        $types = VehicleType::all();
        $brands = Brand::where('vehicle_type_id', $userVehicle->vehicle_type_id)->get();
        $models = CarModel::where('brand_id', $userVehicle->brand_id)->listsTranslations('name')->get()->unique('name');
        $years = ModelYear::all();
        $bodies = Body::all();
        $fuels = Fuel::all();
        $engines = Engine::all();
        return view('admin.userVehicle.edit',
            [
                'userVehicle' => $userVehicle,
                'types' => $types,
                'brands' => $brands,
                'models' => $models,
                'years' => $years,
                'bodies' => $bodies,
                'fuels' => $fuels,
                'engines' => $engines,
            ]);
    }

    public function destroy($id)
    {
        UserVehicle::where('id', $id)->delete();
        return response()->json(['success' => true, 'message' => config('languageString.user_vehicle_deleted')]);
    }
}
