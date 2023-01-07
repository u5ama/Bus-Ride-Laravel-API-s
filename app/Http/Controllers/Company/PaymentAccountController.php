<?php

namespace App\Http\Controllers\Company;


use App\Models\PaymentAccounts;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class PaymentAccountController extends Controller
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
            $engines = PaymentAccounts::where('company_id',auth()->guard('company')->user()->id)->get();
            return Datatables::of($engines)
                ->addColumn('action', function($engines){
                    $edit_button = '<a href="' . route('company.payment_account.edit', [$engines->id]) . '" class="btn btn-info btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
                    $delete_button = '<button data-id="' . $engines->id . '" class="delete-single btn btn-danger btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '"><i class="bx bx-trash font-size-16 align-middle"></i></button>';
                    return '<div class="btn-icon-list">' . $edit_button . ' ' . $delete_button . '</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('company.payment_accounts.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Modelslication|Factory|View
     */
    public function create()
    {
        return view('company.payment_accounts.create');
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
            PaymentAccounts::create([
                'name'    => $request->name,
                'account_number'    => $request->account_number,
                'account_holder_name'    => $request->account_holder_name,
                'branch_code'    => $request->branch_code,
                'company_id'    => $user_id
            ]);
            return response()->json(['success' => true, 'message' => 'Payment Account Added Successfully']);
        } else{
            PaymentAccounts::where('id', $id)->update([
                'name'    => $request->name,
                'account_number'    => $request->account_number,
                'account_holder_name'    => $request->account_holder_name,
                'branch_code'    => $request->branch_code,
                'company_id'    => $user_id
            ]);

            return response()->json(['success' => true, 'message' => 'Payment Account Updated Successfully']);
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
        $payment_account = PaymentAccounts::find($id);
        if($payment_account){
            return view('company.payment_accounts.edit', [
                'payment_account'       => $payment_account,
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
        PaymentAccounts::where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => 'Payment Account Deleted Successfully']);
    }
}
