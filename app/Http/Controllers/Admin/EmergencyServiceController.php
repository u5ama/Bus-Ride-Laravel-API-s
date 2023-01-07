<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageUploadHelper;
use App\Models\EmergencyService;
use App\Models\EmergencyServiceTranslation;
use App\Models\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmergencyServiceStoreRequest;
use Yajra\DataTables\Facades\DataTables;

class EmergencyServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $emergencyServices = EmergencyService::listsTranslations('name')->select('emergency_services.*');
            return Datatables::of($emergencyServices)
                ->filter(function($query) use ($request){
                    if(!empty($request->input('search'))){
                        $query->whereTranslationLike('name', "%" . $request->input('search') . "%");
                        $query->orWhere('vehicle_types.id', 'LIKE', "%" . $request->input('search') . "%");
                    }
                })
                ->addColumn('image', function($emergencyServices){
                    return "<img src='" . asset($emergencyServices->image) . "' style='width:100px' />";
                })
                ->addColumn('action', function($emergencyServices){
                    $edit_button = '<a href="' . route('admin.emergency-service.edit', [$emergencyServices->id]) . '" class="btn btn-info btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
                    $delete_button = '<button data-id="' . $emergencyServices->id . '" class="delete-single btn btn-danger btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '"><i class="bx bx-trash font-size-16 align-middle"></i></button>';
                    return '<div class="btn-icon-list">' . $edit_button . ' ' . $delete_button . '</div>';
                })
                ->rawColumns(['action', 'image'])
                ->make(true);
        }
        return view('admin.emergencyService.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $languages = Language::where('status','Active')->get();
        return view('admin.emergencyService.create', ['languages' => $languages]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(EmergencyServiceStoreRequest $request)
    {
        $validated = $request->validated();
        $id = $request->input('edit_value');

        if($id == 0){

            $image = ImageUploadHelper::imageUpload($validated['image']);

            $insert_id = new EmergencyService();
            $insert_id->image = $image;
            $insert_id->save();

            $languages = Language::where('status','Active')->get();
            foreach($languages as $language){
                EmergencyServiceTranslation::create([
                    'name'                 => $request->input($language->language_code . '_name'),
                    'emergency_service_id' => $insert_id->id,
                    'locale'               => $language->language_code,
                ]);
            }
            return response()->json(['success' => true, 'message' => config('languageString.emergency_service_added')]);
        } else{
            $languages = Language::where('status','Active')->get();
            foreach($languages as $language){
                EmergencyServiceTranslation::updateOrCreate([
                    'emergency_service_id' => $id,
                    'locale'               => $language->language_code,
                ],
                    [
                        'emergency_service_id' => $id,
                        'locale'               => $language->language_code,
                        'name'                 => $request->input($language->language_code . '_name'),
                    ]);

            }
            return response()->json(['success' => true, 'message' => config('languageString.emergency_service_updated')]);
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
        $emergencyService = EmergencyService::find($id);
        if($emergencyService){
            $languages = Language::where('status','Active')->get();
            return view('admin.emergencyService.edit', ['emergencyService' => $emergencyService, 'languages' => $languages]);
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
        EmergencyService::where('id', $id)->delete();
        return response()->json(['success' => true, 'message' => config('languageString.emergency_service_deleted')]);
    }
}
