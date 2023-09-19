@extends('layouts.sub')
@section('title','Dashboard')
@push('css')
<link rel="stylesheet" href=" {{asset('backend/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
<link rel="stylesheet" href="{{asset('backend/assets/vendors/select2/select2.min.css')}}">
<style>
    body.modal-open {
        overflow: auto;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow b::before {
        display: none;

    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: inherit !important;
    }

    .select2-container--default .select2-selection--single {
        border-radius: 1px !important;
        border: 1px solid #e8ebf1 !important;
        height: 34px;

    }

    .list-group-item {
        padding: 0.35rem 1.25rem !important;
    }

    .nav-pills .nav-link.active,
    .nav-pills .show>.nav-link {
        background-color: #ddd !important;
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
                                    <img class="profile-pic wd-100 rounded-circle" src="{{asset('uploads/img')}}/{{Auth::user()->image}}" alt="profile">
                                </div>
                                <div class="float-left mx-auto mt-4">
                                    <span class="profile-name ml-0">{{$userInfo->name}}</span><br>
                                    <span>{{$userInfo->designation_name}}</span><br>
                                    <span>Company: {{$userInfo->company_name}}</span> <br>
                                    <span>Employee Id: {{$userInfo->emp_id}}</span>
                                </div>
                            </div>
                            <div class="d-none d-md-block me-5">
                                <button type="button" class="btn btn-primary btn-icon-text btn-edit-profile" data-emp_id="{{$userInfo->emp_id}}" data-name="{{$userInfo->name}}" data-email="{{$userInfo->email}}" data-mobile="{{$userInfo->phone}}" data-pabx="{{$userInfo->pabx}}" data-birthdate="{{$userInfo->birth_date}}" data-gender="{{$userInfo->gender}}" data-reportto_id="{{$userInfo->reportto_to}}" data-address="{{$userInfo->address}}" data-blood="{{$userInfo->blood}}" data-ip="{{$userInfo->ip_address}}" data-department="{{$userInfo->department_id}}" data-designation="{{$userInfo->designation_id}}" data-bs-toggle="modal" data-bs-target="#userUpdateModal">
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

                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        <div class="row">
                        <div class="col-lg-6 col-xl-6 grid-margin grid-margin-xl-0 stretch-card">
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
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column">

                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item border-0 "><strong>Name:</strong> {{$userInfo->name}}</li>
                                                <li class="list-group-item border-0 "><strong>Email: </strong> {{$userInfo->email}}</li>
                                                <li class="list-group-item border-0 "><strong>Address: </strong> {{$userInfo->address}}</li>
                                                <li class="list-group-item border-0 "><strong>Phone: </strong> {{$userInfo->phone}}</li>
                                                <li class="list-group-item border-0 "><strong>PABX: </strong> {{$userInfo->pabx}}</li>
                                                <li class="list-group-item border-0 "><strong>IP Address:</strong> {{$userInfo->ip_address}}</li>
                                                <li class="list-group-item border-0 "><strong>Date of Joining: </strong> </li>
                                                <li class="list-group-item border-0 "><strong>Date of Birth:</strong> {{$userInfo->birth_date}}</li>
                                                <li class="list-group-item border-0 "><strong>Gender:</strong> {{$userInfo->gender}}</li>
                                                <li class="list-group-item border-0 "><strong>Department: </strong> {{$userInfo->department_name}}</li>
                                                <li class="list-group-item border-0 "><strong>Religion: </strong> </li>
                                                <li class="list-group-item border-0 "><strong>Marital Status:</strong> </strong> </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div> <!-- row -->
                    </div>
                    <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                        <div class="row">
                            
                        </div>
                    </div>
                </div>


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
                <form action="{{route('sub.settings.update')}}" method="POST">
                    @method('PUT')
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-danger" style="display:none"></div>
                        <div class="row pb-0">
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="name">Full Name *</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="designationId">Designation *</label>
                                <select class="form-control" name="designation" id="designationId">
                                    <option selected disabled>Select designation</option>
                                    @foreach(App\Models\Designation::where('company_id','=', Auth::user()->company_id)->get() as $designation)
                                    <option value="{{ $designation->id }}" {{ $designation->id === $userInfo->designation_id ? "selected" : ""}}>{{$designation->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="designation">Department *</label>
                                <select class="form-control" name="department" id="departmentId">
                                    <option selected disabled>Select Department</option>
                                    @foreach(App\Models\Department::where('company_id','=', Auth::user()->company_id)->get() as $department)
                                    <option value="{{ $department->id }}" {{ $department->id === $userInfo->department_id ? "selected" : ""}}>{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="email">Email*</label>
                                    <input type="email" class="form-control" disabled id="email" name="email" placeholder="Enter email">
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="form-group">
                                    <label for="mobile">Mobile *</label>
                                    <input type="text" class="form-control" name="phone" id="mobile" placeholder="Enter phone">
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="form-group">
                                    <label for="pabx">Pabx</label>
                                    <input type="text" class="form-control" id="pabx" name="pabx" placeholder="Enter phone">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group" id="">
                                    <label for="birthdate">Birth Date</label>
                                    <div class="input-group date datepicker" id="gender_datepicker">
                                        <input type="date" name="birthdate" value="{{!empty($userInfo->birth_date) ? $userInfo->birth_date: ''}}" class="form-control" id="birthdate">
                                        <!-- <span class="input-group-addon"><i data-feather="calendar"></i></span> -->
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="gender">Gender</label>
                                <select class="form-control" name="gender" id="gender">
                                    <option selected disabled>Select Gender</option>
                                    <option value='male' {{!empty($userInfo->gender)  && $userInfo->gender=='male' ? 'selected':''}}>Male</option>
                                    <option value='female' {{ !empty($userInfo->gender)  && $userInfo->gender=='female' ? 'selected': ''}}>Female</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="report_to">Report To *</label>
                                <select class="livesearch w-100 form-control" id="employeedId" name="report_to" data-width="100%">
                                </select>

                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input type="address" class="form-control" id="address" name="address" placeholder="Enter address">
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="form-group">
                                    <label for="blood">Blood Broup</label>
                                    <input type="text" id="blood" class="form-control" name="blood" placeholder="Enter blood">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="ip_address">IP Address *</label>
                                    <input type="text" id="ip_address" class="form-control" name="ip_address" placeholder="Enter ip address">
                                </div>
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
<script src="{{asset('backend/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('backend/assets/js/datepicker.js')}}"></script>
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

        $('.livesearch').select2({
            dropdownParent: $('#userUpdateModal'),
            placeholder: 'Select user',
            ajax: {
                url: "{{ url('/employee/search') }}",
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        // results: $.map(data, function (item) {
                        //     return {
                        //         text: item.name,
                        //         id: item.id
                        //     }
                        // })
                        results: data,
                    };
                },
                cache: true
            }
        });

        // if($('#gender_datepicker #datepicker').length) {
        //     var date = new Date();
        //     var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
        //     $('#gender_datepicker #datepicker').datepicker({
        //     format: "mm/dd/yyyy",
        //     todayHighlight: true,
        //     autoclose: true
        //     });
        //     $('#gender_datepicker #datepicker').datepicker('setDate', today);
        // }


        $('#userUpdateModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var emp_id = button.data('emp_id');
            var name = button.data('name');
            var email = button.data('email');
            var mobile = button.data('mobile');
            var pabx = button.data('pabx');
            var birthdate = button.data('birthdate');
            var gender = button.data('gender');
            var reportto_id = button.data('reportto_id');
            var address = button.data('address');
            var blood = button.data('blood');
            var ip = button.data('ip');
            var department = button.data('department');
            var designation = button.data('designation');

            var modal = $(this);

            modal.find('.modal-body #name').val(name);
            modal.find('.modal-body #employee_id').val(emp_id);
            modal.find('.modal-body #email').val(email);
            modal.find('.modal-body #mobile').val(mobile);
            modal.find('.modal-body #pabx').val(pabx);
            modal.find('.modal-body #birthdate').val(birthdate);
            modal.find('.modal-body #report_to').val(reportto_id);
            modal.find('.modal-body #address').val(address);
            modal.find('.modal-body #blood').val(blood);
            modal.find('.modal-body #ip_address').val(ip);
        })


    });
</script>
@endpush