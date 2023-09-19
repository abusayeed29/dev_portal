@extends('layouts.sub')
@section('title','Dashboard')
@push('css')
    <link rel="stylesheet" href="{{asset('backend/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css')}}">
@endpush

@section('content')

<div class="page-content">

    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Leave</li>
        </ol>
    </nav>

    <div class="row">
        @include('backend.slices.messages')
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title float-left">Leave</h6>
                    <div class="table-responsive">
                    <table id="dataTableUsers" class="table">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>User Id</th>
                                <th>Company</th>
                                <th>Deparment</th>
                                <th>Email</th>
                                <th>joining date</th>
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
<script src="{{asset('backend/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('backend/assets/js/data-table.js')}}"></script>
<script>

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(function () {
        
        var table = $('#dataTableUsers').DataTable({
            processing: true,
            serverSide: true,
            "order": [[ 0, "ASC" ]],
            ajax:{
                "url": "{{ route('sub.employee.jsondata') }}",
                "dataType": "json",
                "type": "GET",
                data:{"_token":" {{csrf_token()}} "}
            },
            columns: [
                {data: 'employee_id', name: 'employee_id'},
                {data: 'employee_name', name: 'employee_name'},
                {data: 'user_id', name: 'user_id'},
                {data: 'company_name', name: 'company_name'},
                {data: 'department_name', name: 'department_name'},
                {data: 'email', name: 'email'},
                {data: 'joining_date', name: 'joining_date'},
                {data: 'mobile', name: 'mobile'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

    });
</script>
@endpush