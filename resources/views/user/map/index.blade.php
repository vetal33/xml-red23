@extends('user.layouts.master')

@section('title') @lang('translation.Leaflet_Maps') @endsection

@section('css')
{{--   <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
          integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
          crossorigin=""/>--}}
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/libs/toastr/toastr.min.css') }}">
    <link href="{{ asset('/assets/libs/leaflet/leaflet.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/assets/css/map.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

{{--    @component('components.breadcrumb')
        @slot('li_1') Maps @endslot
        @slot('title') Leaflet Maps @endslot
    @endcomponent--}}

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">

                <div class="d-sm-flex flex-wrap">
                    <h4 class="card-title mb-4">Interactive Choropleth Map</h4>
                    <div class="ms-auto">
                        <ul class="nav nav-pills">
                            <li class="nav-item">
                                <a type="button" class="btn btn-light waves-effect">
                                    <i class="bx bx-add-to-queue font-size-16 align-middle me-2"></i> Add
                                    <input type="file" id="file-form-jsonFile" data-href="{{ route('map.upload-json') }}"
                                           accept="text/json">
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>



                <div id="map" class="leaflet-map" style="height: 50vh"></div>
            </div>
        </div>
    </div>
</div>
<!-- end row -->

@endsection
@section('script')
{{--    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
            integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM="
            crossorigin=""></script>--}}
<script src="{{ asset('/assets/libs/leaflet/leaflet.min.js') }}"></script>
<!-- toastr plugin -->
<script src="{{ URL::asset('/assets/libs/toastr/toastr.min.js') }}"></script>
   {{--<script src="{{ asset('/assets/js/map/base-layers.js') }}"></script>--}}
   <script src="{{ asset('/assets/js/map.js') }}"></script>
{{-- <!-- leaflet plugin -->
    <script src="{{ URL::asset('/assets/libs/leaflet/leaflet.min.js') }}"></script>

    <!-- leaflet map.init -->
    <script src="{{ URL::asset('/assets/js/pages/leaflet-us-states.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/pages/leaflet-map.init.js') }}"></script>--}}
@endsection
