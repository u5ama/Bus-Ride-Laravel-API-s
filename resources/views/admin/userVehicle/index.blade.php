@extends('admin.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">{{config('languageString.user_vehicle')}}</h2>

            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <!-- row opened -->
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mg-b-0 text-md-nowrap" id="data-table">
                            <thead>
                            <tr>
                                <th>{{config('languageString.no')}}</th>
                                <th>{{config('languageString.creation_time')}}</th>
                                <th> {{config('languageString.user_name')}}</th>
                                <th> {{config('languageString.model_name')}}</th>
                                <th> {{config('languageString.year')}}</th>
                                <th> {{config('languageString.body_name')}}</th>
                                <th> {{config('languageString.engine_name')}}</th>
                                <th> {{config('languageString.fuel_name')}}</th>
                                <th> {{config('languageString.updation_time')}}</th>
                                <th>{{config('languageString.actions')}}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--/div-->
    </div>

@endsection
@section('js')
    <script>
        const sweetalert_title = "{{ config('languageString.user_vehicle_destroy').'?' }}";
        const sweetalert_text = "{{ config('languageString.sweetalert_text') }}";
        const confirmButtonText = "{{ config('languageString.yes_delete_it') }}";
        const cancelButtonText = "{{ config('languageString.no_cancel_plx') }}";
    </script>
    <script src="{{URL::asset('assets/js/custom/userVehicle.js')}}"></script>
@endsection
