@php
$slug = Auth::user()->role->slug;
@endphp
@extends('layouts.'.$slug)
@section('title','Ticket')
@push('css')
<link rel="stylesheet" href="{{asset('backend/assets/vendors/toastr.js/toastr.min.css')}}">
<link rel="stylesheet" href="{{asset('backend/assets/vendors/select2/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('backend/assets/vendors/datatables.net-bs5/dataTables.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('backend/assets/vendors/datatables.net/Buttons-2.2.3/css/buttons.bootstrap5.min.css')}}">
<style>
     .table th,
    .datepicker table th,
    .table td,
    .datepicker table td {
        white-space: normal !important;
    }
    .nrel-pill {
        position: absolute;
        z-index: 10;
        top: 0;
        right: 20px;
    }
</style>
@endpush

@section('content')

<div class="page-content">

    <div class="d-flex justify-content-center align-items-center flex-wrap grid-margin">
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <h4 class="mb-3 mb-md-0">Company Tickets </h4>
        </div>
    </div>

    <div class="row">
        @include('backend.slices.messages')

        @foreach($cmptkts as $tkts)
        <div class="col-md-2 grid-margin stretch-card">
            <a style="color: #000;" href="#" data-company="{{$tkts->company_id}}" class="btnCompanyTkt">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-0">{{$tkts->comp_name}}</h5>
                        <span class="badge bg-primary rounded-pill nrel-pill">{{$tkts->total}}</span>
                    </div>
                </div>
            </a>
        </div>
        @endforeach

    </div>

    <div class="row">
        <div class="">
            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="card-title float-left">Tickets</h6>
                    <div class="table-responsive">
                        <table id="dataTableTickets" class="table stripe">
                            <thead>
                                <tr>
                                    <th>#ID</th>
                                    <th>Message</th>
                                    <th>Category</th>
                                    <th>Department</th>
                                    <th>User.Info</th>
                                    <th>Company</th>
                                    <th>Support.Officer</th>
                                    <th>Created.AT</th>
                                    <th>Assigned.AT</th>
                                    <th>Started.AT</th>
                                    <th>Completed.AT</th>
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
<script src="{{asset('backend/assets/vendors/toastr.js/toastr.min.js')}}"></script>

<script src="{{asset('backend/assets/vendors/datatables.net/Buttons-2.2.3/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('backend/assets/vendors/datatables.net/Buttons-2.2.3/js/buttons.bootstrap5.min.js')}}"></script>
<script src="{{asset('backend/assets/vendors/datatables.net/Buttons-2.2.3/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('backend/assets/vendors/datatables.net/Buttons-2.2.3/js/buttons.print.min.js')}}"></script>
<script src="{{asset('backend/assets/vendors/datatables.net/Buttons-2.2.3/js/buttons.colVis.min.js')}}"></script>
<script src="{{asset('backend/assets/vendors/datatables.net/JSZip-2.5.0/jszip.min.js')}}"></script>
<script src="{{asset('backend/assets/vendors/datatables.net/pdfmake-0.1.36/pdfmake.min.js')}}"></script>
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

        //display super visor datatable
        function getTicketsData(comp_id = '') {

            $('#dataTableTickets').DataTable({
                processing: true,
                serverSide: true,
                dom: 'PQBlfritp',
                "lengthMenu": [ [10, 50, 100, 500, -1], [10, 50, 100, 500, "All"] ],
                buttons: [{
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: [0, ':visible']
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [0,1,2,3,4,5,6,7,8,9,10]
                        }
                    },
                    'pdf',
                    'print'
                ],
                "order": [[ 0, "DESC" ]],
                "autoWidth": false,
                columnDefs: [
                    { "width": "30%", "targets": [1] },
                ],
                ajax: {
                    url: "{{url('tickets/company-reports')}}",
                    "dataType": "json",
                    data: {
                        comp_id: comp_id
                    },
                },
                columns:[
                    {data: 'id',name: 'id'},
                    {data: 'message',name: 'message'},
                    {data: 'tkt_type',name: 'tkt_type'},
                    {data: 'department',name: 'department'},
                    {data: 'user_name',name: 'user_name'},
                    {data: 'comp_name',name: 'comp_name'},
                    {data: 'sup_officer',name: 'sup_officer'},
                    {data: 'created_at',name: 'created_at'},
                    {data: 'assigned_at',name: 'assigned_at'},
                    {data: 'started_at',name: 'started_at'},
                    {data: 'completed_at',name: 'completed_at'},
                ]
            });
        }
        getTicketsData();

        $('.btnCompanyTkt').on('click', function() {
            let company_id = $(this).data('company');
            $('#dataTableTickets').DataTable().destroy();
            getTicketsData(company_id);
        });

    });
</script>
@endpush