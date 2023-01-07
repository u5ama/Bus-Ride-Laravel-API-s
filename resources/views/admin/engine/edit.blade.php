@extends('admin.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">{{config('languageString.edit_engine')}}</h2>

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
                        <input type="hidden" id="edit_value" name="edit_value" value="{{ $engine->id }}">
                        <input type="hidden" id="form-method" value="edit">

                        <div class="row row-sm">

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="vehicle_type_id">{{ config('languageString.vehicle_type_name') }} <span
                                                class="error">*</span></label>
                                    <select name="vehicle_type_id" class="form-control select2" id="vehicle_type_id">
                                        <option value="">{{config('languageString.vehicle_type_name')}}</option>
                                        @foreach($vehicleTypes as $vehicleType)
                                            <option value="{{$vehicleType->id}}"
                                                    @if($engine->vehicle_type_id==$vehicleType->id) selected @endif>{{$vehicleType->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            @foreach($languages as $language)
                                <div class="col-12">
                                    <div class="form-group">
                                        <label
                                                for="{{ $language->language_code }}_name">{{ $language->name }} {{ config('languageString.name') }}
                                            <span
                                                    class="error">*</span></label>
                                        <input type="text" class="form-control"
                                               @if($language->is_rtl==1) dir="rtl" @endif
                                               name="{{ $language->language_code }}_name"
                                               id="{{ $language->language_code }}_name"
                                               value="{{ $engine->translateOrNew($language->language_code)->name }}"
                                               placeholder="{{ $language->name }} Name" required/>
                                        <div class="help-block with-errors error"></div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="col-12">
                                <div class="form-group mb-0 mt-3 justify-content-end">
                                    <div>
                                        <button type="submit"
                                                class="btn btn-success">{{config('languageString.submit')}}</button>
                                        <a href="{{ route('admin.engine.index') }}"
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
    <script src="{{URL::asset('assets/js/custom/engine.js')}}?v={{ time() }}"></script>
@endsection
