@extends('company.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">Assign A Bus</h2>

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
                                    <label for="bus_id">Select Bus <span
                                                class="error">*</span></label>
                                    <select class="form-control select2" id="bus_id" name="bus_id" required>
                                        <option value="">{{ config('languageString.select_option') }}</option>
                                        @foreach($buses as $bus)
                                            <option value="{{$bus->id}}">{{$bus->name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="address">Select Driver <span
                                            class="error">*</span></label>
                                    <select class="form-control select2" id="driver_id" name="driver_id" required>
                                        <option value="">{{ config('languageString.select_option') }}</option>
                                        @foreach($drivers as $driver)
                                            <option value="{{$driver->id}}">{{$driver->du_full_name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="address">Select Route <span
                                            class="error">*</span></label>
                                    <select class="form-control select2" id="route_id" name="route_id" required>
                                        <option value="">{{ config('languageString.select_option') }}</option>
                                        @foreach($routes as $route)
                                            <option value="{{$route->id}}">{{$route->route_name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="start_time">Start Time <span
                                            class="error">*</span></label>
                                    <input type="time" class="form-control"
                                           name="start_time"
                                           id="start_time" autocomplete="off"
                                           placeholder="Enter Start Time" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="end_time">End Time <span
                                            class="error">*</span></label>
                                    <input type="time" class="form-control"
                                           name="end_time"
                                           id="end_time" autocomplete="off"
                                           placeholder="Enter End Time" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-0 mt-3 justify-content-end">
                                    <div>
                                        <button type="submit"
                                                class="btn btn-success">{{ config('languageString.submit') }}
                                        </button>
                                        <a href="{{ route('company.assign_bus.index') }}"
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
    <script src="{{URL::asset('assets/js/custom/company/assigned_bus.js')}}?v={{ time() }}"></script>
@endsection
