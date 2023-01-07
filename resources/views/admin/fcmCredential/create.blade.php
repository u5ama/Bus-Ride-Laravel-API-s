@extends('admin.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">{{ config('languageString.add_fcm_credential') }}</h2>

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
                                    <label for="project_name">{{ config('languageString.project_name') }}<span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="project_name"
                                           id="project_name"
                                           placeholder="{{ config('languageString.project_name') }}" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="server_key">{{ config('languageString.server_key') }}<span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="server_key"
                                           id="server_key" autocomplete="off"
                                           placeholder="{{ config('languageString.server_key') }}" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="sender_id">{{ config('languageString.sender_id') }}<span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="sender_id" autocomplete="off"
                                           id="sender_id"
                                           placeholder="{{ config('languageString.sender_id') }}" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-0 mt-3 justify-content-end">
                                    <div>
                                        <button type="submit"
                                                class="btn btn-success">{{ config('languageString.submit') }}
                                        </button>
                                        <a href="{{ route('admin.fcm-credential.index') }}"
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
    <script src="{{URL::asset('assets/js/custom/fcmCredential.js')}}?v={{ time() }}"></script>
@endsection
