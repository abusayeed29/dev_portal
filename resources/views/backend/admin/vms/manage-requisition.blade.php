@php
$slug = Auth::user()->role->slug;
@endphp

@extends('layouts.'.$slug)
@section('title','Requisition')
@push('css')
<link rel="stylesheet" href="{{asset('backend/assets/vendors/toastr.js/toastr.min.css')}}">
<link rel="stylesheet" href="{{asset('backend/assets/vendors/select2/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('backend/assets/vendors/datatables.net-bs5/dataTables.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('backend/assets/vendors/flatpickr/flatpickr.min.css')}}">
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
                    <li class="breadcrumb-item">VMS</li>
                    <li class="breadcrumb-item active" aria-current="page">Requsition</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        @include('backend.slices.messages')
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title float-left">Vehicle Requisition</h6>
                    <div class="table-responsive">
                        <table id="load_requisition_data" class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Token</th>
                                    <th>Requested.By</th>
                                    <th>Requisition.Time</th>
                                    <th>Release.Time</th>
                                    <th>Route</th>
                                    <th>Driver.Name</th>
                                    <th>Status</th>
                                    <th>Created.AT</th>
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
    @include('backend.slices.vrequisition-modal')
    @include('backend.slices.vrequi-approve-modal')
    @include('backend.slices.vrequi-release-modal')

</div>

@endsection

@push('js')
<script src="{{asset('backend/assets/vendors/datatables.net/jquery.dataTables.js')}}"></script>
<script src="{{asset('backend/assets/vendors/datatables.net-bs5/dataTables.bootstrap5.js')}}"></script>
<script src="{{asset('backend/assets/vendors/toastr.js/toastr.min.js')}}"></script>
<script src="{{asset('backend/assets/js/data-table.js')}}"></script>

<script src="{{asset('backend/assets/vendors/select2/select2.min.js')}}"></script>
<script src="{{asset('backend/assets/js/select2.js')}}"></script>

<script src="{{asset('backend/assets/vendors/moment/moment.min.js')}}"></script>
<script src="{{asset('backend/assets/vendors/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{asset('backend/assets/js/flatpickr.js')}}"></script>

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

        // Load Data
        $(function() {
            var table = $('#load_requisition_data').DataTable({
                "autoWidth": false,
                processing: true,
                serverSide: true,
                "order": [
                    [0, "DESC"]
                ],
                ajax: {
                    "url": "{{ route('admin.vms.manage') }}",
                    "dataType": "json",
                    "type": "GET",
                    data: {
                        "_token": " {{csrf_token()}} "
                    }
                },
                columns: [
                    { data: 'id',  name: 'id' },
                    { data: 'generate_id',  name: 'generate_id' },
                    { data: 'requister',  name: 'requister' },
                    { data: 'pick_drop_time',  name: 'pick_drop_time' },
                    { data: 'release_time',  name: 'release_time' },
                    { data: 'route',  name: 'route' },
                    { data: 'driver_name',  name: 'driver_name' },
                    { data: 'status', name: 'status' },
                    { data: 'created_at',  name: 'created_at' },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]

            });
        });

        // display approval modal
        $('body').on('click', '.approveModalSubmit', function() {

            var requisition_id = $(this).data('id');

            $.get("aprrove" + '/' + requisition_id + '/edit', function(data) {
                $('#approveRequisitionModalLabel').html("Approve Modal - " + data.vRequisition.generate_id);
                $('#btnApproveSubmit').val("Save");
                $('#approveRequisitionModal').modal('show');
                $('#requisition_id').val(data.vRequisition.id);
                $('#stageId').val(data.vRequisition.stage);
                $('#message_body').html('<strong>Date:</strong> ' + data.vRequisition.created_at + '<br><strong>Deatils</strong><br>Required Time: ' + data.vRequisition.pick_time + ' - ' + data.vRequisition.drop_time);
                $('#driverAssign').html(data.form_field);

                select2Live();
            })
        });

        $('body').on('click', '.releaseModal', function() {
            var requisition_id = $(this).data('id');
            $.get("aprrove" + '/' + requisition_id + '/edit', function(data) {
                $('#releaseModal').modal('show');
                $('#req_id').val(data.vRequisition.id);
            })
        });

        // search driver
        function select2Live() {
            $('.liveDriverSearch').select2({
                dropdownParent: $('#approveRequisitionModal'),
                placeholder: 'Select Driver',
                ajax: {
                    url: "{{ url('/driver/search') }}",
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

            $('.liveVehicleSearch').select2({
                dropdownParent: $('#approveRequisitionModal'),
            });
        }

        // end search driver

        // start approval submit modal
        $('#btnApproveSubmit').click(function(e) {
            e.preventDefault();

            let requisition_id = $('input#requisition_id').val();

            $.ajax({
                url: "{{ url('requisition/approve') }}",
                method: 'POST',
                data: $('#requisitionApproveForm').serialize(),
                success: function(result) {
                    if (result.errors) {
                        $('.alert-danger').html('');
                        printErrorMsg(result.errors);
                    } else {
                        printSuccessMsg(result.success);
                        $('.alert-danger').hide();
                        $('#approveRequisitionModal').modal('hide');
                        $('#requisitionApproveForm').trigger("reset");
                        $("#load_requisition_data").DataTable().draw();
                        //$('#requistion_row_' + requisition_id).fadeOut(1000);
                    }
                }
            });
        });

        // strat release modal function
        $('#btnReleaseSubmit').click(function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ url('vms/requisition/release') }}",
                method: 'POST',
                data: $('#requisitionReleaseForm').serialize(),
                success: function(result) {
                    if (result.errors) {
                        $('.alert-danger').html('');
                        printErrorMsg(result.errors);
                    } else {
                        printSuccessMsg(result.success);
                        $('.alert-danger').hide();
                        $('#releaseModal').modal('hide');
                        $('#requisitionReleaseForm').trigger("reset");
                        $("#load_requisition_data").DataTable().draw();
                    }
                }
            });
        });


        //start search
        $('.select2_modal').select2({
            dropdownParent: $('#approveRequisitionModal'),
        });

        // radio button approve
        $('input[type="radio"]').click(function() {
            var inputValue = $(this).attr("value");
            console.log(inputValue);
            if (inputValue == 0) {
                $("#notapprove_box").fadeIn();
                //$("#driverAssign").fadeOut();
            } else {
                $("#notapprove_box").fadeOut();
                //$("#driverAssign").fadeIn();
            }
        });



    });
</script>
@endpush