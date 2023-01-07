@extends('admin.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">{{ config('languageString.notification_details') }}</h2>
                <input type="hidden" name="user_id" id="user_id" value="{{$notifications->id}}">
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="row row-sm">
        <div class="col-xl-12 py-2">
            <div class="card h-100">
                <div class="card-body">
                    <p class="card-title mb-3"></p>
                    <div class="row border-top  p-2">
                        <div class="col-md-6">
                            <p class="mb-0">{{ config('languageString.id') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-0">{{$notifications->id}}</p>
                        </div>
                    </div>
                    <div class="row border-top  p-2">
                        <div class="col-md-6">
                            <p class="mb-0">{{ config('languageString.title') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-0">{{$notifications->title}}</p>
                        </div>
                    </div>
                    <div class="row border-top border-bottom p-2">
                        <div class="col-md-6">
                            <p class="mb-0">{{ config('languageString.creation_time') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-0">{{date('d-m-Y H:i:s',strtotime($notifications->created_at))}}</p>
                        </div>
                    </div>
                    <div class="row  border-bottom p-2">
                        <div class="col-md-6">
                            <p class="mb-0">{{ config('languageString.message') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-0">{{$notifications->message}}</p>
                        </div>
                    </div>

                    <div class="row border-bottom p-2">
                        <div class="col-md-6">
                            <p class="mb-0">{{ config('languageString.description') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-0">{{$notification_translation->description}}</p>
                        </div>
                    </div>

                    <div class="row border-bottom p-2">
                        <div class="col-md-6">
                            <p class="mb-0">{{ config('languageString.image') }}</p>
                        </div>
                        <div class="col-md-6">
                            <img src="{{ url($notifications->image) }}" class="mb-0" style="max-height: 200px; max-width: 200px;"/>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!----vehicle modal start here---->




@endsection
@section('js')
    <script src="{{URL::asset('assets/js/custom/notification.js')}}"></script>
@endsection
