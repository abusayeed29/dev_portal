@extends('layouts.sub')
@section('title','Ticket')
@push('css')
<link rel="stylesheet" href="{{asset('backend/assets/vendors/toastr.js/toastr.min.css')}}">
<link rel="stylesheet" href="{{asset('backend/assets/vendors/select2/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('backend/assets/vendors/datatables.net-bs5/dataTables.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('backend/assets/vendors/datatables.net/Buttons-2.2.3/css/buttons.bootstrap5.min.css')}}">
<link rel="stylesheet" href="{{asset('backend/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
<style>
    .table th,
    .datepicker table th,
    .table td,
    .datepicker table td {
        white-space: normal !important;
    }

    body.modal-open {
        overflow: auto;
    }

    table.dataTable td {
        font-size: 12px;
    }

    table.dataTable tr.greeClass {
        color: #27ae60;
    }

    table.dataTable tr.redClass {
        color: #FF4500;
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

    .select2-container .select2-selection--single {
        height: 35px;
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
                    <li class="breadcrumb-item active" aria-current="page">Manage Ticket</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            <div class="row flex-grow-1">
                @foreach($statuses as $status)
                <div class="col-md-2 grid-margin stretch-card">
                    <div class="card">
                        <a href="{{route('sub.ticket.status', $status->status_id )}}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <h6 class="card-title mb-3" style="color: {{$status->color}};">{{$status->status_name}}</h6>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <h3 class="mb-2" style="color: {{$status->color}};">{{$status->total}}</h3>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                @endforeach
                <div class="col-md-2 grid-margin">
                    <div class="card">
                        <a href="{{route('sub.ticket.status', 'pend' )}}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <h6 class="card-title mb-3" style="color: #ff6700">Pending</h6>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-md-12 col-xl-12">
                                        <h3 class="mb-2" style="color: #ff6700">{{$pending}}</h3>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- row -->

    <div class="row">
        @include('backend.slices.messages')
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <!-- <h6 class="card-title float-left">Ticket</h6> -->
                    <div class="advance-search float-right d-flex justify-content-end">
                        <form class="form-inline d-flex">
                            <!-- <div class="form-group">
                                <select class="livesearch w-100 form-control" id="employeedId" name="user_id" data-width="100%">
                                </select>
                            </div> -->
                            <div class="form-group input-daterange d-flex">
                                <input type="text" name="from_date" id="from_date" readonly class="form-control rounded-0" value="{{date('Y-m-d')}}">
                                <div class="input-group-addon">to</div>
                                <input type="text" name="to_date" id="to_date" readonly class="form-control rounded-0" value="{{date('Y-m-d', time() + 86400)}}">
                            </div>
                            <button type="button" name="filter" id="filter" class="btn btn-light ms-1 rounded-0">Filter</button>
                            <button type="button" name="refresh" id="refresh" class="btn btn-light rounded-0 ms-1">Refresh</button>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table id="dataTableTicketManage" class="table">
                            <thead>
                                <tr>
                                    <th>Id#</th>
                                    <th>Status</th>
                                    <th>Ticket Owner</th>
                                    <th>Engineer</th>
                                    <th>Subject</th>
                                    <th>Company</th>
                                    <th>Date</th>
                                    <th>Actions</th>
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

    <div class="modal fade" id="openAssignModal" tabindex="-1" role="dialog" aria-labelledby="openAssignModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content border-0">
                <div class="modal-header">
                    <h5 class="modal-title modal_title" id="openAssignModalLabel">Assign Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="submitAssignModal" class="btn btn-primary">Updated</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Status -->

    <div class="modal fade" id="updateTicketStatusModal" tabindex="-1" role="dialog" aria-labelledby="updateTicketStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Change Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <form id="ticketStatusForm">
                    <div class="modal-body">
                        <div class="alert alert-danger" style="display:none"></div>
                        <div class="form-content mb-4 row justify-content-md-center">
                            <div class="col-md-auto">
                                <div class="form-check mb-2 ps-0">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option selected disabled>Select Status</option>
                                        <option value="5">Rejected</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="reason" class="form-label">Reason *</label>
                                    <textarea class="form-control" name="reason" id="reason" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="ticket_id" id="ticket_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" id="ticketStatusCancel" class="btn btn-primary">Updated</button>
                    </div>
                </form>
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
<script src="{{asset('backend/assets/js/data-table.js')}}"></script>

<script src="{{asset('backend/assets/vendors/select2/select2.min.js')}}"></script>
<script src="{{asset('backend/assets/js/select2.js')}}"></script>

<script src="{{asset('backend/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('backend/assets/js/datepicker.js')}}"></script>


<script src="{{asset('backend/assets/vendors/datatables.net/Buttons-2.2.3/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('backend/assets/vendors/datatables.net/Buttons-2.2.3/js/buttons.bootstrap5.min.js')}}"></script>
<script src="{{asset('backend/assets/vendors/datatables.net/Buttons-2.2.3/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('backend/assets/vendors/datatables.net/Buttons-2.2.3/js/buttons.print.min.js')}}"></script>
<script src="{{asset('backend/assets/vendors/datatables.net/Buttons-2.2.3/js/buttons.colVis.min.js')}}"></script>
<script src="{{asset('backend/assets/vendors/datatables.net/JSZip-2.5.0/jszip.min.js')}}"></script>
<script src="{{asset('backend/assets/vendors/datatables.net/pdfmake-0.1.36/pdfmake.min.js')}}"></script>
<script src="{{asset('backend/assets/vendors/datatables.net/pdfmake-0.1.36/vfs_fonts.js')}}"></script>


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

        // Add the following code if you want the name of the file appear on select
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });

        //$('.assignUser').select2();

        $('body').on('click', '.asaignModal', function(e) {
            e.preventDefault();
            var tkt_id = $(this).data('id');
            var type = $(this).data('type');
            var userId = $(this).data('userid');
            var description = $(this).data('description');
            var company = $(this).data('comp');
            // var modal = $(this);
            // modal.find('.description').text(description);

            $.ajax({
                url: "{{route('sub.ticket.assign.user')}}",
                type: 'POST',
                data: {
                    tkt_id: tkt_id,
                    type: type,
                    userId: userId,
                    description: description,
                    company: company
                },

                success: function(response) {
                    // Add response in Modal body
                    $('.modal-body').html(response.output);
                    // Display Modal
                    $('#openAssignModal').modal('show');
                }

            });
        });

        $('#submitAssignModal').click(function(e) {
            e.preventDefault();
            var staff_id = $('select#assignUser option:selected').val();
            var user_id = $('input#user_id').val();
            var tkt_id = $('input#ticket_id').val();
            var type = $('input#tkt_type_id').val();

            $.ajax({
                url: "{{route('sub.ticket.assign.update')}}",
                type: 'POST',
                data: {
                    staff_id: staff_id,
                    user_id: user_id,
                    tkt_id: tkt_id,
                    type: type
                },

                success: function(response) {
                    // Add response in Modal body
                    $('.modal-body').html(response.output);
                    // Display Modal
                    $('#productForm').trigger("reset");
                    $('#openAssignModal').modal('hide');
                    $("#dataTableTicketManage").DataTable().draw();
                    //setInterval('location.reload()', 500);
                }

            });
        });

        //display super visor datatable
        // $('#dataTableTicketManage').DataTable({
        //     "order": [[ 0, "desc" ]],
        // });

        load_supervisor_data();

        function load_supervisor_data(from_date = '', to_date = '', user_id = '') {
            $('#dataTableTicketManage').DataTable({
                "autoWidth": false,
                columnDefs: [{
                    "width": "20%",
                    "targets": [2, 4, 6]
                }],
                "lengthMenu": [
                    [20, 50, 100, 500, -1],
                    [20, 50, 100, 500, "All"]
                ],
                dom: 'lBfrtip',
                buttons: ['copy', 'excel', 'pdf', 'colvis'],
                processing: true,
                serverSide: true,
                "order": [
                    [6, "desc"]
                ],

                ajax: {
                    "url": "{{ route('sub.ticket.supervisor.jsondata') }}",
                    "dataType": "json",
                    data: {
                        from_date: from_date,
                        to_date: to_date,
                        emp_id: user_id
                    }
                },
                columns: [{
                        data: 'ticket_id',
                        name: 'ticket_id'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'username',
                        name: 'username'
                    },
                    {
                        data: 'engineer_name',
                        name: 'engineer_name'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'comp_shortname',
                        name: 'comp_shortname'
                    },
                    // {data: 'assign_time', name: 'assign_time'},
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],

                "createdRow": function(row, data, dataIndex) {
                    if (data['is_dlog']) {
                        $(row).addClass('redClass');
                    } else {
                        $(row).addClass('greeClass');
                    }
                }
            });

        }

        // end datatable

        $('.input-daterange input').each(function() {
            $(this).datepicker({
                "setDate": new Date(),
                format: 'yyyy-mm-dd',
                "autoclose": true
            });
        });

        $('#filter').click(function() {

            var from_date = $('input#from_date').val();
            var to_date = $('input#to_date').val();
            var user_id = $('#employeedId option:selected').val();

            if (from_date != '' && to_date != '') {
                $('#dataTableTicketManage').DataTable().destroy();
                load_supervisor_data(from_date, to_date, user_id);
            } else {
                alert('Both Data is required');
            }
        })

        $('#refresh').click(function() {
            $('#from_date').val('');
            $('#to_date').val('');
            $('#employeedId option:selected').val('');
            $('#dataTableTicketManage').DataTable().destroy();
            load_supervisor_data();
        })
        // end function

        // search engineer
        $('.livesearch').select2({
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

        // function for delete ticekt

        $('body').on('click', '.tktDeleteModal', function() {
            var ticket_id = $(this).data("id");
            var result = confirm("Are You sure want to delete !");
            if (result) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('sub.ticket.destroy') }}",
                    data: {
                        ticket_id: ticket_id
                    },
                    success: function(data) {
                        $("#dataTableTicketManage").DataTable().draw();
                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });
            } else {
                return false;
            }
        });

        // ticket rejected
        $('#updateTicketStatusModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var t_id = button.data('tid');
            var modal = $(this);
            modal.find('.modal-body #ticket_id').val(t_id);
        })

        $('#ticketStatusCancel').click(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "{{ route('sub.ticket.update.status.cancel') }}",
                data: $('#ticketStatusForm').serialize(),

                success: function(res) {

                    if (res.errors) {
                        $('.alert-danger').html('');
                        $.each(res.errors, function(key, value) {
                            $('.alert-danger').show();
                            $('.alert-danger').append('<li>' + value + '</li>');
                        });
                    } else {
                        printSuccessMsg(res.success);
                        $('.alert-danger').hide();
                        $('#ticketStatusForm').trigger("reset");
                        $("#dataTableTicketManage").DataTable().draw();
                        $('#updateTicketStatusModal').modal('hide');

                    }
                }

            });

        });

        // setTimeout("location.reload(true);", 30000);
        setInterval(function() {
            $("#dataTableTicketManage").DataTable().draw();
        }, 60000);

    });
</script>
@endpush