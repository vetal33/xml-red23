@extends('user.layouts.master')

@section('title')
    User xml validator
@endsection

@section('css')
    <link href="{{ asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
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
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="mt-4 mb-5">
                                <div>
                                    <form action="{{ route('xml-validator.upload') }}" method="POST"
                                          enctype="multipart/form-data">
                                        @csrf
                                        <div class="input-group">
                                            <input name="file" type="file" class="form-control" id="inputGroupFile04"
                                                   aria-describedby="inputGroupFileAddon04" aria-label="Upload"
                                                   value="{{ $xmlNormative ? $xmlNormative->path : '' }}" accept="text/xml">
                                            <button class="btn btn-primary" type="submit" id="inputGroupFileAddon04">
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
            <div class="col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div data-simplebar class="mt-2" style="max-height: 280px;">
                            <ul class="verti-timeline list-unstyled">
                                <li class="event-list">
                                    <div class="event-timeline-dot">
                                        @if (Session::has('structure') || Session::has('geom'))
                                            <span class="text-success">
                                                <i class="bx bx bxs-check-circle font-size-22"></i>
                                            </span>
                                        @else
                                            <i class="bx bx-right-arrow-circle font-size-18"></i>
                                        @endif

                                    </div>
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <h5 class="font-size-14">Структура
                                                @if (Session::has('structure') || Session::has('geom'))
                                                @else
                                                    <i class="bx bx-right-arrow-alt font-size-16 text-primary align-middle ms-2"></i>
                                                @endif
                                            </h5>
                                        </div>
                                    </div>
                                </li>
                                <li class="event-list">
                                    <div class="event-timeline-dot">
                                        @if (Session::has('geom'))
                                            <span class="text-success">
                                                <i class="bx bx bxs-check-circle font-size-22"></i>
                                            </span>
                                        @else
                                            <i class="bx bx-right-arrow-circle font-size-18"></i>
                                        @endif
                                    </div>
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <h5 class="font-size-14">Геометрія
                                            @if (Session::has('structure'))
                                                <i class="bx bx-right-arrow-alt font-size-16 text-primary align-middle ms-2"></i>
                                            @endif
                                            </h5>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- end card -->
            </div>
            <!-- end col -->
            <div class="col-xl-9">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ Session::has('structure') ? route('xml-validator.geom-validate', ['file' => $xmlNormative->id]) : route('xml-validator.structure-validate', ['file' => $xmlNormative->id]) }}"
                              method="POST">
                            @csrf
                            <input type="hidden" name="file">
                            <div class="d-sm-flex flex-wrap">
                                <h4 class="card-title mb-4">Перевірка <span
                                        class="text-primary @if(Session::has('validationErrors')) text-danger @elseif(Session::has('geom')) text-success @endif">{{ $xmlNormative->name }}</span></h4>

                                <div class="ms-auto">
                                    <ul class="nav nav-pills">
                                        @if(Session::has('validationErrors'))
                                        <li class="nav-item me-3">
                                            <a class="btn btn-outline-primary validation-error-export" href="{{ route('validator-xml.print-errors-pdf', ['file' => $xmlNormative->id]) }}">
                                                <i class="bx bxs-file-pdf font-size-16 align-middle me-2"></i>Експорт</a>
                                        </li>
                                        @endif
                                        <li class="nav-item">
                                            <button id="xml-validation" type="button" class="nav-link active">Перевірити</button>
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

                        @if (Session::has('validationErrors'))
                            <h4 class="card-title mt-3 mb-3">Помилки валідації <span class="text-danger">({{ count(Session::get('validationErrors')) }} помилок)</span></h4>
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
                        @endif
                    </div>
                </div>
                <!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->

    @endif

@endsection

@section('script')
    <!-- Required datatable js -->
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/pdfmake/pdfmake.min.js') }}"></script>
    <!-- Datatable init js -->
    <script src="{{ URL::asset('/assets/js/pages/datatables.init.js') }}"></script>

    <script src="{{ asset('/assets/js/xml-validation.js') }}"></script>
@endsection
