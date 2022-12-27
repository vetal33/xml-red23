@extends('user.layouts.master')

@section('title') @lang('translation.Leaflet_Maps') @endsection

@section('css')
    <!-- leaflet Css -->
    <link href="{{ URL::asset('/assets/libs/leaflet/leaflet.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

{{--    @component('components.breadcrumb')
        @slot('li_1') Maps @endslot
        @slot('title') Leaflet Maps @endslot
    @endcomponent--}}

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div id="leaflet-map" class="leaflet-map"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->

@endsection
@section('script')
    <!-- leaflet plugin -->
    <script src="{{ URL::asset('/assets/libs/leaflet/leaflet.min.js') }}"></script>

    <!-- leaflet map.init -->
    <script src="{{ URL::asset('/assets/js/pages/leaflet-us-states.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/pages/leaflet-map.init.js') }}"></script>
@endsection
