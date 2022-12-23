@extends('admin.layouts.master')

@section('title') Admin Users @endsection

@section('css')
    <link href="{{ asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Tables @endslot
        @slot('title') Users @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($users)
                    <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Email verified at</th>
                                <th>Avatar</th>
                                <th>Created at</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if ($user->hasRole(App\Models\User::ROLE_ADMIN))
                                        <span class="badge badge-pill badge-soft-success font-size-11">{{ $user->roles->first()->name }}</span>
                                        @else
                                        <span class="badge badge-pill badge-soft-secondary font-size-11">{{ $user->roles->first()->name }}</span>
                                    @endif
                                    {{ $user->role_name }}
                                </td>
                                <td>{{ $user->email_verified_at ? $user->email_verified_at->format('m/d/Y') : '' }}</td>
                                <td></td>
                                <td>{{ $user->created_at ? $user->created_at->format('d-m-Y') : '' }}</td>
                                <td style="width: 100px">
                                    <a class="btn btn-outline-secondary btn-sm edit" href="{{ route('admin.users.edit', ['user' => $user->id]) }}" title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->

@endsection
@section('script')
    <!-- Required datatable js -->
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/pdfmake/pdfmake.min.js') }}"></script>
    <!-- Datatable init js -->
    <script src="{{ URL::asset('/assets/js/pages/datatables.init.js') }}"></script>
@endsection
