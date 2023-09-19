@extends('layouts.sub')
@section('title','Ticket')
@push('css')
    <link rel="stylesheet" href="{{asset('backend/assets/vendors/toastr.js/toastr.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/vendors/select2/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/vendors/datatables.net-bs5/dataTables.bootstrap5.css')}}">
    <style>
        body.modal-open {
            overflow: auto;
        } 
        .table th, .datepicker table th, .table td, .datepicker table td {
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
    </style>
@endpush

@section('content')

<div class="page-content" id="pageContent">

    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0"></h4>
            <nav class="page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Ticket</li>
                </ol>
            </nav>
        </div>
        @include('backend.slices.ticket-button')
    </div>
    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            @if(!empty($tkt_status))
            <div class="row flex-grow-1">
                @foreach($tkt_status as $status)
                <div class="col-md-2 grid-margin stretch-card">
                    <div class="card">
                        <a href="#">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-3" style="color: {{$status->color}};">{{$status->status_name}}</h6>
                                </div>
                                <div class="row">
                                <div class="col-12">
                                    <h3 class="mb-2"  style="color: {{$status->color}};">{{$status->total}}</h3>
                                </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
 
            @endif
        </div>   
    </div> <!-- row -->


    <div class="row">
        @include('backend.slices.messages')
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title float-left">Ticket</h6>
                    @include('backend.slices.ticket-data')
                </div>
            </div>
        </div>
    </div>
    
    @include('backend.slices.ticket-modal')

</div>
            
@endsection

@push('js')
<script src="{{asset('backend/assets/vendors/datatables.net/jquery.dataTables.js')}}"></script>
<script src="{{asset('backend/assets/vendors/datatables.net-bs5/dataTables.bootstrap5.js')}}"></script>
<script src="{{asset('backend/assets/js/data-table.js')}}"></script>
<script src="{{asset('backend/assets/vendors/toastr.js/toastr.min.js')}}"></script>

<script src="{{asset('backend/assets/vendors/select2/select2.min.js')}}"></script>
<script src="{{asset('backend/assets/js/select2.js')}}"></script>

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

            function printSuccessMsg (msg) {
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

            // add new modal open
            $('#ticketModal').on('hidden.bs.modal', function(e) {
                $(this).find('#modalTicketForm').trigger('reset');
            });

            $('#pageContent').on('click', '.addNewTicketModal', function (e) {

                e.preventDefault();
                var nhd_department  = $(this).data('did');

                if(nhd_department>0){
                    // AJAX request
                    var url = "{{url('ticket-types')}}?department_id=" + nhd_department;
                    $.ajax({
                        url: url,
                        dataType: 'json',
                        success: function(res){
                            if (res) {
                                $("#tkt_type").empty();
                                $("#tkt_type").html(res.data);
                                $('input#filter_tktdepartment').val(nhd_department);
                                if(nhd_department==1){
                                    $('#ticketModalLabel').html("IT New Ticket");
                                }else{
                                    $('#ticketModalLabel').html("Admin New Ticket");
                                }
                            } else {
                                $("#tkt_type").empty();
                            }
                            // Display Modal
                            $('#ticketModal').modal('show'); 
                        }
                    });
                }
            });
            // end modal open


            $("#btnSubminTicket").click(function(e){
                e.preventDefault();
                var form_data = new FormData();
                //form_data.append("tkt_department", $("#filter_tkttype option:selected").val());
                form_data.append("tkt_department", $("input#filter_tktdepartment").val());
                form_data.append("type", $("#tkt_type option:selected").val());
                form_data.append("location", $("#location option:selected").val());
                // form_data.append("priority", $("#priority option:selected").val());
                form_data.append("description", $("textarea#message").val());
                form_data.append('file', $('input[type=file]#customFile')[0].files[0]); 

                $.ajax({
                    type:'POST',
                    url: "{{ route('sub.ticket.store') }}",
                    data:form_data,
                    processData: false,
                    contentType: false,
                    success: function(result){
                        if(result.errors)
                        {
                            $('.alert-danger').html('');
                            $.each(result.errors, function(key, value){
                                $('.alert-danger').show();
                                $('.alert-danger').append('<li>'+value+'</li>');
                            });
                        }
                        else
                        {
                            printSuccessMsg(result.success);
                            $('.alert-danger').hide();
                            //var routurl ="{{ url('employee/ticket') }}";
                            $("#ticketrow_id").prepend(result.ticket);
                            $('#ticketModal').modal('hide');
                            $("#dataTableTicket").DataTable().draw();
                        }
                    }

                });
            });

            //datatable

            // Start Department Wise New Ticket
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                e.target // newly activated tab
                e.relatedTarget // previous active tab
                let department = $(this).attr("data-url");
                let status = '';

                $('#dataTableTicket').DataTable().destroy();

                getDepartmentTkt(department, status);
            })

            function getDepartmentTkt(department = '', status='') {
                //let department = $('.nav-item a.active').attr("data-url");
                $('#dataTableTicket').DataTable({
                    "autoWidth": false,
                    "lengthMenu": [
                        [10, 20, 50, 100, -1],
                        [10, 20, 50, 100, "All"]
                    ],
                    processing: true,
                    serverSide: true,
                    "order": [
                        [0, "desc"]
                    ],
                    ajax: {
                        url: "{{route('department.ticket')}}",
                        "dataType": "json",
                        data: { department: department, status:status },
                    },
                    columns: [{ data: 'ticket_id',name: 'ticket_id'},
                        {data: 'description', name: 'description'},
                        {data: 'tkt_type',name: 'tkt_type'},
                        {data: 'created_at',name: 'created_at'},
                        {data: 'updated_at',name: 'updated_at'},
                        {data: 'status',name: 'status', orderable: false},
                        {data: 'action',name: 'action',orderable: false, searchable: false},
                    ]
                });
            }
            getDepartmentTkt();
            // end display deprtment wise datatable

            //end datatable

            $('.select2_modal').select2({
                dropdownParent: $('#ticketModal')
            });

            //tooltip
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
            })

            // start filter deparment tkt

        });
</script>
@endpush