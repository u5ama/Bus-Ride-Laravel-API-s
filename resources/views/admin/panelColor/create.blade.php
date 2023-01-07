@extends('admin.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">{{config('languageString.add_panel_color')}}</h2>

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
                                    <label for="color_key_field">{{config('languageString.color_key_field')}}<span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="color_key_field"
                                           id="color_key_field"
                                           placeholder="{{config('languageString.color_key_field')}}" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="color_key_value">{{config('languageString.color_key_value')}}<span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="color_key_value"
                                           id="color_key_value"
                                           placeholder="{{config('languageString.color_key_value')}}" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="color_code">{{config('languageString.color_code')}}<span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="color_code"
                                           id="color_code"
                                           placeholder="{{config('languageString.color_code')}}" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                        <label for="theme">{{ config('languageString.theme') }}<span
                                            class="error">*</span></label>
                                    <select class="form-control select2" id="theme" name="theme" required>
                                        <option value="">{{ config('languageString.select_option') }}</option>
                                        <option value="1">{{ config('languageString.dark') }}</option>
                                        <option value="2">{{ config('languageString.light') }}</option>
                                    </select>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-0 mt-3 justify-content-end">
                                    <div>
                                        <button type="submit"
                                                class="btn btn-success">{{config('languageString.submit')}}</button>
                                        <a href="{{ route('admin.panel-color.index') }}"
                                           class="btn btn-secondary">{{config('languageString.cancel')}}</a>
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
    <script src="{{URL::asset('assets/js/custom/panelColor.js')}}?v={{ time() }}"></script>
@endsection
