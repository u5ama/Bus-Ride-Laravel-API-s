<?php

namespace App\Http\Controllers\Admin;

use App\Models\Body;
use App\Models\BodyTranslation;
use App\Models\Booking;
use App\Models\CategoryVehicle;
use App\Models\Engine;
use App\Models\Language;
use App\Models\Ryde;
use App\Models\RydeInstance;
use App\Models\Vehicle;
use App\Models\VehicleExtra;
use App\Models\VehicleFeature;
use App\Models\VehicleNotAvailable;
use App\Helpers\ImageUploadHelper;
use App\Models\VehicleOption;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\BodyStoreRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class BodyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $bodies = Body::listsTranslations('name')->select('bodies.*');
            return Datatables::of($bodies)
                ->filter(function($query) use ($request){
                    if(!empty($request->input('search'))){
                        $query->whereTranslationLike('name', "%" . $request->input('search') . "%");
                        $query->orWhere('bodies.id', 'LIKE', "%" . $request->input('search') . "%");
                    }
                })
                ->addColumn('vehicle_type', function($brands){
                    return $brands->vehicleType->name;
                })
                ->addColumn('action', function($bodies){
                    $edit_button = '<a href="' . route('admin.body.edit', [$bodies->id]) . '" class="btn btn-info btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
                    $delete_button = '<button data-id="' . $bodies->id . '" class="delete-single btn btn-danger btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '"><i class="bx bx-trash font-size-16 align-middle"></i></button>';
                    return '<div class="btn-icon-list">' . $edit_button . ' ' . $delete_button . '</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.body.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $languages = Language::where('status','Active')->get();
        $vehicleTypes = VehicleType::all();
        return view('admin.body.create', [
            'languages'    => $languages,
            'vehicleTypes' => $vehicleTypes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(BodyStoreRequest $request)
    {
        $validated = $request->validated();
        $id = $request->input('edit_value');

        if($id == NULL){
            $body_order = Body::max('id');
            $insert_id = new Body();
            $insert_id->vehicle_type_id = $request->input('vehicle_type_id');
            $insert_id->body_order = $body_order;
            $insert_id->save();
            $languages = Language::where('status','Active')->get();
            foreach($languages as $language){
                BodyTranslation::create([
                    'name'    => $request->input($language->language_code . '_name'),
                    'body_id' => $insert_id->id,
                    'locale'  => $language->language_code,
                ]);
            }
            return response()->json(['success' => true, 'message' => config('languageString.body_added')]);
        } else{
            Body::where('id', $id)->update([
                'vehicle_type_id' => $request->input('vehicle_type_id'),
            ]);
            $languages = Language::where('status','Active')->get();
            foreach($languages as $language){
                BodyTranslation::updateOrCreate([
                    'body_id' => $id,
                    'locale'  => $language->language_code,
                ],
                    [
                        'body_id' => $id,
                        'locale'  => $language->language_code,
                        'name'    => $request->input($language->language_code . '_name'),
                    ]);

            }
            return response()->json(['success' => true, 'message' => config('languageString.body_updated')]);
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
        $body = Body::find($id);
        $vehicleTypes = VehicleType::all();
        if($body){
            $languages = Language::where('status','Active')->get();
            return view('admin.body.edit', [
                'body'         => $body,
                'languages'    => $languages,
                'vehicleTypes' => $vehicleTypes,
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
        Body::where('id', $id)->delete();
        return response()->json(['success' => true, 'message' => config('languageString.body_deleted')]);
    }
}
