<?php

namespace App\Http\Controllers\Admin;


use App\Models\Body;
use App\Models\Booking;
use App\Models\Brand;
use App\Models\Color;
use App\Models\ModelYear;
use App\Models\CategoryVehicle;
use App\Models\Company;
use App\Models\Door;
use App\Models\Engine;
use App\Models\Fuel;
use App\Models\Gearbox;
use App\Models\Insurance;
use App\Models\Language;
use App\Models\Ryde;
use App\Models\RydeInstance;
use App\Models\RydeTranslation;
use App\Models\Vehicle;
use App\Models\VehicleExtra;
use App\Models\VehicleFeature;
use App\Models\VehicleNotAvailable;
use App\Models\VehicleOption;
use App\Helpers\ImageUploadHelper;
use App\Helpers\LogCreateHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use Yajra\DataTables\Facades\DataTables;

class RydeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\App\Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $rydes = Ryde::with('brand', 'modelYear', 'color', 'rydeInstance');

            if($request->input('make_id') != NULL){
                $rydes->where('brand_id', $request->input('make_id'));
            }
            if($request->input('model') != NULL){
                $rydes->whereTranslation('name', $request->input('model'));
            }
            if($request->input('year_id') != NULL){
                $rydes->where('model_year_id', $request->input('year_id'));
            }
            if($request->input('color_id') != NULL){
                $rydes->where('color_id', $request->input('color_id'));
            }
            if($request->input('body_id') != NULL){
                $rydes->whereHas('rydeInstance', function($q) use ($request){
                    $q->where('body_id', $request->input('body_id'));
                });
            }
            if($request->input('door_id') != NULL){
                $rydes->whereHas('rydeInstance', function($q) use ($request){
                    $q->where('door_id', $request->input('door_id'));
                });
            }
            if($request->input('seat') != NULL){
                $rydes->whereHas('rydeInstance', function($q) use ($request){
                    $q->where('seats', $request->input('seat'));
                });
            }
            $rydes = $rydes->listsTranslations('name')->select('rydes.*');

            return Datatables::of($rydes)
                ->filter(function($query) use ($request){
                    if(!empty($request->input('search'))){
                        $query->whereTranslationLike('name', "%" . $request->input('search') . "%")
                            ->orWhereHas('rydeInstance', function($query) use ($request){
                                $query->whereHas('door', function($query) use ($request){
                                    $query->whereTranslationLike('name', "%" . $request->input('search') . "%");
                                })->orWhereHas('body', function($query) use ($request){
                                    $query->whereTranslationLike('name', "%" . $request->input('search') . "%");
                                })->orWhere('seats', "%" . $request->input('search') . "%");
                            })
                            ->orWhereHas('brand', function($query) use ($request){
                                $query->whereTranslationLike('name', "%" . $request->input('search') . "%");
                            })
                            ->orWhereHas('color', function($query) use ($request){
                                $query->whereTranslationLike('name', "%" . $request->input('search') . "%");
                            })
                            ->orWhereHas('modelYear', function($query) use ($request){
                                $query->where('name', 'LIKE', "%" . $request->input('search') . "%");
                            })
                            ->orWhere('rydes.id', 'LIKE', "%" . $request->input('search') . "%");
                    }
                })
                ->order(function($query) use ($request){
                    if(!empty($request->input('order'))){
                        $order = $request->input('order');
                        if($order[0]['column'] == 0) {
                            $query->orderBy('rydes.id', $order[0]['dir']);
                        }
                        if($order[0]['column'] == 2) {
                            $query->whereHas('brand', function($query) use ($order)  {
                                $query->orderByTranslation('name');
                            });
                        }
                        if($order[0]['column'] == 3) {
                            $query->whereHas('modelYear', function($query) use ($order)  {
                                $query->orderBy('name', $order[0]['dir']);
                            });
                        }

                        if($order[0]['column'] == 5) {
                            $query->whereHas('color', function($query) use ($order)  {
                                $query->orderByTranslation('name');
                            });
                        }

                        if($order[0]['column'] == 6 || $order[0]['column'] == 7 || $order[0]['column'] == 8) {

                            $query->whereHas('rydeInstance', function($query) use ($order) {
                                if($order[0]['column'] == 6){
                                    $query->whereHas('body', function($query) use ($order){
                                        $query->orderByTranslation('name');

                                    });
                                }
                                if($order[0]['column'] == 7){
                                    $query->whereHas('door', function($query) use ($order){
                                        $query->orderByTranslation('name');

                                    });
                                }
                                if($order[0]['column'] == 8){

                                    $query->orderBy('seats', $order[0]['dir']);
                                }
                            });
                        }
                    }
                })

                ->orderColumn('color', function($query, $order){
                    $query->whereHas('ryde', function($q) use ($order){
                        $q->with([
                            'color' => function($qs) use ($order){
                                $qs->orderByTranslation('name');
                            },
                        ]);
                    });
                })
                ->addColumn('image', function($rydes){
                    $url = asset($rydes->image);
                    return "<img src='" . $url . "' style='width:100px' />";
                })
