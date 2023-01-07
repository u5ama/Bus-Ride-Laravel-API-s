@extends('company.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">Edit Payment Account</h2>

            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <!-- row -->
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" data-parsley-validate="" id="addEditForm" role="form">
                        @csrf
                        <input type="hidden" id="edit_value" name="edit_value" value="{{ $payment_account->id }}">
                        <input type="hidden" id="form-method" value="edit">

                        <div class="row row-sm">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="account_holder_name">Account Holder Name <span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="account_holder_name"
                                           id="account_holder_name"
                                           placeholder="Account Holder Name"  value="{{ $payment_account->account_holder_name }}"required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-12">
                                    <div class="form-group">
                                        <label for="name">Account Title <span
                                                class="error">*</span></label>
                                        <input type="text" class="form-control"
                                               name="name"
                                               id="name"
                                               placeholder="Account Title" required value="{{ $payment_account->name }}"/>
                                        <div class="help-block with-errors error"></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="account_number">Account Number <span
                                                class="error">*</span></label>
                                        <input type="text" class="form-control"
                                               name="account_number"
                                               id="account_number"
                                               placeholder="Account Number" required value="{{ $payment_account->account_number }}"/>
                                        <div class="help-block with-errors error"></div>
                                    </div>
                                </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="branch_code">Branch Code <span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="branch_code"
                                           id="branch_code"
                                           placeholder="Branch Code" value="{{ $payment_account->branch_code }}" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-0 mt-3 justify-content-end">
                                    <div>
                                        <button type="submit"
                                                class="btn btn-success">{{ config('languageString.submit') }}
                                        </button>
                                        <a href="{{ route('company.payment_account.index') }}"
                                           class="btn btn-secondary">{{ config('languageString.cancel') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /row -->

@endsection
@section('js')
    <script src="{{URL::asset('assets/js/custom/company/payment_account.js')}}?v={{ time() }}"></script>
@endsection
