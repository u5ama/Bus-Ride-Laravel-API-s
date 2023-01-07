@extends('admin.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h2 class="content-title mb-0 my-auto">Add Bus Station</h2>

            </div>
        </div>
    </div>
    <!-- breadcrumb -->
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBvRPR8W93pV4cHO6iEabc61OgS3-JPscY&callback=initMap&libraries=&v=weekly"
        defer
    ></script>
    <style type="text/css">
        /* Always set the map height explicitly to define the size of the div
         * element that contains the map. */
        #map {
            height: 100%;
        }

        /*!* Optional: Makes the sample page fill the window. *!*/
        /*html,*/
        /*body {*/
        /*    height: 100%;*/
        /*    margin: 0;*/
        /*    padding: 0;*/
        /*}*/
    </style>
    <script>

        function initMap() {
            const myLatlng = { lat:-33.918861 , lng:18.423300 };
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 6,
                center: myLatlng,
            });
            // Create the initial InfoWindow.
            let infoWindow = new google.maps.InfoWindow({
                content: "Click the map to get Lat/Lng!",
                position: myLatlng,
            });
            infoWindow.open(map);
            // Configure the click listener.
            map.addListener("click", (mapsMouseEvent) => {
                // Close the current InfoWindow.
                infoWindow.close();
                // Create a new InfoWindow.
                infoWindow = new google.maps.InfoWindow({
                    position: mapsMouseEvent.latLng,
                });
                infoWindow.setContent(
                    JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2)

                );
                infoWindow.open(map);
                var latandlongselected = mapsMouseEvent.latLng.toJSON();
                $('#station_long').val(latandlongselected.lng);
                $('#station_lat').val(latandlongselected.lat);
                $('#modaldemo3').modal('toggle');

            });

        }

    </script>
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
                                    <label for="station_name">Name <span
                                                class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="station_name"
                                           id="station_name"
                                           placeholder="Station Name" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="company_id">Company <span
                                                class="error">*</span></label>
                                    <select class="form-control select2" id="company_id" name="company_id" required>
                                        <option value="">{{ config('languageString.select_option') }}</option>
                                        @foreach($companies as $company)
                                            <option value="{{$company->id}}">{{$company->com_name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="address">Address <span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control"
                                           name="address"
                                           id="address"
                                           placeholder="Address" required/>
                                    <div class="help-block with-errors error"></div>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="station_lat">Add Location <span
                                        class="error">*</span></label>
                                <a type="button" class="btn btn-sm btn-outline-success waves-effect waves-light"  data-placement="top" title="User Detail" data-target="#modaldemo3" data-toggle="modal"><i class="fa fa-map-marker font-size-16 align-middle"></i></a>
                            </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="station_lat">Station Lat <span
                                                class="error">*</span></label>
                                        <input type="text" class="form-control"
                                               name="station_lat"
                                               id="station_lat"
                                               placeholder="Latitude" required readonly/>
                                        <div class="help-block with-errors error"></div>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="station_long">Station Long <span
                                                class="error">*</span></label>
                                        <input type="text" class="form-control"
                                               name="station_long"
                                               id="station_long"
                                               placeholder="Longitude" required readonly/>
                                        <div class="help-block with-errors error"></div>
                                    </div>
                                </div>

                            <div class="col-12">
                                <div class="form-group mb-0 mt-3 justify-content-end">
                                    <div>
                                        <button type="submit"
                                                class="btn btn-success">{{ config('languageString.submit') }}
                                        </button>
                                        <a href="{{ route('admin.bus_stations.index') }}"
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
    <div class="modal" id="modaldemo3">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">View Locations</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body" id="map" style="height: 600px">
                </div>
                <div class="modal-footer">
                    <button class="btn ripple btn-secondary" data-dismiss="modal" type="button">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{URL::asset('assets/js/custom/bus_station.js')}}?v={{ time() }}"></script>
@endsection
