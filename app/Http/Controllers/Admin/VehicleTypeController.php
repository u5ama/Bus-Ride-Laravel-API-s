<?php

namespace App\Http\Controllers\Admin;

use App\Models\VehicleType;
use App\Models\VehicleTypeTranslation;
use App\Models\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\VehicleTypeStoreRequest;
use Yajra\DataTables\Facades\DataTables;

class VehicleTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $vehicleTypes = VehicleType::listsTranslations('name')->select('vehicle_types.*');
            return Datatables::of($vehicleTypes)
                ->filter(function($query) use ($request){
                    if(!empty($request->input('search'))){
                        $query->whereTranslationLike('name', "%" . $request->input('search') . "%");
                        $query->orWhere('vehicle_types.id', 'LIKE', "%" . $request->input('search') . "%");
                    }
                })
                ->addColumn('action', function($vehicleTypes){
                    $edit_button = '<a href="' . route('admin.vehicle-type.edit', [$vehicleTypes->id]) . '" class="btn btn-info btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
                    $delete_button = '<button data-id="' . $vehicleTypes->id . '" class="delete-single btn btn-danger btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '"><i class="bx bx-trash font-size-16 align-middle"></i></button>';
                    return '<div class="btn-icon-list">' . $edit_button . ' ' . $delete_button . '</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.vehicleType.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $languages = Language::where('status','Active')->get();
        return view('admin.vehicleType.create', ['languages' => $languages]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(VehicleTypeStoreRequest $request)
    {
        $validated = $request->validated();
        $id = $request->input('edit_value');

        if($id == NULL){

            $vehicle_type_order = VehicleType::max('id');
            $insert_id = new VehicleType();
            $insert_id->vehicle_type_order = $vehicle_type_order;
            $insert_id->save();
            $languages = Language::where('status','Active')->get();
            foreach($languages as $language){
                VehicleTypeTranslation::create([
                    'name'            => $request->input($language->language_code . '_name'),
                    'vehicle_type_id' => $insert_id->id,
                    'locale'          => $language->language_code,
                ]);
            }
            return response()->json(['success' => true, 'message' => config('languageString.vehicle_type_added')]);
        } else{
            $languages = Language::where('status','Active')->get();
            foreach($languages as $language){
                VehicleTypeTranslation::updateOrCreate([
                    'vehicle_type_id' => $id,
                    'locale'          => $language->language_code,
                ],
                    [
                        'vehicle_type_id' => $id,
                        'locale'          => $language->language_code,
                        'name'            => $request->input($language->language_code . '_name'),
                    ]);

            }
            return response()->json(['success' => true, 'message' => config('languageString.vehicle_type_updated')]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $vehicleType = VehicleType::find($id);
        if($vehicleType){
            $languages = Language::where('status','Active')->get();
            return view('admin.vehicleType.edit', ['vehicleType' => $vehicleType, 'languages' => $languages]);
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
        VehicleType::where('id', $id)->delete();
        return response()->json(['success' => true, 'message' => config('languageString.vehicle_type_deleted')]);
    }
}
