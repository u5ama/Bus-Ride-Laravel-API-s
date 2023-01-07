<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CacheClearHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserAddressStoreRequest;
use App\Http\Requests\UserVehicleStoreRequest;
use App\Http\Requests\UserStoreRequest;
use App\Models\Admin;
use App\Models\Body;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\Engine;
use App\Models\Fuel;
use App\Models\Language;
use App\Models\ModelYear;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserVehicle;
use App\Models\VehicleType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $users = User::all();

            return DataTables::of($users)
                ->addColumn('action', function ($users) {
                    $edit_button = '<a href="' . route('admin.user.edit', [$users->id]) . '" class="btn btn-icon btn-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="' . config('languageString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
                    $delete_button = '<button data-id="' . $users->id . '" class="delete-single btn btn-danger btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '"><i class="bx bx-trash font-size-16 align-middle"></i></button>';
//                    $detail_button = '<a href="' . route('admin.userDetails', [$users->id]) . '" class="btn btn-secondary btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.view') . '"><i class="bx bx-bullseye font-size-16 align-middle"></i></button>';
                    if ($users->status == 'Active') {
                        $status_button = '<button data-id="' . $users->id . '" data-status="InActive" class="status-change btn btn-warning btn-icon" data-effect="effect-fall" data-toggle="tooltip" data-placement="top" title="' . config('languageString.inactive') . '" ><i class="bx bx-refresh font-size-16 align-middle"></i></button>';
                    } else {
                        $status_button = '<button data-id="' . $users->id . '" data-status="Active" class="status-change btn btn-success btn-icon" data-effect="effect-fall" data-toggle="tooltip" data-placement="top" title="' . config('languageString.active') . '" ><i class="bx bx-refresh font-size-16 align-middle"></i></button>';
                    }
                    return '<div class="btn-icon-list">'. $edit_button . ' ' . $delete_button . '' . $status_button . '</div>';
                })
                ->addColumn('status', function ($users) {
                    if ($users->status == 'Active') {
                        $status = '<a data-id="' . $users->id . '" data-status="InActive" class="status-change" data-toggle="tooltip" data-placement="top" title="' . config('languageString.inactive') . '" ><span class="badge badge-success">' . config('languageString.active') . '</span></a>';
                    } else {
                        $status = '<span data-id="' . $users->id . '" data-status="Active"  class="status-change badge badge-danger" data-toggle="tooltip" data-placement="top" title="' . config('languageString.active') . '">' . config('languageString.inactive') . '</span>';
                    }
                    return $status;
                })
                ->addColumn('is_filter', function ($users) {
                    if($users->is_filter == 'yes') {
                        $filter = 'yes';
                    } else {
                        $filter = 'no';
                    }
                    return $filter;
                })
                ->addColumn('creation_time', function ($users) {
                    return date('d-m-Y H:i:s', strtotime($users->created_at));
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('admin.user.index');
    }

    public function create()
    {
        return view('admin.user.create');
    }

    public function store(Request $request)
    {
        $id = $request->input('edit_value');

            if ($id == NULL) {
                $user = new User();
                $user->name = $request->name;
                $user->email = $request->email;
                $user->mobile_no = $request->mobile_no;
                $user->gender = $request->gender;
                $user->date_of_birth = $request->date_of_birth;
                $user->save();

                return response()->json(['message' => config('languageString.user_added')], 200);
            } else {
                $user = User::find($id);
                $user->name = $request->name;
                $user->email = $request->email;
                $user->mobile_no = $request->mobile_no;
                $user->gender = $request->gender;
                $user->date_of_birth = $request->date_of_birth;
                $user->save();

                return response()->json(['message' => config('languageString.user_updated')], 200);
            }


    }

    public function userVehicleStore(UserVehicleStoreRequest $request): \Illuminate\Http\JsonResponse
    {
        $id = $request['edit_value'];
        $validated = $request->validated();
        if ($validated) {
            if ($id == NULL) {
                $insert = new UserVehicle();
                $insert->name = $validated['name'];
                $insert->vehicle_type_id = $validated['type'];
                $insert->brand_id = $validated['brand'];
                $insert->car_model_id = $validated['vehicle_model'];
                $insert->model_year_id = $validated['year'];
                $insert->body_id = $request['body'];
                $insert->fuel_id = $validated['fuel'];
                $insert->engine_id = $request['engine'];
                $insert->user_id = $validated['user_id'];
                $insert->save();

                return response()->json(['success'=>true,'message' => config('languageString.vehicle_added')], 200);
            } else {
                $insert = UserVehicle::find($id);
                $insert->name = $validated['name'];
                $insert->vehicle_type_id = $validated['type'];
                $insert->brand_id = $validated['brand'];
                $insert->car_model_id = $validated['vehicle_model'];
                $insert->model_year_id = $validated['year'];
                $insert->body_id = $request['body'];
                $insert->fuel_id = $validated['fuel'];
                $insert->engine_id = $request['engine'];
                $insert->save();
                return response()->json(['success'=>true,'message' => config('languageString.vehicle_updated')], 200);
            }
        }
    }

    public function userAddressStore(UserAddressStoreRequest $request): \Illuminate\Http\JsonResponse
    {
        $id = $request['edit_value'];
        $validated = $request->validated();
        if ($validated) {
            if ($id == NULL) {
                $insert = new UserAddress();
                $insert->address = $validated['address'];
                $insert->latitude = $validated['latitude'];
                $insert->longitude = $validated['longitude'];
                $insert->user_id = $validated['user_id'];
                $insert->save();

                return response()->json(['success'=>true,'message' => config('languageString.address_added')], 200);
            } else {
                $insert = UserAddress::find($id);
                $insert->address = $validated['address'];
                $insert->latitude = $validated['latitude'];
                $insert->longitude = $validated['longitude'];
                $insert->save();
                return response()->json(['success'=>true,'message' => config('languageString.address_updated')], 200);
            }
        }
    }

    public function edit(int $id)
    {
        $user = User::find($id);
        return view('admin.user.edit', ["user" => $user]);
    }

    public function destroy(int $id)
    {
        User::where('id', $id)->delete();
        return response()->json(['message' => config('languageString.user_deleted')], 200);
    }

    public function changeStatus($id, $status)
    {
        User::where('id', $id)->update(['status' => $status]);

        return response()->json([
            'message' => Config::get('languageString.change_status_message'),
        ], 200);
    }

    public function userDetails($id)
    {
        $types = VehicleType::all();
        $years = ModelYear::all();
        $brands = Brand::all();
        $models = ModelYear::all();
        $bodies = Body::all();
        $fuels = Fuel::all();
        $engines = Engine::all();
        $address = UserAddress::find($id);
        $user = User::where('id', $id)->first();
        if ($user) {
            return view('admin.user.show', ['user' => $user, 'types' => $types, 'brands' => $brands, 'models'=> $models, 'years' => $years, 'bodies' => $bodies, 'fuels' => $fuels, 'engines' => $engines, 'address' => $address]);
        } else {
            abort(404);
        }
    }

    public function getBrands(Request $request)
    {
        $vehicle_type = $request->input('type');
        $vehicle_brand = Brand::where('vehicle_type_id', $vehicle_type)->get();
        if (count($vehicle_brand) > 0) {
            echo "<option value=''>---Select Brands---</option>";
            foreach ($vehicle_brand as $row) {
                echo '<option value="' . $row->id . '">' . $row->name . '</option>';
            }
        } else {
            echo '<option value="">---No Brands Found---</option>';
        }
    }

    public function getModels(Request $request)
    {
        $vehicle_brand = $request->input('brand');
        $vehicle_model = CarModel::where('brand_id', $vehicle_brand)->listsTranslations('name')->get()->unique('name');
        if (count($vehicle_model) > 0) {
            echo "<option value=''>---Select Model---</option>";
            foreach ($vehicle_model as $row) {
                echo '<option value="' . $row->id . '">' . $row->name . '</option>';
            }
        } else {
            echo "<option value=''>---No Model Found---</option>";
        }
    }

    public function userVehicle(Request $request)
    {
        if ($request->ajax()) {

            $userVehicles = UserVehicle::where('user_id', $request->input('user_id'))->get();

            return DataTables::of($userVehicles)
                ->addColumn('action', function ($userVehicles) {
                    $edit_button = '<a href="' . route('admin.editUserVehicle', [$userVehicles->id]) . '" class="btn btn-icon btn-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="' . config('languageString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
                    $delete_button = '<button data-id="' . $userVehicles->id . '" class="delete-vehicle btn btn-danger btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '"><i class="bx bx-trash font-size-16 align-middle"></i></button>';
                    $status_button = '';
                    return '<div class="btn-icon-list">' . $edit_button . '' . $delete_button . '' . $status_button . '</div>';
                })
                ->addColumn('vehicle_type', function ($userVehicles) {
                    return $userVehicles->vehicleType->name;
                })
                ->addColumn('brand', function ($userVehicles) {
                    return $userVehicles->brand->name;
                })
                ->addColumn('model', function ($userVehicles) {
                    return $userVehicles->carModel->name;
                })
                ->addColumn('body', function ($userVehicles) {
                    return $userVehicles->body->name;
                })
                ->addColumn('year', function ($userVehicles) {
                    return $userVehicles->modelYear->name;
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
                ->addColumn('status', function ($users) {
                    if ($users->status == 'Active') {
                        $status = '<a data-id="' . $users->id . '" data-status="InActive" class="status-change" data-toggle="tooltip" data-placement="top" title="' . config('languageString.inactive') . '" ><span class="badge badge-success">' . config('languageString.active') . '</span></a>';
                    } else {
                        $status = '<span data-id="' . $users->id . '" data-status="Active"  class="status-change badge badge-danger" data-toggle="tooltip" data-placement="top" title="' . config('languageString.active') . '">' . config('languageString.inactive') . '</span>';
                    }
                    return $status;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('admin.user.userDetails');
    }

    public function userAddress(Request $request)
    {
        if ($request->ajax()) {

            $userAddress = UserAddress::where('user_id', $request->input('user_id'))->get();

            return DataTables::of($userAddress)
                ->addColumn('action', function ($userAddress) {
                    $edit_button = '<a href="' . route('admin.editUserAddress', [$userAddress->id]) . '" class="btn btn-icon btn-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="' . config('languageString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
                    $delete_button = '<button data-id="' . $userAddress->id . '" class="delete-single btn btn-danger btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '"><i class="bx bx-trash font-size-16 align-middle"></i></button>';
                    $status_button = '';
                    return '<div class="btn-icon-list">' . $edit_button . '' . $delete_button . '' . $status_button . '</div>';
                })
                ->addColumn('status', function ($users) {
                    if ($users->status == 'Active') {
                        $status = '<a data-id="' . $users->id . '" data-status="InActive" class="status-change" data-toggle="tooltip" data-placement="top" title="' . config('languageString.inactive') . '" ><span class="badge badge-success">' . config('languageString.active') . '</span></a>';
                    } else {
                        $status = '<span data-id="' . $users->id . '" data-status="Active"  class="status-change badge badge-danger" data-toggle="tooltip" data-placement="top" title="' . config('languageString.active') . '">' . config('languageString.inactive') . '</span>';
                    }
                    return $status;
                })
                ->addColumn('creation_time', function ($users) {
                    return date('d-m-Y H:i:s', strtotime($users->created_at));
                })
                ->addColumn('updation_time', function ($users) {
                    return date('d-m-Y H:i:s', strtotime($users->updated_at));
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('admin.user.userAddress');
    }

    public function editUserVehicle($id)
    {
        $userVehicle = UserVehicle::where('id', $id)->first();

        $types = VehicleType::all();
        $brands = Brand::where('vehicle_type_id', $userVehicle->vehicle_type_id)->get();
        $models = CarModel::where('brand_id', $userVehicle->brand_id)->listsTranslations('name')->get()->unique('name');
        $years = ModelYear::all();
        $bodies = Body::all();
        $fuels = Fuel::all();
        $engines = Engine::all();
        return view('admin.user.editUserVehicle',
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

    public function editUserAddress($id)
    {
        $address = UserAddress::where('id', $id)->first();
        return view('admin.user.editAddress', ['address' => $address]);
    }

    public function deleteUserAddress($id)
    {
        UserAddress::where('id', $id)->delete();
        return response()->json(['message' => config('languageString.userAddress_deleted')], 200);
    }

    public function createAddress($id)
    {
        $user = User::where('id', $id)->first();
        return view('admin.user.createAddress', ['user' => $user]);
    }

    public function deleteUserVehicle($id)
    {
        UserVehicle::where('id', $id)->delete();
        return response()->json(['message' => config('languageString.userVehicle_deleted')], 200);
    }

}
