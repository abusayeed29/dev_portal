@extends('layouts.admin')
@section('title','Project create')
@push('css')

@endpush

@section('content')

<div class="page-content">

    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Project</a></li>
            <li class="breadcrumb-item active" aria-current="page">Update</li>
        </ol>
    </nav>

    <div class="row">
        <!-- <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Add Project information</h6>
                    <form class="forms-sample">
                        <div class="form-group row">
                            <label for="name" class="col-sm-3 col-form-label">Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="name" value="{{$project->name}}" placeholder="Project name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="location" class="col-sm-3 col-form-label">Location</label>
                            <div class="col-sm-9">
                                <input type="text" value="{{$project->location}}" class="form-control" id="location" autocomplete="off" placeholder="location">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="type" class="col-sm-3 col-form-label">Project Type</label>
                            <div class="col-sm-9">
                                <select class="form-control mb-3">
                                    <option selected>Open this select menu</option>
                                    <option value="1">Residential</option>
                                    <option value="2">Commercial</option>
                                    <option value="3">Condominium</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="area_stories" class="col-sm-3 col-form-label">Area & Stories</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="area_stories" autocomplete="off" placeholder="Area and stories">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="area_stories" class="col-sm-3 col-form-label">File upload</label>
                            <input type="file" name="img[]" class="file-upload-default">
                            <div class="col-sm-9">
                                <div class="input-group col-xs-12">
                                    <input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload Image">
                                    <span class="input-group-append">
                                        <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">Submit</button>
                        <button class="btn btn-light">Cancel</button>
                    </form>
                </div>
            </div>
        </div> -->
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">ADD Department Wise Task</h6>
                    @include('backend.slices.messages')
                    <form class="forms-sample" action="{{ route('pnc.tasks.duration.store') }}" method="post">
                        @csrf
                            <div class="row">
                            <div class="form-group col-3">
                                <label for="departmentId">Department</label>
                                <select class="form-control mb-3" name="department" id="departmentId">
                                    <option selected>Open this select menu</option>
                                    <option value="12">Land Development</option>
                                    <option value="5">Design & Development</option>
                                    <option value="13">Logistics</option>
                                    <option value="6">Engineering</option>
                                </select>
                            </div>
                            <div class="form-group col-3">
                                <label for="task">Task</label>
                                <select class="form-control mb-3" name="task" id="taskId">
                                    <option value="" selected>Open this select menu</option>
                                </select>
                            </div>
                            <div class="form-group col-3">
                                <label for="duration">Duration (Days)</label>
                                <input type="number" class="form-control" name="duration" id="duration" placeholder="Duration">
                                <input type="hidden" class="form-control" value="{{$project->id}}" name="project_id">
                            </div>
                            <div class="form-group col-3 mt-3 py-3">
                                <button type="submit" class="btn btn-primary mr-2">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Task List</h4>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        #
                                    </th>
                                    <th>
                                        Name
                                    </th>
                                    <th>
                                        Department
                                    </th>
                                    <th>
                                        Duration (Days)
                                    </th>
                                    <th>
                                        Created date
                                    </th>
                                    <th>
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tasks as $task)
                                <tr>
                                    <td> {{ $task->id }}</td>
                                    <td> {{ $task->prTask->title }}</td>
                                    <td> {{$task->department->name}} </td>
                                    <td> {{$task->duration}} </td>
                                    <td> {{ $task->created_at}}</td>
                                    <td>
                                        <a href="#" class="btn btn-light btn-sm btn-sm-custom"><i data-feather="edit-2" width="18" height="18"></i></a>
                                        <a href="#" class="btn btn-light btn-sm btn-sm-custom"><i data-feather="eye" width="18" height="18"></i></a>
                                        <a href="#" class="btn btn-light btn-sm btn-sm-custom"><i data-feather="trash" width="18" height="18"></i></a>
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

            
@endsection

@push('js')
<script src="{{asset('backend/assets/js/file-upload.js')}}"></script>
<script>
    // start country
    $('#departmentId').change(function(){
        var department_id = $(this).val(); 
        if(department_id){
            $.ajax({
                type:"GET",
                url:"{{url('get-task')}}?department_id="+department_id,
                success:function(res){           
                    if(res){
                        $("#taskId").empty();
                        $("#taskId").append('<option>Open this select menu</option>');
                        $.each(res.tasks,function(key,value){
                            $("#taskId").append('<option value="'+key+'">'+value+'</option>');
                        });
                    }else{
                        $("#taskId").empty();
                    }
                }
            });
        }else{
            $("#taskId").empty();
        }      
    });
</script>
@endpush