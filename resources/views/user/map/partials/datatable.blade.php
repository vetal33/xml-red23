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
            <td style="width: 165px">
                @if($parcel->is_passed)
                <a class="btn btn-outline-secondary btn-sm edit" href="{{ route('parcels.edit', ['parcel' => $parcel->id]) }}" title="Edit">
                    <i class="fas fa-pencil-alt"></i>
                </a>
                @else
                <a class="btn btn-outline-secondary btn-sm save btn-save" href="{{ route('parcels.update', ['parcel' => $parcel->id]) }}" title="Save">
                    <i class="bx bxs-save font-size-16"></i>
                </a>
                @endif
                <a class="btn btn-outline-secondary btn-sm zoom btn-zoom ms-1" href="#" title="Zoom" data-extent="{{ $parcel->extent }}">
                    <i class="bx bxs-map-pin font-size-16"></i>
                </a>
                <a class="btn btn-outline-secondary btn-sm zoom btn-remove ms-1" href="{{ route('parcels.destroy', ['parcel' => $parcel->id]) }}" title="Delete">
                    <i class="bx bx-x-circle font-size-16"></i>
                </a>
            </td>
        </tr>
    @endforeach

    </tbody>
</table>
