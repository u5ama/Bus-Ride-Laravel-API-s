@extends('admin.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">Add Route</h2>
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
                                    <label for="route_name">Name <span
                                                class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="route_name"
                                           id="route_name"
                                           placeholder="Route Name" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-12" id="dynamic_field">
                                <div class="row">
                                    <div class="col-10">
                                        <div class="form-group">
                                            <label for="station_id">Stations <span
                                                    class="error">*</span></label>
                                            <select class="form-control select2"
                                                    name="station_id[]"
                                                    id="station_id"
                                                    required>
                                                <option value="" selected>Select Station</option>
                                                @foreach($stations as $station)
                                                    <option value="{{$station->id}}">{{$station->station_name}}</option>
                                                @endforeach
                                            </select>
                                            <div class="help-block with-errors error"></div>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <button type="button" name="add" id="add" class="btn btn-success" style="margin-top: 30px;">Add More</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-0 mt-3 justify-content-end">
                                    <div>
                                        <button type="submit" class="btn btn-success">{{ config('languageString.submit') }}
                                        </button>
                                        <a href="{{ route('admin.routes.index') }}"
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
    <script src="{{URL::asset('assets/js/custom/routes.js')}}"></script>
    <script>
        $(document).ready(function(){
            let i = 1;
            $('#add').click(function(){
                i++;
                // $('#dynamic_field').append('<tr id="row'+i+'" class="dynamic-added"><td><input type="text" name="name[]" placeholder="Enter your Name" class="form-control name_list" /></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');
                $('#dynamic_field').append('<div class="row" id="row'+i+'"><div class="col-10"> <div class="form-group"> <label for="station_id">Stations <span class="error">*</span></label> <select class="form-control select2"name="station_id[]"id="station_id"required> <option value="" selected>Select Station</option>@foreach($stations as $station) <option value="{{$station->id}}">{{$station->station_name}}</option>@endforeach </select> <div class="help-block with-errors error"></div> </div> </div> <div class="col-2"> <button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove" style="margin-top: 30px;">X</button> </div></div>');
            });


            $(document).on('click', '.btn_remove', function(){
                const button_id = $(this).attr("id");
                $('#row'+button_id+'').remove();
            });
        });
    </script>
@endsection
