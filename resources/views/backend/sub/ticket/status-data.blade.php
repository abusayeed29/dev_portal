@extends('layouts.sub')
@section('title','Ticket')
@push('css')
    <link rel="stylesheet" href="{{asset('backend/assets/vendors/select2/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/vendors/datatables.net-bs5/dataTables.bootstrap5.css')}}">

    <style>
        .table th, .datepicker table th, .table td, .datepicker table td {
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
        .select2-container .select2-selection--single{
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
            @if(!empty($tkt_status))
            @foreach($tkt_status as $status)
            <div class="row flex-grow">

                <div class="col-md-3 grid-margin stretch-card">
                    <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <h6 class="card-title mb-0">Total Tickets</h6>
                        </div>
                        <div class="row">
                            <div class="col-6 col-md-12 col-xl-5 mt-3">
                                <h3 class="mb-2">{{sprintf("%03d", $status->ID)}}</h3>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>

                <div class="col-md-3 grid-margin stretch-card">
                    <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <h6 class="card-title mb-0">Solved Tickets</h6>
                        </div>
                        <div class="row">
                            <div class="col-6 col-md-12 col-xl-5 mt-3">
                                <h3 class="mb-2">{{$status->completed}}</h3>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>

                <div class="col-md-3 grid-margin stretch-card">
                    <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <h6 class="card-title mb-0">Open Tickets</h6>
                        </div>
                        <div class="row">
                            <div class="col-6 col-md-12 col-xl-5 mt-3">
                                <h3 class="mb-2">{{$status->open}}</h3>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>

                <div class="col-md-3 grid-margin stretch-card">
                    <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <h6 class="card-title mb-0">Ongoing Tickets</h6>
                        </div>
                        <div class="row">
                            <div class="col-6 col-md-12 col-xl-5 mt-3">
                                <h3 class="mb-2">{{$status->ongoing}}</h3>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>

            </div>
            @endforeach
            @endif
        </div>
    </div> <!-- row -->


    <div class="row">
        @include('backend.slices.messages')
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title float-left">Ticket</h6>
                    <div class="table-responsive">
                        <table id="dataTableTicketStatus" class="table">
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
                            @foreach($data_status as $tkt_status)
                            <tr class="{{!empty($tkt_status->tkt_type_id) ? 'greeClass': 'redClass'}}">
                            <td>{{ date('md', strtotime($tkt_status->created_at)).$tkt_status->id}}</td>
                                <td><span class="badge" style="color:#fff; background-color:{{ $tkt_status->color ? $tkt_status->color : '#ff6700'}}">{{$tkt_status->status_name}}</span></td>
                                <td>{{ $tkt_status->tktowner }}
                                    <p>Work Place: {{$tkt_status->location_name}}</p>
                                    <p>Mobile: {{$tkt_status->phone}}</p>
                                    <p>Email: {{$tkt_status->email}}</p>
                                </td>
                                
                                <td>{{ $tkt_status->engineer }}</td>
                                <td> {{ substr($tkt_status->description, 0, 100) }}</td>
                                <td>{{$tkt_status->cmpsrt_name}}</td>
                                <td>{{$tkt_status->updated_at}}</td>
                                <td>
                                    <!-- <a href="javascript:void(0)" class="asaignModal btn btn-light align-items-center btn-sm btn-sm-custom" data-description="{{$tkt_status->description}}" data-comp="{{$tkt_status->company_name}}" data-id="{{$tkt_status->id}}" data-userid="{{$tkt_status->user_id}}" data-type="{{$tkt_status->tkt_type_id}}">Assign
                                        </a> -->
                                        <div class="d-flex">
                                            <a href="javascript:void(0)" class="asaignModal btn btn-light align-items-center btn-sm btn-sm-custom" 
                                            data-description="{{$tkt_status->description}}"
                                            data-comp="{{$tkt_status->cmpsrt_name}}"
                                            data-id="{{$tkt_status->id}}" 
                                            data-userid="{{ $tkt_status->user_id }}" 
                                            data-type="{{ $tkt_status->tkt_type_id }}" >Assign</i>
                                            </a>
                                            <a href="{{route('sub.ticket.show', $tkt_status->id)}}" class="btn btn-light align-items-center btn-sm btn-sm-custom ms-1">View</a>
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
    </div>

    <div class="modal fade" id="openAssignModal" tabindex="-1" role="dialog" aria-labelledby="openAssignModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="openAssignModalLabel">Update Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <form id="formAssign">
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="submitAssignModal" class="btn btn-primary">Updated</button>
                </div>
                </form>
            </div>
        </div>
    </div>


</div>
            
@endsection

@push('js')
<script src="{{asset('backend/assets/vendors/datatables.net/jquery.dataTables.js')}}"></script>
<script src="{{asset('backend/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('backend/assets/js/data-table.js')}}"></script>
<script src="{{asset('backend/assets/vendors/select2/select2.min.js')}}"></script>
<script src="{{asset('backend/assets/js/select2.js')}}"></script>

<script src="{{asset('backend/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('backend/assets/js/datepicker.js')}}"></script>

<script>
        $(document).ready(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            function printErrorMsg (msg) {
                toastr.options = {
                    "closeButton": true,
                    "newestOnTop": true,
                    "positionClass": "toast-top-right"
                };
                var error_html = '';
                for(var count = 0; count < msg.length; count++){
                    error_html += '<p>'+msg[count]+'</p>';
                }
                toastr.error(error_html);
            }

            //display super visor datatable
            $('#dataTableTicketStatus').DataTable({
                "order": [[ 0, "desc" ]],
            });

            $('body').on('click', '.asaignModal', function (e) {
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
                    data: {tkt_id: tkt_id, type:type, userId:userId, description:description, company:company},

                    success: function(response){ 
                        // Add response in Modal body
                        $('.modal-body').html(response.output);
                        // Display Modal
                        $('#openAssignModal').modal('show'); 
                    }

                });
            });

            $('#submitAssignModal').click(function(e){
                e.preventDefault();
                var staff_id   = $('select#assignUser option:selected').val();
                var user_id = $('input#user_id').val();
                var tkt_id = $('input#ticket_id').val();
                var type = $('input#tkt_type_id').val();

                $.ajax({
                    url: "{{route('sub.ticket.assign.update')}}",
                    type: 'POST',
                    data: {staff_id: staff_id, user_id:user_id, tkt_id:tkt_id, type:type},

                    success: function(response){ 
                        // Add response in Modal body
                        $('.modal-body').html(response.output);
                        // Display Modal
                        $('#formAssign').trigger("reset");
                        $('#openAssignModal').modal('hide'); 
                        //$("#dataTableTicketStatus").DataTable().draw();
                        setInterval('location.reload()', 500);
                    }

                });
            });



        });
</script>
@endpush