@extends('user.layouts.master')

@section('title')
    User xml validator
@endsection

@section('css')
    <link href="{{ asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css"/>

    <!-- owl.carousel css -->
    <link rel="stylesheet" href="{{ URL::asset('/assets/libs/owl.carousel/owl.carousel.min.css') }}">
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1')
            User
        @endslot
        @slot('title')
            Xml Validator
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="mt-2 mb-2">
                                <div>
                                    <form action="{{ route('xml-validator.upload') }}" method="POST"
                                          enctype="multipart/form-data">
                                        @csrf
                                        <div class="input-group">
                                            <input name="file" type="file" class="form-control" id="inputGroupFile04"
                                                   aria-describedby="inputGroupFileAddon04" aria-label="Upload"
                                                   value="{{ $xmlNormative ? $xmlNormative->path : '' }}"
                                                   accept="text/xml">
                                            <button
                                                class="btn @if($xmlNormative) btn-secondary @else btn-primary @endif"
                                                type="submit" id="inputGroupFileAddon04">
                                                Завантажити
                                            </button>

                                        </div>
                                        @error('file')
                                        <div class="text-danger mt-2">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </form>
                                </div>

                                @if($xmlNormative)
                                    <form
                                        action="{{ Session::has('structure') ? route('xml-validator.geom-validate', ['file' => $xmlNormative->id]) : route('xml-validator.structure-validate', ['file' => $xmlNormative->id]) }}"
                                        method="POST">
                                        @csrf
                                        <input type="hidden" name="file">
                                        <input type="hidden" id="zone-route"
                                               value="{{ route('xml-validator.geom-zone-validate') }}">
                                        <input type="hidden" id="region-route"
                                               value="{{ route('xml-validator.geom-region-validate') }}">
                                        <div class="d-sm-flex flex-wrap mt-4">
                                            <h4 class="card-title mb-2 mt-2">Перевірка <span
                                                    class="text-primary @if(Session::has('validationErrors')) text-danger @elseif(Session::has('geom')) text-success @endif">{{ $xmlNormative->name }}</span>
                                            </h4>
                                            <div class="ms-auto">
                                                <ul class="nav nav-pills">
                                                    <li class="nav-item">
                                                        @if (Session::has('structure'))
                                                            <a href="{{ route('xml-validator.geom-validate', ['file' => $xmlNormative->id]) }}"
                                                               id="xml-validation-geom" class="nav-link active">
                                                                Перевірити
                                                            </a>
                                                        @else
                                                            <button id="xml-validation" type="button"
                                                                    class="nav-link active">
                                                                Перевірити
                                                            </button>
                                                        @endif
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div>
                                            @error('uploadFile')
                                            <div class="row">
                                                <div class="col-12 text-danger mt-2">
                                                    {{ $message }}
                                                </div>
                                            </div>
                                            @enderror
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->

    @if($xmlNormative)
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="hori-timeline">
                            <div class="owl-carousel owl-theme  navs-carousel events" id="timeline-carousel">
                                <div class="item event-list">
                                    <div>
                                        <div class="event-date">
                                            <h5 class="mb-4">Структура</h5>
                                        </div>
                                        <div class="event-down-icon">
                                            @if (Session::has('structure') || Session::has('geom'))
                                                <i class="mdi mdi-checkbox-marked-circle-outline h1 text-success down-arrow-icon"></i>
                                            @else
                                                <i class="mdi mdi-checkbox-blank-circle-outline h1 @if (Session::has('validationErrors')) text-danger @else text-primary @endif down-arrow-icon"></i>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="item event-list">
                                    <div>
                                        <div class="event-date">
                                            <h5 class="mb-4">Геометрія</h5>
                                        </div>
                                        <div class="event-down-icon">
                                            @if (Session::has('geom'))
                                                <i class="mdi mdi-checkbox-marked-circle-outline h1 text-success down-arrow-icon"></i>
                                            @else
                                                <i class="mdi mdi-checkbox-blank-circle-outline h1 text-primary down-arrow-icon icon-geom"></i>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="item event-list">
                                    <div class="table-responsive" data-simplebar style="max-height: 137px;">
                                        <table class="table align-left table-nowrap process-table">
                                            <tbody>
                                            <tr class="d-none" id="zone-validate-area">
                                                <td style="width: 50px;" class="spinner">
                                                    <i class="bx bx-loader bx-spin font-size-16 align-middle me-2"></i>
                                                </td>
                                                <td class="text-left">
                                                    <p class="text-muted mb-0">Перевірка площ Зон</p>
                                                </td>
                                            </tr>
                                            <tr class="d-none" id="region-validate-area">
                                                <td style="width: 50px;" class="spinner">
                                                    <i class="bx bx-loader bx-spin font-size-16 align-middle me-2"></i>
                                                </td>
                                                <td class="text-left">
                                                    <p class="text-muted mb-0">Перевірка площ Районів</p>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end card -->
            </div>
        </div>

        @if (Session::has('validationErrors'))
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-sm-flex flex-wrap">
                                <h4 class="card-title mt-3 mb-3">Помилки валідації <span class="text-danger">({{ count(Session::get('validationErrors')) }} помилок)</span>
                                </h4>
                                <div class="ms-auto">
                                    <ul class="nav nav-pills">
                                        <li class="nav-item me-3">
                                            <a class="btn btn-outline-primary validation-error-export"
                                               href="{{ route('validator-xml.print-errors-pdf', ['file' => $xmlNormative->id]) }}">
                                                <i class="bx bxs-file-pdf font-size-16 align-middle me-2"></i>Експорт</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                <thead>
                                <tr>
                                    <th>Текст</th>
                                    <th>Рядок</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach(Session::get('validationErrors') as $error)
                                    <tr>
                                        <td>{{ $error->message }}</td>
                                        <td>{{ $error->line }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- end card -->
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        @endif
    @endif

    <div id="geom-errors"></div>

@endsection

@section('script')
    <!-- Required datatable js -->
    <script src="{{ asset('/assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('/assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('/assets/libs/pdfmake/pdfmake.min.js') }}"></script>
    <!-- Datatable init js -->
    <script src="{{ asset('/assets/js/pages/datatables.init.js') }}"></script>

    <!-- owl.carousel js -->
    <script src="{{ asset('/assets/libs/owl.carousel/owl.carousel.min.js') }}"></script>
    <!-- timeline init js -->
    <script src="{{ asset('/assets/js/pages/timeline.init.js') }}"></script>
    <script src="{{ asset('/assets/js/normative-xml-validator.js') }}"></script>

@endsection
