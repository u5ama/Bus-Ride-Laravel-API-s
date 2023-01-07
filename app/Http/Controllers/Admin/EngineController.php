<?php

namespace App\Http\Controllers\Admin;


use App\Models\Booking;
use App\Models\CategoryVehicle;
use App\Models\Engine;
use App\Models\EngineTranslation;
use App\Models\Language;
use App\Models\Vehicle;
use App\Models\VehicleExtra;
use App\Models\VehicleFeature;
use App\Models\VehicleNotAvailable;
use App\Models\VehicleOption;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class EngineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\App\Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            //DB::enableQueryLog();
            $engines = Engine::listsTranslations('name')->select('engines.*');
            // dd(DB::getQueryLog());
            return Datatables::of($engines)
                ->filter(function($query) use ($request){
                    if(!empty($request->input('search'))){
                        $query->whereTranslationLike('name', "%" . $request->input('search') . "%");
                        $query->orWhere('engines.id', 'LIKE', "%" . $request->input('search') . "%");
                    }
                })
                ->addColumn('vehicle_type', function($brands){
                    return $brands->vehicleType->name;
                })
                ->addColumn('action', function($engines){
                    $edit_button = '<a href="' . route('admin.engine.edit', [$engines->id]) . '" class="btn btn-info btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
                    $delete_button = '<button data-id="' . $engines->id . '" class="delete-single btn btn-danger btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '"><i class="bx bx-trash font-size-16 align-middle"></i></button>';
                    return '<div class="btn-icon-list">' . $edit_button . ' ' . $delete_button . '</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.engine.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\App\Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $languages = Language::where('status','Active')->get();
        $vehicleTypes = VehicleType::all();
        return view('admin.engine.create', [
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
    public function store(Request $request)
    {
        $id = $request->input('edit_value');

        if($id == NULL){
            $engine_order = Engine::max('id');
            $insert_id = Engine::create([
                'engine_order'    => $engine_order + 1,
                'vehicle_type_id' => $request->input('vehicle_type_id'),
            ]);
            $languages = Language::where('status','Active')->get();
            foreach($languages as $language){
                EngineTranslation::create([
                    'name'      => $request->input($language->language_code . '_name'),
                    'engine_id' => $insert_id->id,
                    'locale'    => $language->language_code,
                ]);
            }
            return response()->json(['success' => true, 'message' => config('languageString.engine_added')]);
        } else{
            Engine::where('id', $id)->update([
                'vehicle_type_id' => $request->input('vehicle_type_id'),
            ]);
            $languages = Language::where('status','Active')->get();
            foreach($languages as $language){
                EngineTranslation::updateOrCreate([
                    'engine_id' => $id,
                    'locale'    => $language->language_code,
                ],
                    [
                        'engine_id' => $id,
                        'locale'    => $language->language_code,
                        'name'      => $request->input($language->language_code . '_name'),
                    ]);

            }
            return response()->json(['success' => true, 'message' => config('languageString.engine_updated')]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\App\Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $engine = Engine::find($id);
        $vehicleTypes = VehicleType::all();
        if($engine){
            $languages = Language::where('status','Active')->get();
            return view('admin.engine.edit', [
                'engine'       => $engine,
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
        Engine::where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => config('languageString.engine_deleted')]);
    }
}
