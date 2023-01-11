<table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
    <thead>
    <tr>
        <th>Кадастровий номер</th>
        <th>Цільове використання</th>
        <th>Площа</th>
        <th>Дата</th>
        <th>Керування</th>
    </tr>
    </thead>

    <tbody>
    @foreach($parcels as $parcel)
        <tr class="{{ $parcel->is_passed ? '' : 'table-warning' }}">
            <td>{{ $parcel->cad_num ?? '' }}</td>
            <td>{{ $parcel->usage ?? '' }}</td>
            <td>{{ $parcel->area_origin ? number_format(round(($parcel->area_origin) / 10000, 4), 4) : '' }}</td>
            <td>{{ $parcel->created_at ? $parcel->created_at->format('d-m-Y H:i') : '' }}</td>
            <td style="width: 180px">
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
                @if(!$parcel->parcelProblems->isEmpty())
                <a href="#" class="text-danger ms-1"><i class="mdi mdi-information-outline font-size-18"></i></a>
                @endif
            </td>
        </tr>
    @endforeach

    </tbody>
</table>
