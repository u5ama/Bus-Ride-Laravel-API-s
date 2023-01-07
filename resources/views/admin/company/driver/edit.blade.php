@extends('admin.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">Edit Driver</h2>

            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <style>
        .iti__flag-container {
            max-height: 40px !important;
            background: #141b2d !important;
        }
        .iti--separate-dial-code .iti__selected-flag {
            background-color: #141b2d !important;
            color: white !important;
        }
    </style>
    <!-- row -->
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" data-parsley-validate="" id="addEditForm" role="form">
                        @csrf
                        <input type="hidden" id="edit_value" name="edit_value" value="{{ $driver->id }}">
                        <input type="hidden" id="company_id" name="company_id" value="{{ $company_id }}">
                        <input type="hidden" id="form-method" value="edit">
                        <input type="hidden" id="form-method" value="add">
                        <div class="row row-sm">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="name">Driver Name<span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="du_full_name"
                                           id="du_full_name"
                                           placeholder="Driver Name" value="{{$driver->du_full_name}}" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label for="screen_info">Driver Country Code<span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="country_code"
                                           id="phone"   maxlength="10"  value="{{$driver->du_country_code}}"
                                           placeholder="Driver Country required"/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-9">
                                <div class="form-group">
                                    <label for="screen_info">Driver Contact Number<span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="du_mobile_number"
                                           id="phone"   maxlength="10" value="{{$driver->du_mobile_number}}"
                                           placeholder="Driver Contact Number"  required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>


                            <div class="col-12">
                                <div class="form-group">
                                    <label for="screen_info">Driver Full Contact Number<span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="du_full_mobile_number"
                                           id="du_full_mobile_number"
                                           placeholder="Driver Full Contact Number" required
                                           value="{{$driver->du_full_mobile_number}}" readonly="" />
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>



                            <div class="col-12">
                                <div class="form-group">
                                    <label for="screen_info">Driver User Name<span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="du_user_name"
                                           id="du_user_name"
                                           placeholder="driver User Name" value="{{$driver->du_user_name}}" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="screen_info">Driver Email<span
                                            class="error">*</span></label>
                                    <input type="email" class="form-control"
                                           name="email"
                                           id="email"
                                           placeholder="Driver Email" value="{{$driver->email}}" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="password">Driver Password</label>
                                    <input type="password" class="form-control"
                                           name="password"
                                           id="password"
                                           placeholder="Driver Password"/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="dp_license_number">Driver Licence</label>
                                    <input type="text" class="form-control"
                                           name="dp_license_number"
                                           id="dp_license_number"
                                           placeholder="Driver Licence"
                                           value="@if(isset($driver_profile->dp_license_number)){{$driver_profile->dp_license_number}}@endif"/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>


                            <div class="col-12">
                                <div class="form-group">
                                    <label for="car_registration">Driver Car Registration Number</label>
                                    <input type="text" class="form-control"
                                           name="car_registration"
                                           id="car_registration"
                                           placeholder="Driver Car Registration Number"
                                           value="@if(isset($driver_profile->car_registration)){{$driver_profile->car_registration}}@endif"/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="dp_date_registration">Driver Car Registration Date</label>
                                    <input type="date" class="form-control"
                                           name="dp_date_registration"
                                           id="dp_date_registration"
                                           placeholder="Driver Car Registration Date"
                                           value="@if(isset($driver_profile->dp_date_registration)){{$driver_profile->dp_date_registration}}@endif"/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="image">Driver Image</label>
                                    <input type="file" class="form-control"
                                           name="du_profile_pic"
                                           id="du_profile_pic"
                                           placeholder="Driver Image"/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-0 mt-3 justify-content-end">
                                    <div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <a href="{{ url('admin/company/'.$company_id) }}"
                                           class="btn btn-secondary">Cancel</a>
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

    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
    <script src="{{URL::asset('assets/js/custom/driver.js')}}?v={{ time() }}"></script>
@endsection
