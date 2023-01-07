@extends('company.layouts.master')
@section('css')
    <link href="{{URL::asset('assets/plugins/fancybox/jquery.fancybox.css')}}" rel="stylesheet">
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">Drivers</h2>

            </div>
        </div>

        <div class="d-flex my-xl-auto right-content">
            <div class="pr-1 mb-3 mb-xl-0">
                <a href="{{ route('company.driver.create') }}" class="btn btn-primary  mr-2">
                    <i class="mdi mdi-plus-circle"></i> Add New Driver
                </a>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <!-- row opened -->

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
{{--                    <div class="border-bottom mb-3">--}}
{{--                        <h6 class="card-title">Drivers</h6>--}}
{{--                    </div>--}}
                    <div class="table-responsive">
                        <table class="table mg-b-0 text-md-nowrap" id="data-table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Contact Number</th>
                                <th>Created at</th>
                                <th>Driver Registration In App</th>
                                <th>Status</th>
                                <th>Change Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @if(isset($drivers) && !empty($drivers))
                                @foreach($drivers as $driver)

                                    <tr>
                                        <td>{{ $driver->id }}</td>
                                        <td>@if(!empty($driver->du_profile_pic))
                                                <img src="{{asset($driver->du_profile_pic)}}" width="100" heigh="100">
                                            @else
                                                @php $url = asset('assets/default/driver.png') @endphp
                                                <img src="{{$url}}" width="100" heigh="100">
                                            @endif
                                        </td>
                                        <td>{{ $driver->du_full_name }}</td>
                                        <td>{{ $driver->email }}</td>
                                        <td>{{ $driver->du_full_mobile_number }}</td>
                                        <td>{{ $driver->du_created_at }}</td>
                                        <td>
                                            @if($driver->du_is_reg_active == "0")
                                                @php
                                                    $class_reg = "badge badge-danger";
                                                    $name_reg = "Not Allow";
                                                @endphp
                                            @endif

                                            @if($driver->du_is_reg_active == '1')
                                                @php
                                                    $class_reg = "badge badge-success";
                                                    $name_reg = "Allow";
                                                @endphp
                                            @endif

                                            <a type="button" class="{{$class_reg}}" data-toggle="tooltip"
                                               data-placement="top" onclick="changeDriverRegStatus('{{$driver->id}}','{{$driver->du_is_reg_active}}','{{$company->id}}')">{{$name_reg}}</a>
                                        </td>
                                        @if($driver->DriverProfile !== null)
                                            @if($driver->is_signup_mobile == 1)
                                                @if($driver->is_company_update == 0)
                                                    <td colspan="2">
                                                        <input type="hidden" name="driver_id" id="driver_id" value="{{$driver->id}}">
                                                        <select name="is_company_update" id="is_company_update" class="form-control select2" onchange="updateCompanyStatus(this);">
                                                            <option value="">{{ 'Select One' }}</option>
                                                            {{ \App\Utility\Utility::create_option("companies","id","com_name") }}
                                                        </select>
                                                    </td>
                                                @else
                                                    <td>
                                                        @if($driver->du_driver_status == "driver_status_when_block")
                                                            @php
                                                                $class = "badge badge-danger";
                                                                $name = "Block";
                                                            @endphp
                                                        @endif
                                                        @if($driver->du_driver_status == 'driver_status_when_pending')
                                                            @php
                                                                $class = "badge badge-warning";
                                                                $name = "Pending";
                                                            @endphp
                                                        @endif
                                                        @if($driver->du_driver_status == 'driver_status_when_approved')
                                                            @php
                                                                $class = "badge badge-success";
                                                                $name = "Approve";
                                                            @endphp
                                                        @endif

                                                        <a type="button" class="{{$class}}" data-toggle="tooltip"
                                                           data-placement="top">{{$name}}</a>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $select_option = '<select class="form-control" onchange="updateDriverStatus(' . $driver->id . ',' . $company->id . ')" id="driver_status_' . $driver->id . '">';
                                                        $select_option .= ($driver->du_driver_status == "driver_status_when_block") ? "<option value='driver_status_when_block' selected>Block</option>" : "<option value='driver_status_when_block'  >Block</option>";
                                                        $select_option .= ($driver->du_driver_status == "driver_status_when_pending") ? "<option value='driver_status_when_pending' selected>Pending</option>" : "<option value='driver_status_when_pending' >Pending</option>";
                                                        $select_option .= ($driver->du_driver_status == "driver_status_when_approved") ? "<option value='driver_status_when_approved' selected>Approve</option>" : "<option value='driver_status_when_approved' >Approve</option>";
                                                        $select_option .= "</select>";
                                                        echo $select_option;
                                                        ?>
                                                    </td>
                                                @endif
                                            @else
                                                <td>
                                                    @if($driver->du_driver_status == "driver_status_when_block")
                                                        @php
                                                            $class = "badge badge-danger";
                                                            $name = "Block";
                                                        @endphp
                                                    @endif
                                                    @if($driver->du_driver_status == 'driver_status_when_pending')
                                                        @php
                                                            $class = "badge badge-warning";
                                                            $name = "Pending";
                                                        @endphp
                                                    @endif
                                                    @if($driver->du_driver_status == 'driver_status_when_approved')
                                                        @php
                                                            $class = "badge badge-success";
                                                            $name = "Approve";
                                                        @endphp
                                                    @endif

                                                    <a type="button" class="{{$class}}" data-toggle="tooltip"
                                                       data-placement="top">{{$name}}</a>
                                                </td>
                                                <td>
                                                    <?php
                                                    $select_option = '<select class="form-control" onchange="updateDriverStatus(' . $driver->id . ',' . $company->id . ')" id="driver_status_' . $driver->id . '">';
                                                    $select_option .= ($driver->du_driver_status == "driver_status_when_block") ? "<option value='driver_status_when_block' selected>Block</option>" : "<option value='driver_status_when_block'  >Block</option>";
                                                    $select_option .= ($driver->du_driver_status == "driver_status_when_pending") ? "<option value='driver_status_when_pending' selected>Pending</option>" : "<option value='driver_status_when_pending' >Pending</option>";
                                                    $select_option .= ($driver->du_driver_status == "driver_status_when_approved") ? "<option value='driver_status_when_approved' selected>Approve</option>" : "<option value='driver_status_when_approved' >Approve</option>";
                                                    $select_option .= "</select>";
                                                    echo $select_option;
                                                    ?>
                                                </td>
                                            @endif
                                        @else
                                            <td colspan="2">
                                                <p>Driver Profile Not Completed</p>
                                            </td>
                                        @endif
                                        {{--                                    @else--}}
                                        {{--                                        <td>--}}
                                        {{--                                            <select name="is_company_update" id="is_company_update" class="form-control">--}}
                                        {{--                                                <option value="">{{ 'Select One' }}</option>--}}
                                        {{--                                                {{ \App\Utility\Utility::create_option("companies","id","com_name") }}--}}
                                        {{--                                            </select>--}}
                                        {{--                                        </td>--}}
                                        {{--                                    @endif--}}

                                        <td>
                                            <div class="btn-icon-list">
                                                <a href="{{ route('company.driver.edit',[$driver->id]) }}"
                                                   class="btn btn-info btn-icon"
                                                   data-effect="effect-fall"
                                                   data-id="{{ $driver->id }}"
                                                   data-toggle="tooltip" data-placement="top"
                                                   title="Edit">
                                                    <i class="bx bx-pencil font-size-16 align-middle"></i>
                                                </a>&nbsp;
                                                {{--
                                                                                            <a type="button" data-rideid="{{ $driver->id }}" class="driver-details btn btn-info btn-icon" data-effect="effect-fall" data-placement="top" title="Driver Detail" data-target="#modaldemo3" data-toggle="modal"><i class="fas fa-eye font-size-16 align-middle"></i></a>
                                                --}}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--/div-->
    </div>
    <!-- /row -->
    </div>
    <!-- Container closed -->
    </div>



    <div class="modal fade" id="modaldemo3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="globalModalTitle">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="globalModalDetails"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
    <script src="{{URL::asset('assets/plugins/fancybox/jquery.fancybox.js')}}"></script>
    <script src="{{URL::asset('assets/js/custom/company/CompanyDetail.js')}}"></script>
@endsection
