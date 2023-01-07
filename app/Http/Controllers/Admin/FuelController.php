<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use App\Models\CategoryVehicle;
use App\Models\Fuel;
use App\Models\FuelTranslation;
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

class FuelController extends Controller
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
            $fuels = Fuel::listsTranslations('name')->select('fuels.*');
            // dd(DB::getQueryLog());
            return Datatables::of($fuels)
                ->filter(function($query) use ($request){
                    if(!empty($request->input('search'))){
                        $query->whereTranslationLike('name', "%" . $request->input('search') . "%");
                        $query->orWhere('fuels.id', 'LIKE', "%" . $request->input('search') . "%");
                    }
                })
                ->addColumn('action', function($fuels){
                    $edit_button = '<a href="' . route('admin.fuel.edit', [$fuels->id]) . '" class="btn btn-info btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
                    $delete_button = '<button data-id="' . $fuels->id . '" class="delete-single btn btn-danger btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '"><i class="bx bx-trash font-size-16 align-middle"></i></button>';
                    return '<div class="btn-icon-list">' . $edit_button . ' ' . $delete_button . '</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.fuel.index');
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
        return view('admin.fuel.create', [
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
            $fuel_order = Fuel::max('id');
            $insert_id = Fuel::create([
                'fuel_order' => $fuel_order + 1,
            ]);
            $languages = Language::where('status','Active')->get();
            foreach($languages as $language){
                FuelTranslation::create([
                    'name'    => $request->input($language->language_code . '_name'),
                    'fuel_id' => $insert_id->id,
                    'locale'  => $language->language_code,
                ]);
            }
            return response()->json(['success' => true, 'message' => config('languageString.fuel_added')]);
        } else{
            $languages = Language::where('status','Active')->get();
            foreach($languages as $language){
                FuelTranslation::updateOrCreate([
                    'fuel_id' => $id,
                    'locale'  => $language->language_code,
                ],
                    [
                        'fuel_id' => $id,
                        'locale'  => $language->language_code,
                        'name'    => $request->input($language->language_code . '_name'),
                    ]);

            }
            return response()->json(['success' => true, 'message' => config('languageString.fuel_updated')]);
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
        $fuel = Fuel::find($id);
        $vehicleTypes = VehicleType::all();
        if($fuel){
            $languages = Language::where('status','Active')->get();
            return view('admin.fuel.edit', [
                'fuel'         => $fuel,
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
        Fuel::where('id', $id)->delete();
        return response()->json(['success' => true, 'message' => config('languageString.fuel_deleted')]);
    }
}
