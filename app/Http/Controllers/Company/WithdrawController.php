<?php

namespace App\Http\Controllers\Company;


use App\Models\PaymentAccounts;
use App\Models\Withdraw;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class WithdrawController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Modelslication|Factory|View
     * @throws \Exception
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $engines = Withdraw::where('company_id',auth()->guard('company')->user()->id)->get();
            return Datatables::of($engines)
                ->addColumn('account', function($engines) {
                    $company = PaymentAccounts::where('id', $engines->account_id)->first();
                    return $company->name;
                })
//                ->addColumn('action', function($engines){
////                    $edit_button = '<a href="' . route('company.withdraw.edit', [$engines->id]) . '" class="btn btn-info btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
////                    $delete_button = '<button data-id="' . $engines->id . '" class="delete-single btn btn-danger btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '"><i class="bx bx-trash font-size-16 align-middle"></i></button>';
//                    return '<div class="btn-icon-list"></div>';
//                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('company.withdraw.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Modelslication|Factory|View
     */
    public function create()
    {
        $accounts = PaymentAccounts::where('company_id',auth()->guard('company')->user()->id)->get();
        return view('company.withdraw.create',compact('accounts'));
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
            Withdraw::create([
                'amount'    => $request->amount,
                'account_id'    => $request->account_id,
                'company_id'    => $user_id
            ]);
            return response()->json(['success' => true, 'message' => 'Withdraw Request Added Successfully']);
        } else{
            Withdraw::where('id', $id)->update([
                'amount'    => $request->amount,
                'account_id'    => $request->account_id,
                'company_id'    => $user_id
            ]);

            return response()->json(['success' => true, 'message' => 'Withdraw Request Updated Successfully']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Modelslication|Factory|View
     */
    public function edit($id)
    {
        $payment_account = Withdraw::find($id);
        if($payment_account){
            return view('company.withdraw.edit', [
                'withdraw'       => $payment_account,
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
        Withdraw::where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => 'Withdraw Request Deleted Successfully']);
    }
}
