@extends('admin.layouts.master')

@section('title') Admin User Edit @endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Users @endslot
        @slot('title') User Edit @endslot
    @endcomponent

    <!-- end row -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <img src="assets/images/users/avatar-1.jpg" alt="" class="avatar-lg rounded-circle img-thumbnail">
                                </div>
                            </div>
                        </div>
                    </div>
                    <form class="custom-validation mt-5" action="#" method="POST">
                        @csrf
                        {{ method_field('PATCH') }}
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" disabled name="name" class="form-control" value="{{ $user->name }}" placeholder="Name" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label">E-Mail</label>
                            <div>
                                <input type="email" class="form-control" disabled name="email" parsley-type="email"
                                       placeholder="Enter a valid e-mail"  value="{{ $user->email }}"/>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">E-Mail Verified At</label>
                            <div>
                                <input parsley-type="url" type="url" class="form-control" disabled placeholder="URL" value="{{ $user->email_verified_at ? $user->email_verified_at->format('d.m.Y H:m') : '' }}"/>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select class="form-select" disabled>
                                <option>Select</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" @if($role->name === $user->roles->first()->name) selected @endif > {{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Comment</label>
                            <div>
                                <textarea class="form-control" name="comment" rows="5">{{ old('comment') ?? $user->comment }}</textarea>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">
                                Submit
                            </button>
                            <a href="{{ url()->previous() }}" class="btn btn-secondary waves-effect">Cancel</a>
                        </div>
                    </form>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->

@endsection
@section('script')
    <script src="{{ URL::asset('/assets/libs/parsleyjs/parsleyjs.min.js') }}"></script>

    <script src="{{ URL::asset('/assets/js/pages/form-validation.init.js') }}"></script>
@endsection
