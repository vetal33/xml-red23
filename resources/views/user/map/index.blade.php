@extends('user.layouts.master')

@section('title')
    @lang('translation.Leaflet_Maps')
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/libs/toastr/toastr.min.css') }}">
    <link href="{{ asset('/assets/libs/leaflet/leaflet.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('/assets/css/map.css') }}" rel="stylesheet" type="text/css"/>

    <!-- DataTables -->
    <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css"/>

    <!-- Sweet Alert-->
    <link href="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css"/>
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
                    <input id="get-all-parcels" type="hidden" data-href= {{ route('map.get-all-parcels') }}>
                    <div id="map" class="leaflet-map" style="height: 50vh"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Ділянки</h4>
                    <div class="mb-3"></div>
                    <div class="data-table">
                        @include('user.map.partials.datatable', ['parcels' => $parcels])
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->

@endsection
@section('script')

    <script src="{{ asset('/assets/libs/leaflet/leaflet.min.js') }}"></script>
    <!-- toastr plugin -->
    <script src="{{ asset('/assets/libs/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('/assets/js/map.js') }}"></script>

    <!-- Required datatable js -->
    <script src="{{ asset('/assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('/assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('/assets/libs/pdfmake/pdfmake.min.js') }}"></script>
    <!-- Datatable init js -->
    <script src="{{ asset('/assets/js/pages/datatables.init.js') }}"></script>
    <!-- Sweet Alerts js -->
    <script src="{{ asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
@endsection
