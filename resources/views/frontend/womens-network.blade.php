@extends('layouts.master')

@section('title','Womens Network')

@push('css')

@endpush

@section('content')

<div class="page-content">

    <!-- <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Womens Network</li>
        </ol>
    </nav> -->

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-center card-title-1">Womens NetWork</h6>

                    <form action="#" method="POST">
                        @csrf
                        <div class="alert alert-danger" style="display:none"></div>
                        <div class="form-group row pb-0 mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Full Name *</label>
                                    <input type="text" class="form-control" name="name" placeholder="Enter Name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="employee_id">Employee Id *</label>
                                    <input type="text" class="form-control" name="emp_id" placeholder="Ex:1964">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email*</label>
                                    <input type="email" class="form-control" name="email" placeholder="Enter email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Mobile *</label>
                                    <input type="text" class="form-control" name="mobile" placeholder="Enter phone">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="filter_company">Company *</label>
                                <select class="form-control" name="company" id="filter_company">
                                    <option selected disabled>Select company</option>
                                    @foreach(App\Models\Company::all() as $company)
                                    <option value="{{$company->comp_id}}">{{$company->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="departmentId">Department *</label>
                                <select class="js-example-basic-single form-control w-100" name="department" id="departmentId">
                                    <option selected disabled>Select department</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="designationId">Designation *</label>
                                <select class="js-example-basic-single form-control w-100" name="designation" id="designationId">
                                    <option disabled selected>Select designation</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Submit</button>

                    </form>
                </div>
        </div>
    </div>
</div>
</div>


@endsection

@push('js')

@endpush