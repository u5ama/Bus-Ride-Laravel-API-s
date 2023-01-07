@extends('admin.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">{{config('languageString.add_on_boarding')}}</h2>

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
                        <input type="hidden" id="edit_value" value="{{ $onBoarding->id }}" name="edit_value">
                        <input type="hidden" id="form-method" value="edit">

                        <div class="row row-sm">

                            @foreach($languages as $language)
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="{{ $language->language_code }}_header_text">{{ $language->name }} {{ config('languageString.header_text') }}
                                            <span class="error">*</span></label>
                                        <input type="text" class="form-control"
                                               name="{{ $language->language_code }}_header_text"
                                               id="{{ $language->language_code }}_header_text"
                                               value="{{ $onBoarding->translateOrNew($language->language_code)->header_text }}"
                                               @if($language->is_rtl==1) dir="rtl" @endif
                                               placeholder="{{ $language->name }} {{ config('languageString.header_text') }}"
                                               required/>
                                        <div class="help-block with-errors error"></div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="description">{{ config('languageString.description') }}
                                            <span class="error">*</span></label>
                                        <input type="text" class="form-control"
                                               name="{{ $language->language_code }}_description"
                                               id="{{ $language->language_code }}_description"
                                               value="{{ $onBoarding->translateOrNew($language->language_code)->description }}"
                                               @if($language->is_rtl==1) dir="rtl" @endif
                                               placeholder="{{ $language->name }} {{ config('languageString.description') }}"
                                               required/>
                                        <div class="help-block with-errors error"></div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="icon">{{ config('languageString.icon') }} <span
                                                class="error">*</span></label>
                                    <input type="file" class="form-control dropify"
                                           name="icon" data-default-file="{{url($onBoarding->icon)}}"
                                           id="icon"/>
                                </div>
                            </div>


                            <div class="col-12">
                                <div class="form-group">
                                    <label for="image">{{ config('languageString.image') }} <span
                                                class="error">*</span></label>
                                    <input type="file" class="form-control dropify"
                                           name="image" data-default-file="{{url($onBoarding->image)}}"
                                           id="image"/>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-0 mt-3 justify-content-end">
                                    <div>
                                        <button type="submit"
                                                class="btn btn-success">{{config('languageString.submit')}}</button>
                                        <a href="{{ route('admin.on-boarding.index') }}"
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
@endsection
@section('js')
    <script src="{{URL::asset('assets/js/custom/onBoarding.js')}}?v={{ time() }}"></script>
@endsection
