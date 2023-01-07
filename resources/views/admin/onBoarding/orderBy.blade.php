@extends('admin.layouts.master')
<style>
    #sortable-row {
        list-style: none;
    }

    #sortable-row li {
        margin-bottom: 4px;
        padding: 10px;
        background-color: #BBF4A8;
        cursor: move;
    }

    #sortable-row li.ui-state-highlight {
        height: 1.0em;
        background-color: #F0F0F0;
        border: #ccc 2px dotted;
    }
</style>
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">{{config('languageString.on_boarding_order_by')}}</h2>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <!-- row -->
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">

                    <form name="frmQA" method="POST">
                        <input type="hidden" name="row_order" id="row_order"/>
                        <ul id="sortable-row" class="list-group">
                            @foreach($order_by as $order)
                                <li id="{{$order->id}}" class='list-group-item'>{{$order->on_boarding_order_by}} - {{$order->header_text}}</li>
                            @endforeach
                        </ul>

                        <div class="form-group mb-0">
                            <div>
                                <button type="button" id="btnSave" class="btn btn-success mt-3">Save
                                    Order
                                </button>
                                <a href="{{ route('admin.on-boarding.index') }}"
                                   class="btn btn-secondary waves-effect  mt-3">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                    <!-- container-fluid -->
                </div>
                <!-- End Page-content -->
            </div>
        </div>
        @endsection
        @section('js')
            <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
            <script src="{{URL::asset('assets/js/custom/onBoarding.js')}}?v={{ time() }}"></script>
@endsection
