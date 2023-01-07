@extends('company.layouts.master')
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
                        <input type="hidden" id="edit_value" name="edit_value" value="{{ $bus->id }}">
                        <input type="hidden" id="form-method" value="edit">

                        <div class="row row-sm">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="name">Name <span
                                                class="error">*</span></label>
                                        <input type="text" class="form-control"
                                               name="name"
                                               id="name"
                                               placeholder="Bus Name" required value="{{ $bus->name }}"/>
                                        <div class="help-block with-errors error"></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="reg_number">Registration Number <span
                                                class="error">*</span></label>
                                        <input type="text" class="form-control"
                                               name="reg_number"
                                               id="reg_number"
                                               placeholder="Registration Number" required value="{{ $bus->reg_number }}"/>
                                        <div class="help-block with-errors error"></div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="station_lat">Color <span
                                                class="error">*</span></label>
                                        <input type="color" class="form-control"
                                               name="bus_color"
                                               id="bus_color"
                                               placeholder="Color" required value="{{ $bus->bus_color }}"/>
                                        <div class="help-block with-errors error"></div>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="total_seats">Total Seats <span
                                                class="error">*</span></label>
                                        <input type="text" class="form-control"
                                               name="total_seats"
                                               id="total_seats"
                                               placeholder="Total Seats" required value="{{ $bus->total_seats }}"/>
                                        <div class="help-block with-errors error"></div>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="per_seat_charge">Per Seat Fare <span
                                                class="error">*</span></label>
                                        <input type="text" class="form-control"
                                               name="per_seat_charge"
                                               id="per_seat_charge"
                                               placeholder="Per Seat Fare" required value="{{ $bus->per_seat_charge }}"/>
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
                                        <a href="{{ route('company.buses.index') }}"
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
    <script src="{{URL::asset('assets/js/custom/company/buses.js')}}?v={{ time() }}"></script>
@endsection
