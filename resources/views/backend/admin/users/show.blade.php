@extends('layouts.admin')
@section('title','Dashboard')
@push('css')
<link rel="stylesheet" href="{{asset('backend/assets/vendors/toastr.js/toastr.min.css')}}">
<link rel="stylesheet" href="{{asset('backend/assets/vendors/animate.css/animate.min.css')}}">
<link rel="stylesheet" href="{{asset('backend/assets/vendors/select2/select2.min.css')}}">
<style>
    body.modal-open {
        overflow: auto;
    }
</style>
@endpush


@section('content')

<div class="page-content">
    <div class="profile-page tx-13">
        <div class="row">
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
                                    <span class="profile-name ml-0"><strong>Name:</strong> {{$employee->name}}</span><br>
                                    <span><strong>Employee Id:</strong> {{$employee->emp_id}}</span><br>
                                    <span><strong>Designation:</strong> {{$employee->designation->name}}</span><br>
                                    <span><strong>Department:</strong> {{!empty($employee->department->name) ? $employee->department->name :''}}</span><br>
                                    <span><strong>Section:</strong> {{!empty($employee->section->name) ? $employee->Section->name :''}}</span><br>
                                    <span><strong>Company:</strong> {{$employee->company->name}}</span>
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

            <div class="d-md-block col-md-12 col-xl-12 left-wrapper">
                <div class="card rounded">
                    <div class="card-body">
                        <div class="tab-content" id="pills-tabContent">
                            <ul class="nav nav-tabs nav-tabs-line mb-3" id="lineTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="home-line-tab" data-bs-toggle="tab" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Settings</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="profile-line-tab" data-bs-toggle="tab" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Information</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="contact-line-tab" data-bs-toggle="tab" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Contact</a>
                                </li>
                            </ul>

                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">

                                @foreach($modules as $module)
                                @if(count($module->modulePages) > 0)
                                <div class="card mt-3">
                                    <div class="card-body">
                                        <h6 class="card-title">{{$module->name}}</h6>
                                        <div class="table-responsive mt-3">
                                            <table class="table table-striped table-perm table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Module Menus</th>
                                                        <th class="text-center">Create </th>
                                                        <th class="text-center">Read</th>
                                                        <th class="text-center">Update</th>
                                                        <th class="text-center">Delete</th>
                                                        <th class="text-center">Cancel</th>
                                                        <th class="text-center">approval</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($module->modulePages as $m_page)

                                                    <?php $perms = App\Models\Permission::where([['module_id', '=', $m_page->id], ['user_id', '=', $employee->id]])->first() ?>

                                                    <tr>
                                                        <th>{{$m_page->id}}</th>
                                                        <td>{{$m_page->name}}</td>
                                                        <td class="text-center"> <input type="checkbox" name="create[]" id="create_{{$m_page->id}}" class="form-check-input" {{ !empty($perms->create) ? 'checked': ''}} disabled></td>
                                                        <td class="text-center"> <input type="checkbox" name="read[]" id="read_{{$m_page->id}}" class="form-check-input" {{ !empty($perms->read) ? 'checked': ''}} disabled></td>
                                                        <td class="text-center"><input type="checkbox" name="update[]" id="update_{{$m_page->id}}" class="form-check-input" {{ !empty($perms->update) ? 'checked': ''}} disabled></td>
                                                        <td class="text-center"><input type="checkbox" name="delete[]" id="delete_{{$m_page->id}}" class="form-check-input" {{ !empty($perms->delete) ? 'checked': ''}} disabled></td>
                                                        <td class="text-center"><input type="checkbox" name="Cancel[]" id="cancel_{{$m_page->id}}" class="form-check-input" {{ !empty($perms->cancel) ? 'checked': ''}} disabled></td>
                                                        <td class="text-center"><input type="checkbox" name="approval[]" id="approval_{{$m_page->id}}" class="form-check-input" {{ !empty($perms->approval) ? 'checked': ''}} disabled></td>
                                                        <input type="hidden" name="module_id[]" value="{{$m_page->id}}" />
                                                        <input type="hidden" name="user_id" value="{{$employee->id}}" />
                                                        <td>
                                                            <button type="button" data-uid="{{$employee->id}}" data-mname="{{$m_page->name}}" data-mid="{{$m_page->id}}" data-create="{{ !empty($perms->create) ? $perms->create: '0'}}" data-read="{{ !empty($perms->read) ? $perms->read: '0'}}" data-update="{{ !empty($perms->update) ? $perms->update: '0'}}" data-delete="{{ !empty($perms->delete) ? $perms->delete: '0'}}" data-cancel="{{ !empty($perms->cancel) ? $perms->cancel: '0'}}" data-approval="{{ !empty($perms->approval) ? $perms->approval: '0'}}" class="btn btn-light btn-sm btn-sm-custom" data-bs-toggle="modal" data-bs-target="#permissionModal">
                                                                <i data-feather="edit"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endforeach

                            </div>

                            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                <div class="row">
                                    <div class="col-lg-4 col-xl-4 grid-margin grid-margin-xl-0 stretch-card">
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
                                                        <li class="list-group-item border-0 ">Name: {{$employee->employee_name}}</li>
                                                        <li class="list-group-item border-0 ">Email: {{$employee->email}}</li>
                                                        <li class="list-group-item border-0 ">Place of work:</li>
                                                        <li class="list-group-item border-0 ">PABX: </li>
                                                        <li class="list-group-item border-0 ">Phone: {{$employee->mobile}}</li>
                                                        <li class="list-group-item border-0 ">Date of Joining: {{$employee->joining_date}}</li>
                                                        <li class="list-group-item border-0 ">Date of Birth: </li>
                                                        <li class="list-group-item border-0 ">Gender: {{$employee->sex}}</li>
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
                            <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">Contact</div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="userUpdateModal" tabindex="-1" role="dialog" aria-labelledby="userUpdateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content border-radius-0">
                <div class="modal-header">
                    <h5 class="modal-title" id="userUpdateModalLabel">Profile Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <form action="{{route('admin.user.update')}}" method="POST">
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
                                <div class="form-group">
                                    <label for="email">Email*</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{$employee->email}}" placeholder="Enter email">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="mobile">Mobile *</label>
                                    <input type="text" value="{{$employee->phone}}" class="form-control" name="phone" id="mobile" placeholder="Enter phone">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="designationId">Designation *</label>
                                <select class="form-select w-100 select2_modal" name="designation" id="designationId" data-width="100%">
                                    <option selected disabled>Select designation</option>
                                    @foreach(App\Models\Designation::where('company_id','=', $employee->company_id)->orderBy('name', 'ASC')->get() as $designation)
                                    <option value="{{ $designation->id }}" {{ $designation->id === $employee->designation_id ? "selected" : ""}}>{{$designation->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="designation">Department *</label>
                                <select class="form-select" name="department" id="departmentId">
                                    <option selected disabled>Select Department</option>
                                    @foreach(App\Models\Department::where('company_id','=', $employee->company_id)->get() as $department)
                                    <option value="{{ $department->id }}" {{ $department->id === $employee->department_id ? "selected" : ""}}>{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="designation">Section *</label>
                                <select class="form-select" name="section" id="sectionId">
                                    <option selected disabled>Select Section</option>
                                    @foreach(App\Models\Section::where('company_id','=', $employee->company_id)->get() as $section)
                                    <option value="{{ $department->id }}" {{ $section->id === $employee->section_id ? "selected" : ""}}>{{ $section->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="designation">Status *</label>
                                <select class="form-select" name="status" id="status">
                                    <option selected disabled>Select Status</option>
                                    <option value="R">Regular</option>
                                    <option value="RG">Resigned</option>
                                    <option value="T">Terminated</option>
                                    <option value="E">Expired</option>
                                    <option value="D">Dismissal</option>
                                    <option value="A">Absconding</option>
                                    <option value="MR">Master To Regular</option>
                                    <option value="P">Probationary</option>
                                    <option value="C">Contractual</option>
                                    <option value="CR">Contractual To Regular</option>
                                    <option value="CA">Casual</option>
                                    <option value="M">Master Roll</option>
                                    <option value="NO">Non Management</option>
                                </select>
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
                                <select class="form-select" name="gender" id="gender">
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

    <!-- Modal -->
    <div class="modal fade" id="permissionModal" tabindex="-1" role="dialog" aria-labelledby="permissionModal" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="permissionModalLabel">Change Permission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <form action="{{route('admin.user.permission')}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <!-- <h5>Are you sure?</h5>   -->
                        <div class="alert alert-danger" style="display:none"></div>
                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="checkbox" name="create" id="create" class="form-check-input">
                                    Create
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="checkbox" name="read" id="read" class="form-check-input">
                                    Read
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="checkbox" name="update" id="update" class="form-check-input">
                                    Update
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="checkbox" name="mdelete" id="mdelete" class="form-check-input">
                                    Delete
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="checkbox" name="cancel" id="cancel" class="form-check-input">
                                    Cancel
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="checkbox" name="approval" id="approval" class="form-check-input">
                                    Approval
                                </label>
                            </div>
                            <input type="hidden" value="" name="module_id" id="module_id">
                            <input type="hidden" value="" name="user_id" id="user_id">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                        <button type="submit" class="btn btn-primary mr-2">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

@push('js')
<script src="{{asset('backend/assets/vendors/toastr.js/toastr.min.js')}}"></script>
<script src="{{asset('backend/assets/vendors/select2/select2.min.js')}}"></script>
<script src="{{asset('backend/assets/js/select2.js')}}"></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function printErrorMsg(msg) {
            toastr.options = {
                "closeButton": true,
                "newestOnTop": true,
                "positionClass": "toast-top-right"
            };
            var error_html = '';
            for (var count = 0; count < msg.length; count++) {
                error_html += '<p>' + msg[count] + '</p>';
            }
            toastr.error(error_html);
        }

        function printSuccessMsg(msg) {
            toastr.options = {
                "closeButton": true,
                "newestOnTop": true,
                "positionClass": "toast-top-right"
            };
            toastr.success(msg);
        }

        $('.select2_modal').select2({
            dropdownParent: $('#userUpdateModal')
        });

    });
    

    // start purmission function
    $('#permissionModal').on('show.bs.modal', function(event) {

        var button = $(event.relatedTarget);
        var module_name = button.data("mname");
        var module_id = button.data("mid");
        var user_id = button.data("uid");
        var create = button.data("create");
        var read = button.data("read");
        var update = button.data("update");
        var mdelete = button.data("delete");
        var cancel = button.data("cancel");
        var approval = button.data("approval");

        var modal = $(this);
        modal.find('#permissionModalLabel').text(module_name);
        modal.find('.modal-body #module_id').val(module_id);
        modal.find('.modal-body #user_id').val(user_id);

        if (create == 1) {
            modal.find('.modal-body #create').attr('checked', true);
        } else {
            modal.find('.modal-body #create').attr('checked', false);
        }
        if (read == 1) {
            modal.find('.modal-body #read').attr('checked', true);
        } else {
            modal.find('.modal-body #read').attr('checked', false);
        }
        if (update == 1) {
            modal.find('.modal-body #update').attr('checked', true);
        } else {
            modal.find('.modal-body #update').attr('checked', false);
        }
        if (mdelete == 1) {
            modal.find('.modal-body #mdelete').attr('checked', true);
        } else {
            modal.find('.modal-body #mdelete').attr('checked', false);
        }
        if (cancel == 1) {
            modal.find('.modal-body #cancel').attr('checked', true);
        } else {
            modal.find('.modal-body #cancel').attr('checked', false);
        }
        if (approval == 1) {
            modal.find('.modal-body #approval').attr('checked', true);
        } else {
            modal.find('.modal-body #approval').attr('checked', false);
        }

    })
    // end permission function

    // start country
    $('#departmentId').change(function() {
        var department_id = $(this).val();
        if (department_id) {
            $.ajax({
                type: "GET",
                url: "{{url('get-user')}}?department_id=" + department_id,
                success: function(res) {
                    if (res) {
                        $("#empId").empty();
                        $("#empId").append('<option>Open this select menu</option>');
                        $.each(res.emps, function(key, value) {
                            $("#empId").append('<option value="' + key + '">' + value + '</option>');
                        });
                    } else {
                        $("#empId").empty();
                    }
                }
            });
        } else {
            $("#taskId").empty();
        }
    });

</script>

@endpush