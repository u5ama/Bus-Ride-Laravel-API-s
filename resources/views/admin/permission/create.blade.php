@extends('admin.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">{{ config('languageString.add_permission') }}</h2>

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
                        <input type="hidden" id="form-method" value="add">
                        <div class="row row-sm">

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="module_name">{{ config('languageString.module_name') }}<span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control" data-parsley-pattern="/^[A-Za-z ]+$/"
                                           name="module_name"
                                           id="module_name"
                                           placeholder="admin" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="name">{{ config('languageString.choose_permission') }}<span
                                            class="error">*</span></label><br>
                                    <div class="form-check form-check-inline">
                                        <label class="ckbox mb-2">
                                            <input type="checkbox" id="all" name="all"
                                                   value="1">
                                            <span>{{ config('languageString.select_all') }}</span>
                                        </label>
                                    </div>
                                    @foreach($array as $value)
                                        <div class="form-check form-check-inline">
                                            <label class="ckbox mb-2">
                                                <input type="checkbox" id="{{$value}}" name="{{$value}}"
                                                       value="1">
                                                <span>{{ config('languageString.'.$value) }}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-0 mt-3 justify-content-end">
                                    <div>
                                        <button type="submit"
                                                class="btn btn-success">{{ config('languageString.submit') }}
                                        </button>
                                        <a href="{{ route('admin.role.index') }}"
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
    <script src="{{URL::asset('assets/js/custom/permission.js')}}?v={{ time() }}"></script>
@endsection
