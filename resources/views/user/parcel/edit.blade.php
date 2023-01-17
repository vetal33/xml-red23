@extends('user.layouts.master')

@section('title')
    Edit parcel
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/libs/toastr/toastr.min.css') }}">
    <link href="{{ asset('/assets/libs/leaflet/leaflet.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('/assets/css/map.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1')
            Parcel
        @endslot
        @slot('title')
            Редагувати Ділянку
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                   {{-- <h4 class="card-title">Bootstrap Validation - Normal</h4>--}}
                    <p class="card-title-desc">Файл: {{ $parcel->file_name }}</p>
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#parcel" role="tab">
                                <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                <span class="d-none d-sm-block">Деталі</span>
                            </a>
                        </li>
                        @if($parcel->parcelProblems->isNotEmpty())
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#problems" role="tab">
                                <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                <span class="d-none d-sm-block">Помилки</span>
                            </a>
                        </li>
                        @endif
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content p-3 text-muted">
                        <div class="tab-pane active" id="parcel" role="tabpanel">
                            <form class="needs-validation" novalidate>
                                <input type="hidden" id="data-extent" data-extent="{{ $parcel->extent ?? '' }}">
                                <input id="get-all-parcels" type="hidden" data-href= {{ route('map.get-all-parcels') }}>
                                <div class="mb-3">
                                    <label for="cad-num" class="form-label">Кадастровий номер</label>
                                    <div>
                                        <input type="text" name="cad_num" class="form-control" id="cad-num"
                                               placeholder="Кадастровий номер"
                                               value="{{ old('cad_num') ?? $parcel->cad_num }}">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="usage" class="form-label">Цільове призначення</label>
                                    <div>
                                        <input name="usage" type="text" id="usage" class="form-control"
                                               placeholder="Цільове призначення"
                                               value="{{ old('usage') ?? $parcel->usage }}"/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="area-origin" class="form-label">Площа</label>
                                            <div>
                                                <input type="text" name="area_origin" class="form-control" id="area-origin"
                                                       placeholder="Площа"
                                                       value="{{ old('area_origin') ?? $parcel->area_origin }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="area-calculate" class="form-label">Розрахована площа (га.)</label>
                                            <div>{{$parcel->areaCalculateGa() }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <button class="btn btn-primary" type="submit">Submit form</button>
                                </div>
                            </form>
                        </div>
                        @if($parcel->parcelProblems->isNotEmpty())
                        <div class="tab-pane" id="problems" role="tabpanel">
                            <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                <thead>
                                <tr>
                                    <th>Тип</th>
                                    <th>Площа</th>
                                    <th>Ділянка</th>
                                    <th>Дії</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($parcel->parcelProblems as $problem)
                                    <tr>
                                        <td>{{ \App\Models\ParcelProblem::TYPES_LABEL[$problem->type] }}</td>
                                        <td>{{ $problem->area ? number_format(round(($problem->area) / 10000, 4), 4) : '' }}</td>
                                        <td>{{ $problem->parcel_intersect_id ?? '' }}</td>
                                        <td>
                                            <a class="btn btn-outline-secondary btn-sm zoom btn-zoom-intersect ms-1" href="{{ route('parcel-problem.get-intersect-geom', ['parcelProblemId' => $problem->id]) }}" title="Zoom">
                                                <i class="mdi mdi-vector-intersection font-size-18"></i>
                                            </a>
                                        </td>

{{--                                        <td style="width: 180px">
                                            @if($parcel->is_passed)
                                                <a class="btn btn-outline-secondary btn-sm edit btn-edit" href="{{ route('parcels.edit', ['parcel' => $parcel->id]) }}" title="Edit">
                                                    <i class="mdi mdi-pencil-outline font-size-18"></i>
                                                </a>
                                            @else
                                                <a class="btn btn-outline-secondary btn-sm save btn-save" href="{{ route('parcels.update', ['parcel' => $parcel->id]) }}" title="Save">
                                                    <i class="mdi mdi-content-save-outline font-size-18"></i>
                                                </a>
                                            @endif
                                            <a class="btn btn-outline-secondary btn-sm zoom btn-zoom ms-1" href="#" title="Zoom" data-extent="{{ $parcel->extent }}">
                                                <i class="mdi mdi-map-search font-size-18"></i>
                                            </a>

                                            <a class="btn btn-outline-secondary btn-sm zoom btn-remove ms-4" href="{{ route('parcels.destroy', ['parcel' => $parcel->id]) }}" title="Delete">
                                                @if($parcel->is_passed)
                                                    <i class="mdi mdi-delete-outline font-size-18"></i>
                                                @else
                                                    <i class="mdi mdi-delete-clock-outline font-size-18"></i>
                                                @endif
                                            </a>

                                            @if($parcel->parcelProblems->isNotEmpty())
                                                <a href="#" class="text-danger ms-1"><i class="mdi mdi-information-outline font-size-18"></i></a>
                                            @endif--}}
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- end col -->

        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div id="map" class="leaflet-map" style="height: 400px;"></div>
                </div>
            </div>
            <!-- end card -->
        </div> <!-- end col -->

    </div>
    <!-- end col -->
    </div>

@endsection

@section('script')
    <script src="{{ asset('/assets/libs/leaflet/leaflet.min.js') }}"></script>
    <!-- toastr plugin -->
    <script src="{{ asset('/assets/libs/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('/assets/js/map-edit.js') }}"></script>

    <!-- Required datatable js -->
    <script src="{{ asset('/assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('/assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('/assets/libs/pdfmake/pdfmake.min.js') }}"></script>
    <!-- Datatable init js -->
    <script src="{{ asset('/assets/js/pages/datatables.init.js') }}"></script>
@endsection
