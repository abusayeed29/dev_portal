@extends('layouts.admin')
@section('title','Project create')
@push('css')

@endpush

@section('content')

<div class="page-content">

    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Project</a></li>
            <li class="breadcrumb-item active" aria-current="page">Create</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Add Project information</h6>
                    <form class="forms-sample">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" autocomplete="off" placeholder="Project name">
                        </div>
                        <div class="form-group">
                            <label for="location">Location</label>
                            <input type="text" class="form-control" id="location" autocomplete="off" placeholder="Project location">
                        </div>
                        <div class="form-group">
                            <label>Project Type</label>
                            <select class="form-control mb-3">
                                <option selected>Open this select menu</option>
                                <option value="1">Residential</option>
                                <option value="2">Commercial</option>
                                <option value="3">Condominium</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="area_stories">Area & Stories</label>
                            <input type="text" class="form-control" id="area_stories" autocomplete="off" placeholder="Area and stories">
                        </div>
                        <div class="form-group">
                            <label>File upload</label>
                            <input type="file" name="img[]" class="file-upload-default">
                            <div class="input-group col-xs-12">
                                <input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload Image">
                                <span class="input-group-append">
                                    <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                                </span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">Submit</button>
                        <button class="btn btn-light">Cancel</button>
                    </form>
                </div>
            </div>
        </div>

    </div>

</div>

            
@endsection

@push('js')
<script src="{{asset('backend/assets/js/file-upload.js')}}"></script>
@endpush