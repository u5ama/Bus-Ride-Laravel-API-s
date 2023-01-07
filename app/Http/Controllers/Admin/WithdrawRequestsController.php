<?php

namespace App\Http\Controllers\Admin;


use App\Models\Buses;
use App\Models\Company;
use App\Models\PaymentAccounts;
use App\Models\Withdraw;
use Illuminate\Contracts\Foundation\App\Modelslication;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class WithdrawRequestsController extends Controller
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
            $engines = Withdraw::all();
            return Datatables::of($engines)
//                ->filter(function($query) use ($request){
//                    if(!empty($request->input('search'))){
//                        $query->orWhere('name', "%" . $request->input('search') . "%");
//                        $query->orWhere('account', 'LIKE', "%" . $request->input('search') . "%");
//                    }
//                })
                ->addColumn('company', function($engines) {
                    $company = Company::where('id', $engines->company_id)->first();
                    return $company->com_name;
                })
                ->addColumn('account_name', function($engines) {
                    $company = PaymentAccounts::where('id', $engines->account_id)->first();
                    return $company->name;
                })
                ->addColumn('account_number', function($engines) {
                    $company = PaymentAccounts::where('id', $engines->account_id)->first();
                    return $company->account_number;
                })
                ->addColumn('action', function($engines){
//                    $edit_button = '<a href="' . route('admin.withdraw_requests.edit', [$engines->id]) . '" class="btn btn-info btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
                    $delete_button = '<button data-id="' . $engines->id . '" class="delete-single btn btn-danger btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '"><i class="bx bx-trash font-size-16 align-middle"></i></button>';
                    return '<div class="btn-icon-list"> ' . $delete_button . '</div>';
                })
                ->rawColumns(['action','company','account_name','account_number'])
                ->make(true);
        }
        return view('admin.withdraw_requests.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.withdraw_requests.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
       //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        Withdraw::where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => 'Request Successfully']);
    }
}
