@extends('layouts.sub')
@section('title','Ticket')
@push('css')
    <link rel="stylesheet" href="{{asset('backend/assets/vendors/select2/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css')}}">

    <style>
        body.modal-open {
            overflow: auto;
        } 
        table.dataTable td {
            font-size: 12px;
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

    <div class="d-flex justify-content-center align-items-center flex-wrap grid-margin">
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <h4 class="mb-3 mb-md-0">LAST 3 MONTHS COMPANY AND OFFICER WISE DATA</h4>
        </div>
    </div>

    <div class="row">
        @include('backend.slices.messages')
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-header">
                    Ticekts By Company
                </div>
                <ul class="list-group">
                    @foreach($tkt_month as $tkts)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{$tkts->company_name}}
                        <span class="badge bg-primary rounded-pill">{{$tkts->total}}</span>
                    </li>
                    @endforeach
                </ul>

            </div>
        </div>
        <div class="col-xl-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                <h6 class="card-title">Last 3 Months Tickets By Officer</h6>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <!-- <th class="pt-0">#</th> -->
                            <th class="pt-0">Name</th>
                            <th class="pt-0">New</th>
                            <th class="pt-0">Assign</th>
                            <th class="pt-0">Ong</th>
                            <th class="pt-0">Comp</th>
                            <th class="pt-0">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($supporterTkByStatus as $user_tkt)
                            @php 
                                $st_color = '';
                                if($user_tkt->u_status == 'RG'){
                                    $st_color = '#6c757d';
                                }
                            @endphp
                            <tr>
                                <td>
                                    <a href="#" class="d-flex align-items-center">
                                        <div class="w-100">
                                            <div class="d-flex justify-content-left">
                                                <p class="text-body" style="color: {{ $st_color }} !important">{{ $user_tkt->user_name }}</p>
                                            </div>
                                        </div>
                                    </a>
                                </td>
                                <td>{{$user_tkt->new_tkt}}</td>
                                <td>{{$user_tkt->assign_tkt}}</td>
                                <td>{{$user_tkt->ongoing_tkt}}</td>
                                <td>{{$user_tkt->completed_tkt}}</td>
                                <td>{{$user_tkt->total}}</td>
                            </tr>
                            @endforeach
                            
                        
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div> 


        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-header">
                    Ticekts By Officer
                </div>
                <ul class="list-group">
                    @foreach($tkt_engin as $tkts)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{$tkts->user_name}}
                        <span class="badge bg-primary rounded-pill">{{$tkts->total}}</span>
                    </li>
                    @endforeach
                </ul>

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


        });
</script>
@endpush