@extends('layouts.sub')
@section('title','Ticket')
@push('css')
    <link rel="stylesheet" href="{{asset('backend/assets/vendors/toastr.js/toastr.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/vendors/select2/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/vendors/datatables.net-bs5/dataTables.bootstrap5.css')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/vendors/datatables.net/Buttons-2.2.3/css/buttons.bootstrap5.min.css')}}">
    <style>
        .table th, .datepicker table th, .table td, .datepicker table td {
            white-space: normal !important;
        }
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
                    <li class="breadcrumb-item active" aria-current="page">Support Ticket</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <button type="button" class="btn btn-outline-primary btn-icon-text me-2 mb-2 mb-md-0" data-bs-toggle="modal" data-bs-target="#userAddModal">
              <i class="btn-icon-prepend" data-feather="user-plus"></i>
              Add user
            </button>
            <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0" data-bs-toggle="modal" data-bs-target="#ticketModal">
              <i class="btn-icon-prepend" data-feather="plus-circle"></i>
              Add New
            </button>
        </div>
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
                    <div class="table-responsive">
                        <table id="dataTableTicketEngineer" class="table">
                        <thead>
                            <tr>
                                <th>Id#</th>
                                <th>Ticket Owner</th>
                                <th>Subject</th>
                                <th>Assign Time</th>
                                <th>Updated</th>
                                <th>Feedback</th>
                                <th>Status</th>
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


    <!-- start add new  User modal -->
    <div class="modal fade" id="userAddModal" tabindex="-1" role="dialog" aria-labelledby="userAddModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userAddModalLabel">New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <form action="{{route('sub.employee.store')}}" method="POST">
                    @csrf
                    <div class="modal-body">
                            <div class="alert alert-danger" style="display:none"></div>  
                            <div class="row pb-0">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="name">Full Name *</label>
                                        <input type="text" class="form-control" name="name" placeholder="Enter Name">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="employee_id">Employee Id *</label>
                                        <input type="text" class="form-control" name="emp_id" placeholder="Ex:1964">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email*</label>
                                        <input type="email" class="form-control" name="email" placeholder="Enter email">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Mobile *</label>
                                        <input type="text" class="form-control" name="mobile" placeholder="Enter phone">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="filter_company">Company *</label>
                                    <select class="form-control" name="company" id="filter_company" >
                                        <option selected disabled>Select company</option>
                                        @foreach(App\Models\Company::all() as $company)
                                        <option value="{{$company->comp_id}}">{{$company->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="departmentId">Department *</label>
                                    <select class="js-example-basic-single-1 form-control w-100" name="department" id="departmentId">
                                        <option selected disabled>Select department</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="designationId">Designation *</label>
                                    <select class="js-example-basic-single-1 form-control w-100" name="designation" id="designationId">
                                        <option disabled selected>Select designation</option>
                                    </select>
                                </div>
                                
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


    <!-- start add new  ticket modal -->
    <div class="modal fade" id="ticketModal" tabindex="-1" role="dialog" aria-labelledby="ticketModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ticketModalLabel">New Daily Log</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <form action="{{route('sub.ticket.dailyLogs')}}" method="POST">
                    @csrf
                    <div class="modal-body">
                            <div class="alert alert-danger" style="display:none"></div>  
                            <div class="row pb-0">
                                <div class="col-md-6">
                                    <label>For whom</label>
                                    <select class="livesearch w-100 form-control mb-3" id="employeedId" name="user_id" data-width="100%">
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="location" class="form-label mb-0">Present Work Location *</label>
                                    <select class="form-control mb-3 w-100 border-radius-0 select2_modal" name="location" id="location" data-width="100%">
                                        <option selected="" disabled></option>
                                        @foreach(App\Models\LookUp::orderBy('data', 'ASC')->where('type', 'location')->get() as $location)
                                        <option value="{{$location->id}}">{{$location->data}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mt-3 mb-3">
                                    <label for="category" class="form-label mb-0">Support Type *</label>
                                    <select class="form-control mb-3 w-100 border-radius-0 select2_modal" name="type" id="category" data-width="100%">
                                        <option selected="" disabled></option>
                                        @foreach(App\Models\TicketType::orderBy('id', 'ASC')->where('parent_id', NULL)->get() as $type_parent)
                                        <optgroup label="{{$type_parent->name}}">
                                            @foreach(App\Models\TicketType::orderBy('id', 'ASC')->where('parent_id', $type_parent->id)->get() as $tkt_type)
                                            <option value="{{$tkt_type->id}}">{{$tkt_type->name}}</option>
                                            @endforeach
                                        </optgroup>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="message" class="col-form-label">Message:</label>
                                <textarea class="form-control" name="message" rows="5" maxlength="200" id="message" placeholder="This Message has a limit of 200 chars."></textarea>
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

    <div class="modal fade" id="updateTicketModal" tabindex="-1" role="dialog" aria-labelledby="updateTicketModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Change Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" style="display:none"></div>  
                    <form id="ticketStatusForm">
                        <div class="form-group row pb-0">
                            <div class="col-md-6">
                                <label for="recipient-name" class="col-form-label mb-0">Priority</label>
                                <select class="form-control mb-3" disabled>
                                    <option selected="">Open this select menu</option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="recipient-name" class="col-form-label mb-0">Status</label>
                                <select class="form-control mb-3" id="status">
                                    <option value="3">Ongoing</option>
                                    <option value="4">Completed</option>
                                    <!-- @foreach(App\Models\TktStatus::all() as $status)
                                        <option value="{{$status->id}}">{{$status->name}}</option>
                                    @endforeach -->
                                </select>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="message-text" class="col-form-label">Message:</label>
                            <textarea class="form-control" rows="5" maxlength="100" id="message-text" placeholder="This Message has a limit of 100 chars." disabled></textarea>
                        </div>
                        <input type="hidden" id="ticket_id">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="ticketStatusSubmit" class="btn btn-primary">Updated</button>
                </div>
            </div>
        </div>
    </div>


</div>
            
@endsection

@push('js')
<script src="{{asset('backend/assets/vendors/datatables.net/jquery.dataTables.js')}}"></script>
<script src="{{asset('backend/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('backend/assets/vendors/toastr.js/toastr.min.js')}}"></script>
<script src="{{asset('backend/assets/js/data-table.js')}}"></script>

<script src="{{asset('backend/assets/vendors/select2/select2.min.js')}}"></script>
<script src="{{asset('backend/assets/js/select2.js')}}"></script>

<script src="{{asset('backend/assets/vendors/datatables.net/Buttons-2.2.3/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('backend/assets/vendors/datatables.net/Buttons-2.2.3/js/buttons.bootstrap5.min.js')}}"></script>
<script src="{{asset('backend/assets/vendors/datatables.net/Buttons-2.2.3/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('backend/assets/vendors/datatables.net/Buttons-2.2.3/js/buttons.print.min.js')}}"></script>
<script src="{{asset('backend/assets/vendors/datatables.net/Buttons-2.2.3/js/buttons.colVis.min.js')}}"></script>
<script src="{{asset('backend/assets/vendors/datatables.net/JSZip-2.5.0/jszip.min.js')}}"></script>
<script src="{{asset('backend/assets/vendors/datatables.net/pdfmake-0.1.36/pdfmake.min.js')}}"></script>
<script src="{{asset('backend/assets/vendors/datatables.net/pdfmake-0.1.36/vfs_fonts.js')}}"></script>


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

            // add user
            $('#ticketStatusSubmit').click(function(e){
                e.preventDefault();
                var status         =   $("#status option:selected").val();
                var ticket_id      =   $("input#ticket_id").val();
                $.ajax({
                    url: "{{ route('sub.ticket.update.status') }}",
                    method: 'post',
                    data: {
                        ticket_id: ticket_id, status:status
                    },
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
                            $('#updateTicketModal').modal('hide');
                        }
                    }
                });
            });

            // end add user

            $('#updateTicketModal').on('show.bs.modal', function (event) {
                var button       = $(event.relatedTarget) 
                var t_id         = button.data('tid');
                var priority     = button.data('priority') ;
                var status       = button.data('status');

                var modal = $(this);

                modal.find('.modal-body #ticket_id').val(t_id);
                modal.find('.modal-body #priority').val(priority);
                modal.find('.modal-body #status').val(status);
            })
            // Add the following code if you want the name of the file appear on select
            $(".custom-file-input").on("change", function() {
                var fileName = $(this).val().split("\\").pop();
                $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
            });


            $('.livesearch').select2({
                dropdownParent: $('#ticketModal'),
                placeholder: 'Select user',
                ajax: {
                    url: "{{ url('/employee/search') }}",
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            // results: $.map(data, function (item) {
                            //     return {
                            //         text: item.name,
                            //         id: item.id
                            //     }
                            // })
                            results:data,
                        };
                    },
                    cache: true
                }
            });


            $('#filter_company').change(function(){
                var company_id = $(this).val();
                if(company_id){
                    $.ajax({
                        type:"GET",
                        url:"{{url('get-department-designation')}}?company_id="+company_id,
                        success:function(res){ 
                            if(res){

                                $("#departmentId").empty();
                                $("#departmentId").append('<option disabled selected >Select department</option>');
                                for(var i = 0; i < res.deparments.length; i++){
                                    $("#departmentId").append('<option value="'+res.deparments[i].id+'">'+res.deparments[i].name+'</option>');
                                }  
                                $("#designationId").empty();
                                $("#designationId").append('<option disabled selected >Select designation</option>');
                                for(var i = 0; i < res.designations.length; i++){
                                    $("#designationId").append('<option value="'+res.designations[i].id+'">'+res.designations[i].name+'</option>');
                                }  
                                // $.each(res.deparments,function(key, value){
                                //     $("#departmentId").append('<option value="'+key+'">'+value+'</option>');
                                // });
                            }else{
                                $("#departmentId").empty();
                                $("#designationId").empty();
                            }
                        }
                    });
                }else{
                    $("#taskId").empty();
                }      
            });



            load_engineer_data();

            function load_engineer_data()
            {
                $('#dataTableTicketEngineer').DataTable({
                    "autoWidth": false,
                    processing: true,
                    serverSide: true,
                    "order": [[ 0, "desc" ]],
                    ajax:{
                        "url": "{{ route('sub.ticket.support.jsondata') }}",
                        "dataType": "json",
                        data:{}
                    },
                    columns: [
                        {data: 'ticket_id', name: 'ticket_id'},
                        {data: 'username', name: 'username'},
                        {data: 'description', name: 'description'},
                        {data: 'assign_time', name: 'assign_time'},
                        {data: 'updated_at', name: 'updated_at'},
                        {data: 'feedback', name: 'feedback'},
                        {data: 'status', name: 'status'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ]
                });

            }
            // end engineer data
            $('.js-example-basic-single-1').select2({
                dropdownParent: $('#userAddModal')
            });

            $('.select2_modal').select2({
                dropdownParent: $('#ticketModal')
            });

        });

</script>
@endpush