@php
$slug = Auth::user()->role->slug;
@endphp
@extends('layouts.'.$slug)
@section('title','Roster')
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

    .select2-container--default .select2-selection--single .select2-selection__arrow b::before {
        display: none;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: inherit !important;
    }

    .select2-container--default .select2-selection--single {
        border-radius: 1px !important;
        border: 1px solid #e8ebf1 !important;

    }
    .dt-buttons{
        float: left !important;
    }
</style>
@endpush

@section('content')

<div class="page-content">
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0"></h4>
            <nav class="page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item">event</li>
                    <li class="breadcrumb-item active" aria-current="page">Roster</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <a href="{{route('roster.index')}}" class="btn btn-info btn-icon-text mb-2 mb-md-0">
                <i class="btn-icon-prepend" data-feather="arrow-left"></i>
                Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            <div class="row flex-grow-1">
                @foreach($date_data as $data)
                <div class="col-md-2 grid-margin stretch-card">

                    <div class="card">
                        <a href="#" data-date="{{$data->event_start}}" class="eventDateLink">
                            <div class="card-body">
                                <h5 class="card-title">{{ date('d F', strtotime($data->event_start)) }}</h5>
                                <p class="card-text">{{$data->total}}</p>
                            </div>
                        </a>
                    </div>

                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Roster In this Month</h6>
                    <div class="table-responsive">
                        <table id="dataTableRoster" class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Date</th>
                                    <th>Department</th>
                                    <th>Department Head</th>
                                    <th>Create At</th>
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


    <!-- // modal delete -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Roster</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <h4>Are you sure you want to remove this record ?</h4>
                    <input type="hidden" id="delete_roster_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger deleteRosterBtn">Yes Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- end delete modal -->

</div>

@endsection

@push('js')

<script src="{{asset('backend/assets/vendors/datatables.net/jquery.dataTables.js')}}"></script>
<script src="{{asset('backend/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('backend/assets/js/data-table.js')}}"></script>
<script src="{{asset('backend/assets/vendors/toastr.js/toastr.min.js')}}"></script>

<script src="{{asset('backend/assets/vendors/select2/select2.min.js')}}"></script>
<script src="{{asset('backend/assets/js/select2.js')}}"></script>

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

        function printSuccessMsg(msg) {
            toastr.options = {
                "closeButton": true,
                "newestOnTop": true,
                "positionClass": "toast-top-right"
            };
            toastr.success(msg);
        }


        $('.eventDateLink').on('click', function() {
            let event_date = $(this).data('date');
            $('#dataTableRoster').DataTable().destroy();
            loadRoster(event_date);
        });

        function loadRoster(event_date = '') {
            //let department = $('.nav-item a.active').attr("data-url");
            $('#dataTableRoster').DataTable({
                "autoWidth": false,
                "lengthMenu": [
                    [10, 20, 50, 100, -1],
                    [10, 20, 50, 100, "All"]
                ],
                processing: true,
                serverSide: true,
                "order": [
                    [2, "asc"]
                ],
                dom: 'PQBlfritp',
                buttons: [{
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: [0, ':visible']
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 5]
                        }
                    },
                    'colvis'
                ],
                ajax: {
                    url: "{{route('roster.list')}}",
                    "dataType": "json",
                    data: {
                        event_date: event_date
                    },
                },
                columns: [
                        { data: 'id',name: 'id'},
                        {data: 'username', name: 'username'},
                        {data: 'event_start',name: 'event_start'},
                        {data: 'dept_name',name: 'dept_name'},
                        {data: 'head_name',name: 'head_name'},
                        {data: 'created_at',name: 'created_at'},
                        {data: 'action',name: 'action',orderable: false, searchable: false},
                ]

            });

        }

        loadRoster();


        // delete roster

        $('body').on('click', '.deleteRecord', function(e) {
            e.preventDefault();
            var roster_id = $(this).data("id");
            $('#delete_roster_id').val(roster_id);
            $('#deleteModal').modal('show');

        });

        $(document).on('click', '.deleteRosterBtn', function(e) {
            e.preventDefault();
            var roster_id = $("input#delete_roster_id").val();
            $.ajax({
                type: "DELETE",
                url: "{{url('/delete-event/')}}" + "/" + roster_id,
                success: function(response) {
                    console.log(response);
                    if (response.errors) {
                        printErrorMsg(response.errors);
                        $('#deleteModal').modal('hide')
                    } else {
                        $('#deleteModal').modal('hide');
                        printSuccessMsg(response.success);
                        $("#dataTableRoster").DataTable().draw();
                    }

                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        });




    });
</script>
@endpush