//                ->addColumn('brand_translations.name', function($rydes){
//                    return $rydes->brand->name;
//                })
                ->addColumn('modelYear', function($rydes){
                    return $rydes->modelYear->name;
                })
                ->addColumn('color', function($rydes){
                    $colorName = '';
                    if(!empty($rydes->color->name)){

                        $colorName = $rydes->color->name;
                    }
                    return $colorName;
                })
                ->addColumn('model_name', function($rydes){
                    return $rydes->name;
                })
                ->addColumn('door', function($rydes){
                    return $rydes->rydeInstance->door->name;
                })
                ->addColumn('body', function($rydes){
                    return $rydes->rydeInstance->body->name;
                })
                ->addColumn('seat', function($rydes){
                    return $rydes->rydeInstance->seats;
                })
                ->addColumn('action', function($rydes){
                    $edit_button = '<a href="' . route('admin::ryde.edit', [$rydes->id]) . '" class="btn btn-info btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
                    $delete_button = '<button data-id="' . $rydes->id . '" class="delete-single btn btn-danger btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '"><i class="bx bx-trash font-size-16 align-middle"></i></button>';

                    $view_detail_button = '<button data-id="' . $rydes->id . '" class="ryde-details btn  btn-secondary btn-icon" data-effect="effect-fall" data-toggle="tooltip" data-placement="top" title="' . config('languageString.view_details') . '"><i class="bx bx-bullseye font-size-16 align-middle"></i></button>';

                    return '<div class="btn-icon-list">' . $edit_button . ' ' . $delete_button . ' ' . $view_detail_button . '</div>';
                })
                ->rawColumns(['action', 'image'])
                ->make(true);
        }
        $colors = Color::all();
        $makes = Brand::all();
        $doors = Door::all();
        $years = ModelYear::orderBy('id', 'desc')->get();
        $bodies = Body::all();
        return view('admin.ryde.index',
            [
                'colors' => $colors,
                'doors'  => $doors,
                'years'  => $years,
                'makes'  => $makes,
                'bodies' => $bodies,
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\App\Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $languages = Language::all();
        $makes = Brand::all();
        $bodies = Body::all();
        $engines = Engine::all();
        $doors = Door::all();
        $fuels = Fuel::all();
        $colors = Color::all();
        $modelYears = ModelYear::all();
        $gearboxes = Gearbox::all();
        $insurances = Insurance::all();
        return view('admin.ryde.create', [
            'languages'  => $languages,
            'makes'      => $makes,
            'modelYears' => $modelYears,
            'colors'     => $colors,
            'bodies'     => $bodies,
            'engines'    => $engines,
            'doors'      => $doors,
            'fuels'      => $fuels,
            'gearboxes'  => $gearboxes,
            'insurances' => $insurances,
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
        $duplicate_ryde = 0;
        if($id == NULL){
            $validator_array = [
                'make'       => 'required',
                'year'       => 'required',
                'to_year_id' => 'required',
                'image'      => 'required|image|mimes:jpeg,png,jpg',
                'color'      => 'required',
                'body_id'    => 'required',
                'door'       => 'required',
                'seat'       => 'required',
            ];
        } else{
            $validator_array = [
                'make'    => 'required',
                'year'    => 'required',
                'color'   => 'required',
                'body_id' => 'required',
                'door'    => 'required',
                'seat'    => 'required',
            ];
            $duplicate_ryde = Ryde::listsTranslations('name')
                ->where([
                    'rydes.brand_id'         => $request->input('make'),
                    'rydes.model_year_id'    => $request->input('year'),
                    'rydes.color_id'         => $request->input('color'),
                    'ryde_translations.name' => $request->input('en_name'),
                ])->where('rydes.id', '!=', $id)->count();
        }

        $validator = Validator::make($request->all(), $validator_array);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        if($id == NULL){
            if($request->input('to_year_id') >= $request->input('year')){
                for($i = $request->input('year'); $i <= $request->input('to_year_id'); $i++){
                    $year = ModelYear::where('name', $i)->first()->id;

                    $duplicate_ryde = Ryde::listsTranslations('name')
                        ->where('rydes.brand_id', $request->input('make'))
                        ->where('rydes.model_year_id', $year)
                        ->where('rydes.color_id', $request->input('color'))
                        ->where('ryde_translations.name', $request->input('en_name'))
                        ->count();
                    if($duplicate_ryde != 0){
                        $color = Color::where('id', $request->input('color'))->first();
                        return response()->json([
                            'success' => false,
                            'message' => str_replace(['%year%', '%color%'], [$i, $color->name], config('languageString.ryde_duplicate_in_colo_year')),
                        ]);
                    }
                }
            } else{
                return response()->json([
                    'success' => false,
                    'message' => config('languageString.to_year_is_small_to_from_year'),
                ]);
            }
        } else if($duplicate_ryde != 0){
            return response()->json(['success' => false, 'message' => config('languageString.ryde_duplicate')]);
        }

        $image = '';
        if($request->hasFile('image')){
            $image = ImageUploadHelper::imageUpload($request->file('image'));

            if($id != NULL){
                Ryde::where('id', $id)->update([
                    'image' => $image,
                ]);
            }
        }

        if($id == NULL){
            for($i = $request->input('year'); $i <= $request->input('to_year_id'); $i++){
                $year = ModelYear::where('name', $i)->first()->id;
                $insert_id = Ryde::create([
                    'brand_id'      => $request->input('make'),
                    'model_year_id' => $year,
                    'color_id'      => $request->input('color'),
                    'image'         => $image,
                ]);

                $languages = Language::all();
                foreach($languages as $language){
                    RydeTranslation::create([
                        'name'    => $request->input($language->language_code . '_name'),
                        'ryde_id' => $insert_id->id,
                        'locale'  => $language->language_code,
                    ]);
                }
                RydeInstance::create([
                    'ryde_id' => $insert_id->id,
                    'body_id' => $request->input('body_id'),
                    'door_id' => $request->input('door'),
                    'seats'   => $request->input('seat'),
                ]);
            }
            LogCreateHelper::create(config('languageString.ryde_inserted'), '', $request->all());
            return response()->json(['success' => true, 'message' => config('languageString.ryde_inserted')]);

        } else{

            Ryde::where('id', $id)->update([
                'brand_id'      => $request->input('make'),
                'model_year_id' => $request->input('year'),
                'color_id'      => $request->input('color'),
            ]);

            $languages = Language::all();
            foreach($languages as $language){
                RydeTranslation::updateOrCreate([
                    'ryde_id' => $id,
                    'locale'  => $language->language_code,
                ],
                    [
                        'ryde_id' => $id,
                        'locale'  => $language->language_code,
                        'name'    => $request->input($language->language_code . '_name'),
                    ]);
            }

            RydeInstance::where('ryde_id', $id)->update([
                'body_id' => $request->input('body_id'),
                'door_id' => $request->input('door'),
                'seats'   => $request->input('seat'),
            ]);
            LogCreateHelper::create(config('languageString.ryde_updated'), '', $request->all());
            return response()->json([
                'success' => true,
                'message' => config('languageString.ryde_updated'),
            ], 200);
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
        $ryde = Ryde::with('rydeInstance')->find($id);

        if($ryde){
            $languages = Language::all();
            $makes = Brand::all();
            $bodies = Body::all();
            $engines = Engine::all();
            $doors = Door::all();
            $fuels = Fuel::all();
            $gearboxes = Gearbox::all();
            $colors = Color::all();
            $modelYears = ModelYear::all();

            return view('admin.ryde.edit', [
                'ryde'       => $ryde,
                'languages'  => $languages,
                'makes'      => $makes,
                'bodies'     => $bodies,
                'engines'    => $engines,
                'doors'      => $doors,
                'fuels'      => $fuels,
                'gearboxes'  => $gearboxes,
                'modelYears' => $modelYears,
                'colors'     => $colors,
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
        $vehicles = Vehicle::where('ryde_id', $id)->get();
        foreach($vehicles as $vehicle){
            VehicleExtra::where('vehicle_id', $vehicle->id)->delete();
            VehicleFeature::where('vehicle_id', $vehicle->id)->delete();
            VehicleOption::where('vehicles_id', $vehicle->id)->delete();
            CategoryVehicle::where('vehicle_id', $vehicle->id)->delete();
            VehicleNotAvailable::where('vehicle_id', $vehicle->id)->delete();
            Booking::where('vehicle_id', $vehicle->id)->delete();
        }
        Vehicle::where('ryde_id', $id)->delete();
        RydeInstance::where('ryde_id', $id)->delete();
        Ryde::where('id', $id)->delete();
        LogCreateHelper::create(config('languageString.ryde_deleted'), '', $id);
        return response()->json(['success' => true, 'message' => config('languageString.ryde_deleted')], 200);
    }


    public function getModelImage(Request $request)
    {
        $model_id = $request->input('model_id');
        $model_image = BrandModel::where('id', $model_id)->select('image')->first();
        echo "<img src='" . asset($model_image->image) . "' style='max-width:30%'>";
    }

    public function rydeDetails($id)
    {
        $rydes = Ryde::where('id', $id)->with('brand', 'modelYear', 'rydeInstance', 'color')->first();
        $colorName = '';
        if(!empty($rydes->color->name)){
            $colorName = $rydes->color->name;
        }

        $array['globalModalTitle'] = $rydes->brand->name . ' | ' . $rydes->name . ' | ' . $rydes->modelYear->name . ' | ' . $colorName;
        $array['globalModalDetails'] = '<table class="table table-bordered">';
        $array['globalModalDetails'] .= '<thead class="thead-light"><tr><th colspan="6" class="text-center">' . config('languageString.ryde_details') . '</th></tr></thead>';
        $array['globalModalDetails'] .= '<thead class="thead-dark"><tr><th>' . config('languageString.body') . '</th><th>' . config('languageString.door') . '</th><th>' . config('languageString.seat') . '</th></tr></thead>';
        $array['globalModalDetails'] .= '<tr>';
        $array['globalModalDetails'] .= '<td>' . $rydes->rydeInstance->body->name . '</td>';

        $array['globalModalDetails'] .= '<td>' . $rydes->rydeInstance->door->name . '</td>';


        $array['globalModalDetails'] .= '<td>' . $rydes->rydeInstance->seats . '</td>';
        $array['globalModalDetails'] .= '</tr>';
        $array['globalModalDetails'] .= '</table>';
        $url = asset($rydes->image);
        $array['globalModalDetails'] .= "<img src='" . $url . "' />";
        $array['globalModalDetails'] .= '<table class="table table-bordered">';

        $array['globalModalDetails'] .= '</table>';

        return response()->json(['success' => true, 'data' => $array]);
    }

    public function viewBranchRyde($company_id, $branch_id)
    {
        $company_details = Company::with([
            'companyAddress' => function($query) use ($branch_id){
                $query->where('id', $branch_id);
            },
        ])->where('id', $company_id)->first();

        $vehicles = Vehicle::with([
            'ryde' => function($query){
                $query->with('brand', 'modelYear', 'color');
            },
        ])->where('company_address_id', $branch_id)->select('vehicles.*')->get();

        return view('admin.dealer.viewRyde', [
            'vehicles'        => $vehicles,
            'company_details' => $company_details,
            'branch_id'       => $branch_id,
        ]);

    }

    public function getModelFilter(Request $request)
    {
        $brand_id = $request->input('brand_id');

        $rydes = Ryde::where('brand_id', $brand_id)->get()->unique('name');
        if(count($rydes) > 0){
            echo "<option value=''>Please Select Model</option>";
            foreach($rydes as $ryde){
                echo "<option value='" . $ryde->name . "'>" . $ryde->name . "</option>";
            }
        } else{
            echo "<option value=''>No Model Found</option>";
        }
    }

//    public function rydeExport($model, $make, $color_id, $body_id, $year_id, $door_id, $seat, $type)
//    {
//        $rydes = Ryde::with('brand', 'modelYear', 'color', 'rydeInstance');
//
//        if($make != 0){
//            $rydes->where('brand_id', $make);
//        }
//        if($model != 0){
//            $rydes->whereTranslation('name', $model);
//        }
//        if($year_id != 0){
//            $rydes->where('model_year_id', $year_id);
//        }
//        if($color_id != 0){
//            $rydes->where('color_id', $color_id);
//        }
//        if($body_id != 0){
//            $rydes->whereHas('rydeInstance', function($q) use ($body_id){
//                $q->where('body_id', $body_id);
//            });
//        }
//        if($door_id != 0){
//            $rydes->whereHas('rydeInstance', function($q) use ($door_id){
//                $q->where('door_id', $door_id);
//            });
//        }
//        if($seat != 0){
//            $rydes->whereHas('rydeInstance', function($q) use ($seat){
//                $q->where('seats', $seat);
//            });
//        }
//        $rydes = $rydes->listsTranslations('name')->select('rydes.*')->get();
////        dd($rydes);
//        if($type == 'pdf'){
//            $html = view('admin.ryde.table', compact('rydes'))->render();
//
//            PDF::SetTitle(config('languageString.ryde'));
//            PDF::SetFont('dejavusans', '');
//            PDF::AddPage();
//            $locale = Session::get('locale');
//            if($locale == 'ar'){
//                PDF::setRTL(true);
//            } else{
//                PDF::setRTL(false);
//            }
//            PDF::writeHTML($html, true, 0, true, 0);
//            return PDF::Output(config('languageString.ryde') . '.pdf', 'D');
//        }
//
////        return Excel::create('Transactions', function($excel) use ($rydes){
////            $excel->sheet('mySheet', function($sheet) use ($rydes){
////                $sheet->cell('A1', function($cell){
////                    $cell->setValue(config('languageString.id'));
////                });
////                $sheet->cell('B1', function($cell){
////                    $cell->setValue(config('languageString.brand'));
////                });
////                $sheet->cell('C1', function($cell){
////                    $cell->setValue(config('languageString.year'));
////                });
////                $sheet->cell('D1', function($cell){
////                    $cell->setValue(config('languageString.model'));
////                });
////                $sheet->cell('E1', function($cell){
////                    $cell->setValue(config('languageString.color'));
////                });
////                $sheet->cell('F1', function($cell){
////                    $cell->setValue(config('languageString.body'));
////                });
////                $sheet->cell('G1', function($cell){
////                    $cell->setValue(config('languageString.door'));
////                });
////                $sheet->cell('H1', function($cell){
////                    $cell->setValue(config('languageString.seat'));
////                });
////                if(!empty($rydes)){
////                    foreach($rydes as $key => $ryde){
////                        $i = $key + 2;
////                        $sheet->cell('A' . $i, $ryde->id);
////                        $sheet->cell('B' . $i, $ryde->brand->name);
////                        $sheet->cell('C' . $i, $ryde->modelYear->name);
////                        $sheet->cell('D' . $i, $ryde->name);
////                        $sheet->cell('E' . $i, $ryde->color->name);
////                        $sheet->cell('F' . $i, $ryde->rydeInstance->body->name);
////                        $sheet->cell('G' . $i, $ryde->rydeInstance->door->name);
////                        $sheet->cell('H' . $i, $ryde->rydeInstance->seats);
////                    }
////                }
////            });
////        })->download($type);
//    }
}
