@extends('layouts.sub')
@section('title','Dashboard')
@push('css')
    <!--  <link rel="stylesheet" href="{{asset('public/frontend/vendor/wowjs/animate.css')}}"> -->
    <style>
        .nav-pills .nav-link.active, .nav-pills .show > .nav-link {
            color: #fff !important;
        }
    </style>
@endpush


@section('content')

<div class="page-content">

    <div class="profile-page tx-13">
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="profile-header">
                    <div class="cover">
                        <div class="gray-shade"></div>
                        <figure>
                            <img src="{{asset('backend/assets/images/1148x272.png')}}" class="img-fluid" alt="profile cover">
                        </figure>
                        <div class="cover-body d-flex justify-content-between align-items-center">
                            <div>
                                <img class="profile-pic" src="{{asset('uploads/img')}}/{{$employee->image}}" alt="profile">
                                <span class="profile-name">{{$employee->name}} - {{$employee->company_id}}</span>
                            </div>
                            <div class="d-none d-md-block">
                                <button class="btn btn-primary btn-icon-text btn-edit-profile">
                                    <i data-feather="edit" class="btn-icon-prepend"></i> Edit profile
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="header-links">
                        <ul class="links d-flex align-items-center mt-3 mt-md-0 nav nav-pills" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <!-- <i class="mr-1 icon-md" data-feather="columns"></i> -->
                                <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Leave Settings</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Information</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Permission</a>
                            </li>
                        </ul>


                    </div>
                </div>
            </div>
        </div>
        <div class="row profile-body">

            <div class="d-none d-md-block col-md-12 col-xl-12 left-wrapper">
                
                <div class="tab-content" id="pills-tabContent">

                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        <div class="card rounded">
                            <div class="card-body">
                                <h6 class="card-title">Leave hierarchy</h6> 
                                <form class="forms-sample" action="{{route('user.employee.hierarchy')}}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="form-group col-2">
                                            <label for="task">Leave Levels</label>
                                            <select class="form-control mb-3" name="leave_level_id" id="taskId">
                                                <option value="" selected>Open this select menu</option>
                                                @foreach(App\Models\LeaveLevel::all() as $level)
                                                    <option value="{{$level->id}}">{{$level->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-3">
                                            <label for="departmentId">Department</label>
                                            <select class="form-control mb-3" name="department" id="departmentId">
                                                <option selected>Open this select menu</option>
                                                @foreach(App\Models\Department::where('company_id', $employee->company_id)->get() as $department)
                                                    <option value="{{$department->id}}">{{$department->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-3">
                                            <label for="task">Authorised</label>
                                            <select class="form-control mb-3" name="emp_id" id="empId">
                                                <option value="" selected>Open this select menu</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-1">
                                            <label for="task">Hierarchy</label>
                                            <input type="number" name="hierarchy" class="form-control"/>
                                            <input type="hidden" name="user_id" value="{{$employee->id}}"/>
                                        </div>
                                        
                                        <div class="form-group col-2 mt-3 py-3">
                                            <button type="submit" class="btn btn-primary mr-2" style="padding: 9px; margin: -2px 0 0 0;">Submit</button>
                                        </div>
                                    </div>

                                </form>
                                <div class="row">
                                @foreach($leavehierarchies as $hierarchy)
                                <div class="col-md-4 stretch-card grid-margin grid-margin-md-0">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <h5 class="text-center text-uppercase mt-3 mb-4"> {{$hierarchy->leaveLevel->name}}</h5>
                                            <div class="figure mb-3">
                                                <img src="{{asset('uploads/img')}}/{{$hierarchy->user->image}}" width="80" alt="" class="rounded-circle text-center">
                                            </div>
                                            <h6 class="text-muted text-center font-weight-normal"> {{$hierarchy->user->name}}</h6>

                                            <p class="text-muted text-center mb-4 font-weight-light"> {{App\Models\Department::where('id', $hierarchy->user->department_id)->first()->name}}</p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                </div>
                            </div>
                        </div>    
                    </div>

                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">

                        <div class="row">
                            <div class="col-lg-4 col-xl-4 grid-margin grid-margin-xl-0 stretch-card">
                                <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                                    <h6 class="card-title mb-0">Personal Information</h6>
                                    <div class="dropdown mb-2">
                                        <button class="btn p-0" type="button" id="dropdownMenuButton6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton6">
                                        <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="eye" class="icon-sm mr-2"></i> <span class="">View</span></a>
                                        <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="edit-2" class="icon-sm mr-2"></i> <span class="">Edit</span></a>
                                        <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="trash" class="icon-sm mr-2"></i> <span class="">Delete</span></a>
                                        <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="printer" class="icon-sm mr-2"></i> <span class="">Print</span></a>
                                        <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="download" class="icon-sm mr-2"></i> <span class="">Download</span></a>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item border-0 ">Name: </li>
                                            <li class="list-group-item border-0 ">Email: </li>
                                            <li class="list-group-item border-0 ">Address: </li>
                                            <li class="list-group-item border-0 ">Phone: </li>
                                            <li class="list-group-item border-0 ">Date of Joining: </li>
                                            <li class="list-group-item border-0 ">Date of Birth: </li>
                                            <li class="list-group-item border-0 ">Gender: </li>
                                            <li class="list-group-item border-0 ">Religion: </li>
                                            <li class="list-group-item border-0 ">Marital Status: </li>
                                        </ul>
                                    </div>
                                </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-xl-4 stretch-card">
                                <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                                    <h6 class="card-title mb-0">Official Information</h6>
                                    <div class="dropdown mb-2">
                                        <button class="btn p-0" type="button" id="dropdownMenuButton7" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton7">
                                        <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="eye" class="icon-sm mr-2"></i> <span class="">View</span></a>
                                        <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="edit-2" class="icon-sm mr-2"></i> <span class="">Edit</span></a>
                                        </div>
                                    </div>
                                    </div>

                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item border-0 ">Name: </li>
                                        <li class="list-group-item border-0 ">Email: </li>
                                        <li class="list-group-item border-0 ">Address: </li>
                                        <li class="list-group-item border-0 ">Phone: </li>
                                        <li class="list-group-item border-0 ">Date of Joining: </li>
                                        <li class="list-group-item border-0 ">Date of Birth: </li>
                                        <li class="list-group-item border-0 ">Gender: </li>
                                        <li class="list-group-item border-0 ">Religion: </li>
                                        <li class="list-group-item border-0 ">Marital Status: </li>
                                    </ul>
                                </div> 
                                </div>
                            </div>

                            <div class="col-lg-4 col-xl-4 stretch-card">
                                <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                                    <h6 class="card-title mb-0">Bank Information</h6>
                                    <div class="dropdown mb-2">
                                        <button class="btn p-0" type="button" id="dropdownMenuButton7" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton7">
                                        <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="eye" class="icon-sm mr-2"></i> <span class="">View</span></a>
                                        <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="edit-2" class="icon-sm mr-2"></i> <span class="">Edit</span></a>
                                        </div>
                                    </div>
                                    </div>

                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item border-0 ">Account: </li>
                                        <li class="list-group-item border-0 ">Email: </li>
                                    </ul>
                                </div> 
                                </div>
                            </div>
                        </div> <!-- row -->

                        <div class="row mt-3">
                            <div class="col-lg-6 col-xl-6 grid-margin grid-margin-xl-0 stretch-card">
                                <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                                    <h6 class="card-title mb-0">EDUCATIONAL QUALIFICATION</h6>
                                    <div class="dropdown mb-2">
                                        <button class="btn p-0" type="button" id="dropdownMenuButton6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton6">
                                        <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="eye" class="icon-sm mr-2"></i> <span class="">View</span></a>
                                        <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="edit-2" class="icon-sm mr-2"></i> <span class="">Edit</span></a>
                                        <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="trash" class="icon-sm mr-2"></i> <span class="">Delete</span></a>
                                        <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="printer" class="icon-sm mr-2"></i> <span class="">Print</span></a>
                                        <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="download" class="icon-sm mr-2"></i> <span class="">Download</span></a>
                                        </div>
                                    </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead>
                                            <tr>
                                                <th class="pt-0">#</th>
                                                <th class="pt-0">Institute</th>
                                                <th class="pt-0">Degree</th>
                                                <th class="pt-0">Board / University</th>
                                                <th class="pt-0">Result</th>
                                                <th class="pt-0">GPA / CGPA</th>
                                                <th class="pt-0">Passing Year</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td> -- </td>
                                                <td> -- </td>
                                                <td> -- </td>
                                                <td> -- </td>
                                                <td> -- </td>
                                                <td> -- </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-xl-6 stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-baseline mb-2">
                                            <h6 class="card-title mb-0">PROFESSIONAL EXPERIENCE</h6>
                                            <div class="dropdown mb-2">
                                                <button class="btn p-0" type="button" id="dropdownMenuButton7" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton7">
                                                <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="eye" class="icon-sm mr-2"></i> <span class="">View</span></a>
                                                <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="edit-2" class="icon-sm mr-2"></i> <span class="">Edit</span></a>
                                                <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="trash" class="icon-sm mr-2"></i> <span class="">Delete</span></a>
                                                <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="printer" class="icon-sm mr-2"></i> <span class="">Print</span></a>
                                                <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="download" class="icon-sm mr-2"></i> <span class="">Download</span></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead>
                                                <tr>
                                                    <th class="pt-0">#</th>
                                                    <th class="pt-0">Organization</th>
                                                    <th class="pt-0">Designation</th>
                                                    <th class="pt-0">Duration</th>
                                                    <th class="pt-0">Skill</th>
                                                    <th class="pt-0">Responsibility</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td> -- </td>
                                                    <td> -- </td>
                                                    <td> -- </td>
                                                    <td> -- </td>
                                                    <td> -- </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div> 
                                </div>
                            </div>
                            </div> <!-- row -->
        
                    
                    </div>
                    <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">Contact</div>

                </div>

            </div>
          
        </div>
    </div>

</div>
@endsection

@push('js')
    <script>
        // start country
        $('#departmentId').change(function(){
            var department_id = $(this).val();
            if(department_id){
                $.ajax({
                    type:"GET",
                    url:"{{url('get-user')}}?department_id="+department_id,
                    success:function(res){    
                        if(res){
                            $("#empId").empty();
                            $("#empId").append('<option>Open this select menu</option>');
                            $.each(res.emps,function(key,value){
                                $("#empId").append('<option value="'+key+'">'+value+'</option>');
                            });
                        }else{
                            $("#empId").empty();
                        }
                    }
                });
            }else{
                $("#taskId").empty();
            }      
        });
    </script>
@endpush