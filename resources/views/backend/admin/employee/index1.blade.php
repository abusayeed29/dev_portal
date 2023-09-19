@extends('layouts.admin')
@section('title','Dashboard')
@push('css')
<link rel="stylesheet" href="{{asset('backend/assets/vendors/toastr.js/toastr.min.css')}}">
<link rel="stylesheet" href="{{asset('backend/assets/vendors/datatables.net-bs5/dataTables.bootstrap5.css')}}">
<style>
    .table th,
    .datepicker table th,
    .table td,
    .datepicker table td {
        white-space: normal !important;
    }
</style>
@endpush

@section('content')

<div class="page-content">

    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Employee</li>
        </ol>
    </nav>

    <div class="row">
        @include('backend.slices.messages')
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title float-left">Employee</h6>
                    <div class="d-flex justify-content-center">
                        <form class="form-inline d-flex">
                            <div class="input-daterange d-flex">
                                <select name="filter_company" id="filter_company" class="form-control border-radius-0" required>
                                    <option selected disabled>Select Company</option>
                                    @foreach(App\Models\Company::all() as $company)
                                    <option value="{{$company->comp_id}}">{{$company->name}}</option>
                                    @endforeach
                                </select>

                                <div class="input-group-addon"></div>

                                <select class="form-control border-radius-0" name="department" id="departmentId" required>
                                    <option value="" selected>Select Department</option>
                                </select>
                            </div>
                            <button type="button" name="filter" id="filter" class="border-radius-0 btn btn-light ml-1 py-filter ml-3">Filter</button>
                            <button type="button" name="refresh" id="refresh" class="border-radius-0 btn btn-light ml-1 py-filter ms-1">Refresh</button>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table id="dataTableUsers" class="table">
                            <thead>
                                <tr>
                                    <th>User.Id</th>
                                    <th>Employee.Name</th>
                                    <th>Employee.Id</th>
                                    <th>Company</th>
                                    <th>Deparment</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>mobile</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addWebModal" tabindex="-1" role="dialog" aria-labelledby="addWebModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addWebModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" style="display:none"></div>
                    <p>Do you want to add employee in the system ?</p>
                    <span id="emplabel_id"></span>
                    <input type="hidden" id="emp_id">
                </div>
                <div class="modal-footer">
                    <button type="button" id="addInUserTable" class="btn btn-primary">Update</button>
                </div>
            </div>
        </div>
    </div>


</div>

@endsection

@push('js')
<script src="{{asset('backend/assets/vendors/datatables.net/jquery.dataTables.js')}}"></script>
<script src="{{asset('backend/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('backend/assets/js/data-table.js')}}"></script>
<script src="{{asset('backend/assets/vendors/toastr.js/toastr.min.js')}}"></script>

<script>
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

    // patient Appointment

    // end appointment

    $('#addWebModal').on('show.bs.modal', function(e) {
        let btn = $(e.relatedTarget);
        let emp_id = btn.data('emp_id');
        let name = btn.data('emp_name');
        console.log(name);
        $(".modal-body #emp_id").val(emp_id); // then pass it to the button inside the modal
        $("#addWebModalLabel").text(name)
        $(".modal-body #emplabel_id").text(emp_id)

    })

    $('#addInUserTable').click(function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('admin.user.store') }}",
            method: 'post',
            data: {
                emp_id: $('input#emp_id').val(),
            },
            success: function(result) {
                if (result.errors) {
                    //printErrorMsg(result.errors);
                    $('.alert-danger').html('');
                    $.each(result.errors, function(key, value) {
                        $('.alert-danger').show();
                        $('.alert-danger').append('<li>' + value + '</li>');
                    });
                } else {
                    printSuccessMsg(result.success);
                    $('.alert-danger').hide();
                    $('#addWebModal').modal('hide');
                }
            }
        });
    });


    $(document).ready(function() {

        load_data();

        function load_data(company_id = '', department_id = '') {
            $('#dataTableUsers').DataTable({
                processing: true,
                serverSide: true,
                "autoWidth": false,
                columnDefs: [{
                    "width": "20%",
                    "targets": [1, 5]
                }],
                "lengthMenu": [
                    [20, 50, 100, 500, -1],
                    [20, 50, 100, 500, "All"]
                ],
                "order": [
                    [0, "DESC"]
                ],
                ajax: {
                    "url": "{{ route('admin.user.jsondata') }}",
                    "dataType": "json",
                    data: {
                        company_id: company_id,
                        department_id: department_id
                    }
                },
                columns: [
                    {data: 'user_id',name: 'user_id' },
                    {data: 'employee_name', name: 'employee_name' },
                    {data: 'employee_id', name: 'employee_id'},
                    {data: 'company_name', name: 'company_name'},
                    {data: 'department_name',name: 'department_name'},
                    {data: 'email', name: 'email' },
                    {data: 'status',name: 'status'},
                    {data: 'mobile',name: 'mobile'},
                    {data: 'action',name: 'action', orderable: false,searchable: false},
                ]
            });

        }


        $('#filter_company').change(function() {
            var company_id = $(this).val();
            if (company_id) {
                $.ajax({
                    type: "GET",
                    url: "{{url('get-department')}}?company_id=" + company_id,
                    success: function(res) {
                        if (res) {
                            $("#departmentId").empty();
                            $("#departmentId").append('<option>Open this select menu</option>');
                            for (var i = 0; i < res.deparments.length; i++) {
                                $("#departmentId").append('<option value="' + res.deparments[i].id + '">' + res.deparments[i].name + '</option>');
                            }
                            // $.each(res.deparments,function(key, value){
                            //     $("#departmentId").append('<option value="'+key+'">'+value+'</option>');
                            // });
                        } else {
                            $("#departmentId").empty();
                        }
                    }
                });
            } else {
                $("#taskId").empty();
            }
        });

        $('#filter').click(function() {

            var company_id = $('#filter_company option:selected').val();
            var department_id = $('#departmentId option:selected').val();

            if (company_id != '' && department_id != '') {
                $('#dataTableUsers').DataTable().destroy();
                load_data(company_id, department_id);
            } else {
                alert('Both Data is required');
            }

        })

        $('#refresh').click(function() {
            $('#filter_company').val('');
            $('#departmentId').val('');
            $('#dataTableUsers').DataTable().destroy();
            load_data();
        })


    });
</script>
@endpush