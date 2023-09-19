@extends('layouts.sub')
@section('title','Dashboard')
@push('css')

@endpush


@section('content')

<div class="page-content">
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0"></h4>
            <nav class="page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Change password</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="profile-page tx-13">
        <div class="row">
            @include('backend.slices.messages')
            <div class="col-12 grid-margin">

            </div>
        </div>
        <div class="row profile-body">

            <div class="d-none d-md-block col-md-12 col-xl-12 left-wrapper">

                <div class="row">

                    <div class="col-lg-6 col-xl-6 stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-baseline mb-3">
                                    <h6 class="card-title mb-0">Change Password</h6>
                                </div>
                                <form class="form-horizontal" method="POST" action="{{ route('sub.settings.changePasswordPost') }}">
                                    {{ csrf_field() }}

                                    <div class="form-group{{ $errors->has('current-password') ? ' has-error' : '' }}">
                                        <label for="new-password">Current Password *</label>


                                        <input id="current-password" type="password" class="form-control" name="current-password" required>

                                        @if ($errors->has('current-password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('current-password') }}</strong>
                                        </span>
                                        @endif

                                    </div>

                                    <div class="form-group{{ $errors->has('new-password') ? ' has-error' : '' }}">
                                        <label for="new-password">New Password *</label>


                                        <input id="new-password" type="password" class="form-control" name="new-password" required>

                                        @if ($errors->has('new-password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('new-password') }}</strong>
                                        </span>
                                        @endif

                                    </div>

                                    <div class="form-group">
                                        <label for="new-password-confirm">Confirm New Password *</label>

                                        <input id="new-password-confirm" type="password" class="form-control" name="new-password_confirmation" required>

                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            Change Password
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div> <!-- row -->


            </div>

        </div>
    </div>

</div>
@endsection

@push('js')

@endpush