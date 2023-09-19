@extends('layouts.sub')
@section('title','Dashboard')
@push('css')
    <!--  <link rel="stylesheet" href="{{asset('public/frontend/vendor/wowjs/animate.css')}}"> -->
    <style>
        .list-group-item {
            padding: 0.35rem 1.25rem !important;
        }
    </style>
@endpush


@section('content')

<div class="page-content">

    <div class="profile-page tx-13">
        <div class="row">
            @include('backend.slices.messages')
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="position-relative">
                        <div class="gray-shade"></div>
                        <div class="cover-body d-flex justify-content-between align-items-center">
                            <div class="profile d-flex p-3">
                                <div class="float-left my-auto me-3">
                                    <img class="profile-pic wd-100 rounded-circle" src="{{asset('uploads/img')}}/{{$employee->image}}" alt="profile">
                                </div>
                                <div class="float-left mx-auto mt-4">
                                    <span class="profile-name ml-0">{{$employee->name}}</span><br>
                                    <span>Designation: {{$employee->designation_name}}</span><br>
                                    <span>Department: {{!empty($employee->department->name) ? $employee->department->name :''}}</span><br>
                                    <span>Company: {{$employee->company_name}}</span> <br>
                                    <span>Employee Id: {{$employee->emp_id}}</span>
                                </div>
                            </div>
                            <div class="d-none d-md-block me-5">
                                <button type="button" class="btn btn-primary btn-icon-text btn-edit-profile" data-bs-toggle="modal" data-bs-target="#userUpdateModal">
                                    <i data-feather="edit" class="btn-icon-prepend"></i> Edit profile
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="row profile-body">

            <div class="d-none d-md-block col-md-12 col-xl-12 left-wrapper">
                
                <div class="row">
                    <div class="col-lg-6 col-xl-6 grid-margin grid-margin-xl-0 stretch-card">
                        <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline mb-2">
                            <h6 class="card-title mb-0"> Official Information</h6>
                            <div class="dropdown mb-2">
                                <button class="btn p-0" type="button" id="dropdownMenuButton6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton6">
                                    <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="eye" class="icon-sm mr-2"></i> <span class="">View</span></a>
                                    <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="edit-2" class="icon-sm mr-2"></i> <span class="">Edit</span></a>
                                </div>
                            </div>
                            </div>
                            <div class="d-flex flex-column">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item border-0 "><strong>Name:</strong> {{$employee->name}}</li>
                                    <li class="list-group-item border-0 "><strong>Email: </strong> {{$employee->email}}</li>
                                    <li class="list-group-item border-0 "><strong>Address: </strong> {{$employee->address}}</li>
                                    <li class="list-group-item border-0 "><strong>Phone: </strong> {{$employee->phone}}</li>
                                    <li class="list-group-item border-0 "><strong>PABX: </strong> {{$employee->pabx}}</li>
                                    <li class="list-group-item border-0 "><strong>IP Address:</strong> {{$employee->ip_address}}</li>
                                    <li class="list-group-item border-0 "><strong>Date of Joining: </strong> </li>
                                    <li class="list-group-item border-0 "><strong>Date of Birth:</strong> {{$employee->birth_date}}</li>
                                    <li class="list-group-item border-0 "><strong>Gender:</strong> {{$employee->gender}}</li>
                                    <li class="list-group-item border-0 "><strong>Department: </strong> {{$employee->department_name}}</li>
                                    <li class="list-group-item border-0 "><strong>Religion: </strong> </li>
                                    <li class="list-group-item border-0 "><strong>Marital Status:</strong> </strong> </li>
                                </ul>

                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-xl-4 stretch-card">
                        <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline mb-2">
                            <h6 class="card-title mb-0">Personal Information</h6>
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
                                <li class="list-group-item border-0 ">Name: {{$employee->employee_name}}</li>
                                <li class="list-group-item border-0 ">Email: {{$employee->email}}</li>
                                <li class="list-group-item border-0 ">Address: </li>
                                <li class="list-group-item border-0 ">Phone: {{$employee->mobile}}</li>
                                <li class="list-group-item border-0 ">Date of Birth: </li>
                                <li class="list-group-item border-0 ">Gender: {{$employee->sex}}</li>
                                <li class="list-group-item border-0 ">Religion: </li>
                                <li class="list-group-item border-0 ">Blood Group: {{$employee->blood_group}}</li>
                            </ul>
                        </div> 
                        </div>
                    </div>

                </div> <!-- row -->
                    
            </div>
          
        </div>
    </div>

    <!-- start add new  User modal -->
    <div class="modal fade" id="userUpdateModal" tabindex="-1" role="dialog" aria-labelledby="userUpdateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content border-radius-0">
                <div class="modal-header">
                    <h5 class="modal-title" id="userUpdateModalLabel">Profile Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <form action="{{route('sub.employee.update')}}" method="POST">
                    @method('PUT')
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-danger" style="display:none"></div>
                        <div class="row pb-0">
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="name">Full Name *</label>
                                    <input type="text" class="form-control" value="{{$employee->name}}" id="name" name="name" placeholder="Enter Name">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="designationId">Designation *</label>
                                <select class="form-control" name="designation" id="designationId">
                                    <option selected disabled>Select designation</option>
                                    @foreach(App\Models\Designation::where('company_id','=', $employee->company_id)->orderBy('name', 'ASC')->get() as $designation)
                                    <option value="{{ $designation->id }}" {{ $designation->id === $employee->designation_id ? "selected" : ""}}>{{$designation->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="designation">Department *</label>
                                <select class="form-control" name="department" id="departmentId">
                                    <option selected disabled>Select Department</option>
                                    @foreach(App\Models\Department::where('company_id','=', $employee->company_id)->get() as $department)
                                    <option value="{{ $department->id }}" {{ $department->id === $employee->department_id ? "selected" : ""}}>{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="email">Email*</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{$employee->email}}" placeholder="Enter email">
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="form-group">
                                    <label for="mobile">Mobile *</label>
                                    <input type="text" value="{{$employee->phone}}" class="form-control" name="phone" id="mobile" placeholder="Enter phone">
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="form-group">
                                    <label for="pabx">Pabx</label>
                                    <input type="text" value="{{$employee->pabx}}" class="form-control" id="pabx" name="pabx" placeholder="Enter phone">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group" id="">
                                    <label for="birthdate">Birth Date</label>
                                    <div class="input-group date datepicker" id="gender_datepicker">
                                        <input type="date" name="birthdate" value="{{!empty($employee->birth_date) ? $employee->birth_date: ''}}" class="form-control" id="birthdate">
                                        <!-- <span class="input-group-addon"><i data-feather="calendar"></i></span> -->
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="gender">Gender</label>
                                <select class="form-control" name="gender" id="gender">
                                    <option selected disabled>Select Gender</option>
                                    <option value='male' {{!empty($employee->gender)  && $employee->gender=='male' ? 'selected':''}}>Male</option>
                                    <option value='female' {{ !empty($employee->gender)  && $employee->gender=='female' ? 'selected': ''}}>Female</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input type="address" value="{{$employee->address}}" class="form-control" id="address" name="address" placeholder="Enter address">
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="form-group">
                                    <label for="blood">Blood Broup</label>
                                    <input type="text" value="{{$employee->blood}}" id="blood" class="form-control" name="blood" placeholder="Enter blood">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="ip_address">IP Address *</label>
                                    <input type="text" value="{{$employee->ip_address}}" id="ip_address" class="form-control" name="ip_address" placeholder="Enter ip address">
                                </div>
                                <input type="hidden" value="{{$employee->id}}" name="user_id"/>
                                <input type="hidden" value="{{$employee->emp_id}}" name="emp_id"/>

                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end add new modal -->

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