<?php

namespace App\Http\Controllers\Company;


use App\Models\Buses;
use Illuminate\Contracts\Foundation\App\Modelslication;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class BusesController extends Controller
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
            $engines = Buses::where('company_id',auth()->guard('company')->user()->id)->get();
            return Datatables::of($engines)
                ->addColumn('action', function($engines){
                    $edit_button = '<a href="' . route('company.buses.edit', [$engines->id]) . '" class="btn btn-info btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
//                    $delete_button = '<button data-id="' . $engines->id . '" class="delete-single btn btn-danger btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '"><i class="bx bx-trash font-size-16 align-middle"></i></button>';
                    return '<div class="btn-icon-list">' . $edit_button . '</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('company.buses.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('company.buses.create');
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
        $user_id = auth()->guard('company')->user()->id;
        if($id == NULL){
            Buses::create([
                'name'    => $request->name,
                'reg_number'    => $request->reg_number,
                'bus_color'    => $request->bus_color,
                'total_seats'    => $request->total_seats,
                'current_seats'    => 0,
                'per_seat_charge'    => $request->per_seat_charge,
                'company_id'    => $user_id
            ]);
            return response()->json(['success' => true, 'message' => 'Bus Added Successfully']);
        } else{
            Buses::where('id', $id)->update([
                'name'    => $request->name,
                'reg_number'    => $request->reg_number,
                'bus_color'    => $request->bus_color,
                'total_seats'    => $request->total_seats,
                'per_seat_charge'    => $request->per_seat_charge,
                'company_id'    => $user_id
            ]);

            return response()->json(['success' => true, 'message' => 'Bus Updated Successfully']);
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
        $bus = Buses::find($id);
        if($bus){
            return view('company.buses.edit', [
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
        Buses::where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => 'Bus Deleted Successfully']);
    }
}
