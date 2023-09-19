@extends('layouts.master')

@section('title','Register')

@push('css')
<style>
    select.form-control,
    select {
        color: #000;
    }
    .form-group.row {
        padding: 6px 0;
    }
</style>
@endpush

@section('content')
<div class="page-content">
    <div class="row w-100 mx-0 auth-page">
        <div class="col-md-10 col-xl-10 mx-auto">
            <div class="card">
                <div class="row">
                    <div class="col-md-4 pr-md-0">
                        <div class="auth-left-wrapper">
                        </div>
                    </div>
                    <div class="col-md-8 pl-md-0">
                        <div class="auth-form-wrapper px-4 py-5">
                            <a href="#" class="noble-ui-logo d-block mb-2">NAVANA<span> HELPDESK</span></a>
                            <h5 class="text-muted font-weight-normal mb-4">Create an account.</h5>
                            <form class="forms-sample" method="POST" action="{{ route('register') }}">
                                @csrf
                                <div class="form-group row">
                                    <div class="col-5">
                                        <label for="name">Name *</label>
                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-3">
                                        <label for="emp_id">Employee id *</label>
                                        <input id="emp_id" type="text" class="form-control @error('emp_id') is-invalid @enderror" name="emp_id" value="{{ old('emp_id') }}" autocomplete="emp_id" autofocus>
                                        @error('emp_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-4">
                                        <label for="phone">Official Phone *</label>
                                        <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" autocomplete="phone" autofocus>
                                        @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <!-- <div class="col-6">
                                        <label for="location">Work Place *</label>
                                        <select id="work_place" class="form-control @error('work_place') is-invalid @enderror" name="work_place" value="{{ old('work_place') }}" autocomplete="work_place" autofocus>
                                            <option selected disabled>Select your location</option>
                                        </select>
                                        @error('work_place')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div> -->

                                    <div class="col-8">
                                        <label for="email">Official Email *</label>
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-4">
                                        <label for="company_id">Company *</label>
                                        <select name="company_id" id="company_id" class="form-control @error('company_id') is-invalid @enderror" required>
                                            <option selected disabled>Select Company</option>
                                            @foreach(App\Models\Company::all() as $company)
                                            <option value="{{$company->comp_id}}">{{$company->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('company_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputPassword1">Password *</label>
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="password-confirm">{{ __('Confirm Password') }} *</label>
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary mr-2 mb-2 mb-md-0 text-white">
                                        {{ __('Register') }}
                                    </button>
                                </div>
                                <a href="{{route('login')}}" class="d-block mt-3 text-muted">Already have account? Sign In</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('js')

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {

        /*$('#company_id').change(function() {

            var company_id = $(this).val();

            if (company_id) {
                $.ajax({
                    type: "GET",
                    url: "{{url('get-workplace')}}?company_id=" + company_id,
                    success: function(res) {
                        if (res) {
                            $("#work_place").empty();
                            $("#work_place").append('<option>Open this select menu</option>');
                            for (var i = 0; i < res.work_places.length; i++) {
                                $("#work_place").append('<option value="' + res.work_places[i].id + '">' + res.work_places[i].place_name + '</option>');
                            }
                        } else {
                            $("#work_place").empty();
                        }
                    }
                });
            } else {
                $("#work_place").empty();
            }
        }); */

    });
</script>
@endpush