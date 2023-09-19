@extends('layouts.admin')
@section('title','VMS Settings')
@push('css')
<link rel="stylesheet" href="{{asset('backend/assets/vendors/toastr.js/toastr.min.css')}}">
<link rel="stylesheet" href="{{asset('backend/assets/vendors/select2/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('backend/assets/vendors/datatables.net-bs5/dataTables.bootstrap5.css')}}">
@endpush


@section('content')

<div class="page-content">

    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0"></h4>
            <nav class="page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">VMS</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Settings</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <button type="button" class="ms-1 btn btn-info btn-icon-text mb-2 mb-md-0 addNewapprovalPathModal" data-bs-toggle="modal" data-bs-target="#approvalPathModal" data-did="2">
                <i class="btn-icon-prepend" data-feather="plus-circle"></i>
                Add New
            </button>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12 col-xl-12 stretch-card mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0">Teams</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="pt-0">#</th>
                                    <th class="pt-0">Name</th>
                                    <th class="pt-0">Driver.Assigner</th>
                                    <th class="pt-0">Team Head</th>
                                    <th class="pt-0">Users.In.Team</th>
                                    <th class="pt-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach($teamLeads as $head)
                                <tr>
                                    <td>{{$head->team_id}}</td>
                                    <td>{{$head->team_name}}-{{$head->id}}</td>
                                    <td>{{$head->username}}</td>
                                    <td>{{$head->team_head}}</td>
                                    <td>
                                        @foreach($head->usersInTeam as $user)
                                        <a href="{{$user->user_id}}">{{$user->user_name}} - {{$user->emp_id}} </a><br>
                                        @endforeach
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <a class="d-flex align-items-center" href="javascript:;">
                                                <i data-feather="eye" class="icon-sm me-2"></i>
                                            </a>
                                            <a href="javascript:void(0)" data-id="{{$head->team_id}}" class="btn btn-light btn-sm btn-sm-custom addTeamUserModal"><i data-feather="edit" class="icon-sm me-2"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-xl-8 grid-margin grid-margin-xl-0">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0">1st Stage (Deparment Head) </h6>
                        <div class="dropdown mb-2">
                            <button class="btn p-0" type="button" id="dropdownMenuButton6" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton6">
                                <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-feather="eye" class="icon-sm me-2"></i> <span class="">View</span></a>
                                <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-feather="edit-2" class="icon-sm me-2"></i> <span class="">Edit</span></a>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        @php $i=1; @endphp
                        @foreach($dep_heads as $head)
                        <a href="{{$head->dept_id}}" class="d-flex align-items-center border-bottom pb-3">
                            <div class="me-3">
                                {{$i}}
                                <!-- <img src="../assets/images/faces/face2.jpg" class="rounded-circle wd-35" alt="user"> -->
                            </div>
                            <div class="w-100">
                                <div class="d-flex justify-content-between">
                                    <h6 class="text-body mb-2"> {{$head->dept_name}}({{$head->total}})</h6>
                                    <p class="text-muted tx-12">{{$head->company_name}}</p>
                                </div>
                                @foreach(App\Models\VmsApprovalPath::select('navana_portal.users.name as username')
                                ->leftJoin('navana_portal.users', 'vms_approval_paths.user_id','users.id')->where('dept_id',$head->dept_id)->get() as $user)
                                <p class="text-muted tx-13">{{$user->username}}</p>
                                @endforeach
                            </div>
                        </a>
                        @php $i++; @endphp
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-xl-4 grid-margin grid-margin-xl-0">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0">2nd Stage (Administrator) </h6>
                        <div class="dropdown mb-2">
                            <button class="btn p-0" type="button" id="dropdownMenuButton6" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton6">
                                <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-feather="eye" class="icon-sm me-2"></i> <span class="">View</span></a>
                                <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-feather="edit-2" class="icon-sm me-2"></i> <span class="">Edit</span></a>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        @php $i=1; @endphp
                        @foreach($stage_2 as $head)
                        <a href="#" class="d-flex align-items-center border-bottom pb-3">
                            <div class="me-3">
                                {{$i}}
                                <!-- <img src="../assets/images/faces/face2.jpg" class="rounded-circle wd-35" alt="user"> -->
                            </div>
                            <div class="w-100">
                                <div class="d-flex justify-content-between">
                                    <h6 class="text-body mb-2">{{$head->username}} | {{$head->stage}}</h6>
                                    <p class="text-muted tx-12">{{$head->company_id}}</p>
                                </div>
                                <p class="text-muted tx-13">{{$head->dept_name}}</p>
                            </div>
                        </a>
                        @php $i++; @endphp
                        @endforeach

                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0">3RD Stage (Transport) </h6>
                        <div class="dropdown mb-2">
                            <button class="btn p-0" type="button" id="dropdownMenuButton6" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton6">
                                <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-feather="eye" class="icon-sm me-2"></i> <span class="">View</span></a>
                                <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-feather="edit-2" class="icon-sm me-2"></i> <span class="">Edit</span></a>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        @php $i=1; @endphp
                        @foreach($stage_3 as $head)
                        <a href="#" class="d-flex align-items-center border-bottom pb-3">
                            <div class="me-3">
                                {{$i}}
                                <!-- <img src="../assets/images/faces/face2.jpg" class="rounded-circle wd-35" alt="user"> -->
                            </div>
                            <div class="w-100">
                                <div class="d-flex justify-content-between">
                                    <h6 class="text-body mb-2">{{$head->username}} | {{$head->stage}}</h6>
                                    <p class="text-muted tx-12">{{$head->company_id}}</p>
                                </div>
                                <p class="text-muted tx-13">{{$head->dept_name}}</p>
                            </div>
                        </a>
                        @php $i++; @endphp
                        @endforeach

                    </div>
                </div>
            </div>
        </div>

    </div>


    <!-- start add new  User modal -->
    <div class="modal fade" id="approvalPathModal" tabindex="-1" role="dialog" aria-labelledby="userAddModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userAddModalLabel">Set Approval Path</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <form action="{{route('admin.vms.settings.store')}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-danger" style="display:none"></div>
                        <div class="row pb-0">
                            <div class="col-md-4 mb-3">
                                <label for="employee_id">Stage *</label>
                                <select class="form-select mb-3" name="stage">
                                    <option selected="" disabled>Select stage</option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                </select>

                            </div>
                            <div class="col-md-8 mb-3">
                                <label for="userId">Select User *</label>
                                <select class="livesearch w-100 form-control mb-3" id="userId" name="user_id" data-width="100%">
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="employee_id">Team *</label>
                                <select class="form-select mb-3" name="team">
                                    <option selected="" disabled>Select team</option>
                                    @foreach(App\Models\VmsLookUp::where('data_type','req.team')->get() as $team)
                                    <option value="{{$team->data_keys}}">{{$team->data_values}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-8 mb-3">
                                <label for="deptHead">Notify User *</label>
                                <select class="livesearch w-100 form-control mb-3" id="notifyUser" name="notify_user" data-width="100%">
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="filter_company">Company *</label>
                                <select class="form-select mb-3" name="company" id="filter_company">
                                    <option selected disabled>Select company</option>
                                    @foreach(App\Models\Company::all() as $company)
                                    <option value="{{$company->comp_id}}">{{$company->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="departmentId">Department *</label>
                                <select class="form-select mb-3 w-100" name="department" id="departmentId">
                                    <option selected disabled>Select department</option>
                                </select>
                            </div>
                            <input type="hidden" class="form-control" id="path_id" name="path_id">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end add new modal -->
    <div class="modal fade" id="addTeamUserModal" tabindex="-1" role="dialog" aria-labelledby="addTeamUserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content border-radius-0">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTeamUserModalLabel">Add User Into this Team?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" style="display:none"></div>
                    <p id="message_body"></p>
                    <form id="addTeamUserForm">

                        <input type="hidden" name="team_id" id="team_id" value="">
                        <label for="userId">Select Users *</label>
                        <select class="w-100 form-control mb-3 searchUser" id="employeedId" name="user_id[]" data-width="100%" multiple="multiple">
                        </select>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnAddTeamUser" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>

</div>


@endsection

@push('js')
<script src="{{asset('backend/assets/vendors/datatables.net/jquery.dataTables.js')}}"></script>
<script src="{{asset('backend/assets/vendors/datatables.net-bs5/dataTables.bootstrap5.js')}}"></script>

<script src="{{asset('backend/assets/vendors/toastr.js/toastr.min.js')}}"></script>
<script src="{{asset('backend/assets/js/data-table.js')}}"></script>

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

        // end add user

        $('.livesearch').select2({
            dropdownParent: $('#approvalPathModal'),
            placeholder: 'Select user',
            ajax: {
                url: "{{ url('/employee/search') }}",
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data,
                    };
                },
                cache: true
            }
        });

        $('.select2_modal').select2({
            dropdownParent: $('#approvalPathModal')
        });

        $('#filter_company').change(function() {
            var company_id = $(this).val();
            if (company_id) {
                $.ajax({
                    type: "GET",
                    url: "{{url('get-department-designation')}}?company_id=" + company_id,
                    success: function(res) {
                        if (res) {

                            $("#departmentId").empty();
                            $("#departmentId").append('<option disabled selected >Select department</option>');
                            for (var i = 0; i < res.deparments.length; i++) {
                                $("#departmentId").append('<option value="' + res.deparments[i].id + '">' + res.deparments[i].name + '</option>');
                            }

                        } else {
                            $("#departmentId").empty();
                        }
                    }
                });
            } else {
                $("#taskId").empty();
            }
        });


        $('body').on('click', '.addTeamUserModal', function() {
            var team_id = $(this).data('id');
            $.get("{{ url('admin/vms/settings/') }}" + '/' + team_id + '/edit', function(data) {
                $('#modelHeading').html("Add New Team");
                $('#savedata').val("edit-user");
                $('#addTeamUserModal').modal('show');
                $('#team_id').val(team_id);
                select2Live();
            })
        });

        function select2Live() {
            $('.searchUser').select2({
                dropdownParent: $('#addTeamUserModal'),
                placeholder: 'Select user',
                ajax: {
                    url: "{{ url('/employee/search') }}",
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: data,
                        };
                    },
                    cache: true
                }
            });
        }

        $('#btnAddTeamUser').click(function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{route('admin.vms.settings.team.user')}}",
                method: 'POST',
                data: $('#addTeamUserForm').serialize(),
                success: function(response) {
                    console.log(response);
                    if (response.errors) {
                        printErrorMsg(response.errors);
                    } else {
                        printSuccessMsg(response.success);
                        $('.alert-danger').hide();
                        $('#addTeamUserForm').trigger("reset");
                        $("#addTeamUserModal").modal("hide");
                        window.location.reload(true);
                    }
                }
            });
        });


    });
</script>

@endpush