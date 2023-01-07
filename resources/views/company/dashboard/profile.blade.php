@extends('company.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ config('languageString.edit_profile') }}</h4>

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
                        <input type="hidden" id="edit_value" value="{{ $user->id }}" name="edit_value">
                        <input type="hidden" id="form-method" value="edit">
                        <div class="row">

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="com_name">{{ config('languageString.name') }}<span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="com_name" placeholder="{{ config('languageString.name') }}"
                                           id="com_name" required value="{{ $user->com_name }}"/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>


                            <div class="col-12">
                                <div class="form-group">
                                    <label for="name">{{ config('languageString.image') }}<span
                                            class="error">*</span></label>
                                    <input type="file" class="form-control dropify"
                                           name="com_logo"
                                           data-default-file="{{URL::asset(auth()->guard('company')->user()->com_logo)}}"
                                           id="com_logo"/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group mb-0 mt-3 justify-content-end">
                                <div>
                                    <button type="submit"
                                            class="btn btn-primary">{{ config('languageString.submit') }}</button>
                                    <a href="{{ route('admin.dashboard') }}"
                                       class="btn btn-secondary">{{ config('languageString.cancel') }}</a>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{URL::asset('assets/js/custom/profile.js')}}?v={{ time() }}"></script>
@endsection
