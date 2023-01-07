@extends('admin.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">{{ config('languageString.send_notification') }}</h2>

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

                            @foreach($languages as $language)
                                <div class="col-12">
                                    <div class="form-group">
                                        <label
                                            for="{{$language->language_code}}_title">{{$language->name}} {{config('languageString.title')}}
                                            <span
                                                class="error">*</span></label>
                                        <input type="text" class="form-control"
                                               @if($language->is_rtl == 1) dir="rtl" @endif
                                               name="{{ $language->language_code }}_title"
                                               id="{{ $language->language_code }}_title"
                                               placeholder="{{$language->name}}" required/>
                                        <div class="help-block with-errors error"></div>
                                    </div>
                                </div>
                            @endforeach

                            @foreach($languages as $language)
                                <div class="col-12">
                                    <div class="form-group">
                                        <label
                                            for="{{$language->language_code}}_message">{{$language->name}} {{config('languageString.message')}}
                                            <span
                                                class="error">*</span></label>
                                        <textarea class="form-control"
                                                  @if($language->is_rtl == 1) dir="rtl" @endif
                                                  name="{{ $language->language_code }}_message"
                                                  id="{{ $language->language_code }}_message"
                                                  placeholder="{{$language->name}}" required></textarea>
                                        <div class="help-block with-errors error"></div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="name">{{ config('languageString.image') }} <span
                                            class="error">*</span></label>
                                    <input type="file" class="form-control dropify"
                                           name="image"
                                           id="image"/>
                                </div>
                            </div>

                            @foreach($languages as $language)
                                <div class="col-12">
                                    <div class="form-group">
                                        <label
                                            for="{{ $language->language_code }}_name">{{ $language->name }} {{ config('languageString.description') }}
                                            <span class="error">*</span></label>
                                        <textarea class="form-control description"
                                                  name="{{ $language->language_code }}_description"
                                                  id="{{ $language->language_code }}_description"
                                                  required>
                                        </textarea>
                                        <div class="help-block with-errors error"></div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="col-12">
                                <div class="form-group mb-0 mt-3 justify-content-end">
                                    <div>
                                        <button type="submit"
                                                class="btn btn-success">{{ config('languageString.submit') }}
                                        </button>
                                        <a href="{{ route('admin.notification.index') }}"
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
    <script src="{{URL::asset('assets/js/custom/notification.js')}}?v={{ time() }}"></script>
@endsection
