@extends('layouts.sub')
@section('title','Dashboard')
@push('css')
<link rel="stylesheet" href="{{asset('backend/assets/vendors/datatables.net-bs5/dataTables.bootstrap5.css')}}">
<style>
    .py-filter {
        padding-top: 0.6rem !important;
        padding-bottom: 0.6rem !important;
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
                                    <option value="" selected disabled>Select Department</option>
                                </select>
                            </div>
                            <button type="button" name="filter" id="filter" class="border-radius-0 btn btn-light ms-1 py-filter">Filter</button>
                            <button type="button" name="refresh" id="refresh" class="border-radius-0 btn btn-light py-filter ms-1">Refresh</button>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table id="dataTableUsers" class="table">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Company</th>
                                    <th>Deparment</th>
                                    <th>Email</th>
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

</div>

@endsection

@push('js')
<script src="{{asset('backend/assets/vendors/datatables.net/jquery.dataTables.js')}}"></script>
<script src="{{asset('backend/assets/vendors/datatables.net-bs5/dataTables.bootstrap5.js')}}"></script>
<script src="{{asset('backend/assets/js/data-table.js')}}"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {

        $('#filter_company').change(function() {
            var company_id = $(this).val();
            if (company_id) {
                $.ajax({
                    type: "GET",
                    url: "{{url('get-department')}}?company_id=" + company_id,
                    success: function(res) {
                        if (res) {
                            $("#departmentId").empty();
                            $("#departmentId").append('<option value="">Select department</option>');
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

        load_data();

        function load_data(company_id = '', department_id = '') {
            $('#dataTableUsers').DataTable({
                "autoWidth": false,
                columnDefs: [
                    { "width": "14%", "targets": [1, 2] }
                ],
                "lengthMenu": [
                    [20, 50, 100, 500, -1],
                    [20, 50, 100, 500, "All"]
                ],
                processing: true,
                serverSide: true,
                "order": [
                    [0, "ASC"]
                ],
                ajax: {
                    "url": "{{ route('sub.employee.jsondata') }}",
                    "dataType": "json",
                    data: {
                        company_id: company_id,
                        department_id: department_id
                    }
                },
                columns: [{
                        data: 'employee_id',
                        name: 'employee_id'
                    },
                    {
                        data: 'employee_name',
                        name: 'employee_name'
                    },
                    {
                        data: 'company_name',
                        name: 'company_name'
                    },
                    {
                        data: 'department_name',
                        name: 'department_name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'mobile',
                        name: 'mobile'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

        }


        $('#filter').click(function() {

            var company_id = $('#filter_company option:selected').val();
            var department_id = $('#departmentId option:selected').val();

            if (company_id != '' || department_id != '') {
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