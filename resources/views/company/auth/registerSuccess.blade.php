@extends('company.layouts.master2')

@section('content')
    <style>
        .btn-main-primary{
            color: #242525;
            background-color: #0CA776 !important;
            border-color: #0CA776 !important;
        }
    </style>
    <!-- Page -->
    <div class="page">
        <div class="container-fluid">
            <div class="row no-gutter" style="justify-content: center;">
                <!-- The content half -->
                <div class="col-md-6 col-lg-6 col-xl-5">
                    <div class="login d-flex align-items-center py-2">
                        <!-- Demo content-->
                        <div class="container p-0">
                            <div class="row">
                                <div class="col-md-10 col-lg-10 col-xl-9 mx-auto">
                                    <div class="card-sigin">
                                        <div class="mb-5 d-flex text-center justify-content-center">
                                            <img src="{{URL::asset('assets/img/brand/logo.png')}}" alt="" style="width: 50%">
                                        </div>
                                        <div class="card-sigin">
                                            <div class="main-signup-header">
                                                <h5 class="font-weight-semibold mb-4" style="text-transform: none !important;">Thank you for Signing up a business account with GGO Booking; One of our representatives will contact you shortly for your business account verification.</h5>
                                                <div class="main-signin-footer mt-3">
                                                    <a href="{{ url('company/login') }}">
                                                        <button class="btn btn-main-primary btn-block">
                                                            Continue as Login
                                                        </button>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- End -->
                    </div>
                </div><!-- End -->
            </div>
        </div>
    </div>
    <!-- End Page -->
@endsection
@section('js')
@endsection
