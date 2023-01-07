@extends('admin.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">Edit Bus Station</h2>

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
                        <input type="hidden" id="edit_value" name="edit_value" value="{{ $bus_station->id }}">
                        <input type="hidden" id="form-method" value="edit">

                        <div class="row row-sm">

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="station_name">Name <span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="station_name"
                                           id="station_name"
                                           placeholder="Station Name" required value="{{ $bus_station->station_name }}"/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="company_id">Company <span
                                            class="error">*</span></label>
                                    <select class="form-control select2" id="company_id" name="company_id" required>
                                        <option value="">{{ config('languageString.select_option') }}</option>
                                        @foreach($companies as $company)
                                            <option value="{{$company->id}}" @if($bus_station->company_id==$company->id) selected @endif>{{$company->com_name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="address">Address <span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="address"
                                           id="address"
                                           placeholder="Address" required value="{{ $bus_station->address }}"/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="station_lat">Station Lat <span
                                                class="error">*</span></label>
                                        <input type="text" class="form-control"
                                               name="station_lat"
                                               id="station_lat"
                                               placeholder="Latitude" required value="{{ $bus_station->station_lat }}"/>
                                        <div class="help-block with-errors error"></div>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="station_long">Station Long <span
                                                class="error">*</span></label>
                                        <input type="text" class="form-control"
                                               name="station_long"
                                               id="station_long"
                                               placeholder="Longitude" required value="{{ $bus_station->station_long }}"/>
                                        <div class="help-block with-errors error"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-0 mt-3 justify-content-end">
                                    <div>
                                        <button type="submit"
                                                class="btn btn-success">{{ config('languageString.submit') }}
                                        </button>
                                        <a href="{{ route('admin.buses.index') }}"
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
    <script src="{{URL::asset('assets/js/custom/bus_station.js')}}?v={{ time() }}"></script>
@endsection
