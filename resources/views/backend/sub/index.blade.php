@extends('layouts.sub')
@section('title','Dashboard')
@push('css')
    <!--  <link rel="stylesheet" href="{{asset('public/frontend/vendor/wowjs/animate.css')}}"> -->
    <!-- <link rel="stylesheet" href="{{asset('backend/assets/vendors/datatables.net-bs5/dataTables.bootstrap5.css')}}"> -->
    <style>
        .table th, .datepicker table th, .table td, .datepicker table td {
            white-space: normal !important;
        }
        .table th, .table td {
            padding: 0.2rem 0.5375rem !important;
        }
        .table-hover > tbody > tr:hover > * {
            --bs-table-accent-bg: #bdc3c7;
        }
        td.text-danger{
            color:#dc3545!important;
        }
    </style>
@endpush


@section('content')

<div class="page-content">

    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Welcome to Dashboard</h4>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <div class="input-group date datepicker wd-200 me-2 mb-2 mb-md-0" id="dashboardDate">
                <span class="input-group-text input-group-addon bg-transparent border-primary"><i data-feather="calendar" class=" text-primary"></i></span>
                <input type="text" class="form-control border-primary bg-transparent">
            </div>
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
                                    <h3 class="mb-2"  style="color: {{$status->color}};">{{$status->total}}</h3>
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
                                <h6 class="card-title mb-3" style="color: #ff6700">Pending (- 3 D)</h6>
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
                <div class="col-md-2 grid-margin">
                    <div class="card">
                        <a href="{{route('sub.ticket.status', 'today' )}}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-3" style="color: #ff6700">Today's</h6>
                                </div>
                                <div class="row">
                                <div class="col-12 col-md-12 col-xl-12">
                                    
                                    <h3 class="mb-2" style="color: #ff6700">{{$today_total}}</h3>
                                </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- row -->

    <div class="row">
        <div class="col-xl-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Today's Tickets By Company</h6>
                
                    <canvas id="chartjsDoughnutByCompanyToday"></canvas>
                    
                </div>
            </div>
        </div>
        <div class="col-xl-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                <h6 class="card-title">Daily Tickets</h6>
                    <canvas id="chartjsLineDailyTkt"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 grid-margin stretch-card"> 
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Today's Tickets By Status</h6>
                    <canvas id="chartjsByStatusDate"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 grid-margin stretch-card"> 
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">All Tickets By Status</h6>
                    <canvas id="chartjsDoughnutByStatus"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                <h6 class="card-title">Tickets By Engineer</h6>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <!-- <th class="pt-0">#</th> -->
                            <th class="pt-0">Engineer</th>
                            <th class="pt-0">New</th>
                            <th class="pt-0">Assign</th>
                            <th class="pt-0">Ong</th>
                            <th class="pt-0">Comp</th>
                            <th class="pt-0">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($usertTktByStatus as $user_tkt)
                            @php 
                                $st_color = '';
                                if($user_tkt->u_status == 'RG'){
                                    $st_color = '#6c757d';
                                }
                            @endphp
                            <tr>
                                <!-- <td>{{$user_tkt->emp_id}}</td> -->
                                <td>
                                    <a href="#" class="d-flex align-items-center">
                                        <div class="w-100">
                                            <div class="d-flex justify-content-left">
                                                <p class="text-body" style="color: {{ $st_color }} !important">{{ $user_tkt->username }} ({{$user_tkt->emp_id}}) - {{$user_tkt->company_name}}</p>
                                                <!-- <p>{{$user_tkt->emp_id}}</p> -->
                                            </div>
                                        </div>
                                    </a>
                                </td>
                                <td>{{$user_tkt->new_tkt}}</td>
                                <td>{{$user_tkt->open_tkt}}</td>
                                <td>{{$user_tkt->ongoing}}</td>
                                <td>{{$user_tkt->completed}}</td>
                                <td>{{$user_tkt->total}}</td>
                            </tr>
                            @endforeach
                            
                        
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div> 
        <div class="col-md-6 grid-margin"> 
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Tickets By Company</h6>
                    <canvas id="chartjsDoughnutByCompany"></canvas>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                <h6 class="card-title">Monthly Tickets</h6>
                <canvas id="chartjsBarMonthlyTikcet"></canvas>
                </div>
            </div>

        </div>
    </div>


    <div class="modal fade" id="phoneModal" tabindex="-1" aria-labelledby="phoneModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content border-radius-0">
            <div class="modal-header">
                <h5 class="modal-title" id="phoneModalLabel">Add Mobile</h5>
            </div>
            <form method="post" action="{{ route('sub.settings.profile.phone') }}">
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <label for="phone" class="form-label">Offical Mobile Number</label>
                        <input type="text" required name="phone" class="form-control border-radius-0" id="phone" placeholder="Ex: 01730729305">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
            </div>
        </div>
    </div>


</div>
            
@endsection

@push('js')
<script src="{{asset('backend/assets/js/chartjs.js')}}"></script>
<!-- <script src="{{asset('backend/assets/vendors/datatables.net/jquery.dataTables.js')}}"></script>
<script src="{{asset('backend/assets/vendors/datatables.net-bs5/dataTables.bootstrap5.js')}}"></script> -->
<script src="{{asset('backend/assets/js/data-table.js')}}"></script>

<script>
    var _labels = {!! json_encode($labels) !!};
    var _data = {!! json_encode($data) !!};
    var _bgcolor = {!! json_encode($bg_color) !!};

    var _clabels = {!! json_encode($clabels) !!};
    var _cdata = {!! json_encode($cdata) !!};
    var _cbgcolor = {!! json_encode($cbg_color) !!};

    var _mlabels = {!! json_encode($mlabels) !!};
    var _mdata = {!! json_encode($mdata) !!};

    var _wlabels = {!! json_encode($wlabels) !!};
    var _wdata = {!! json_encode($wdata) !!};

    var _cmplabels = {!! json_encode($cmplabels) !!};
    var _cmpdata = {!! json_encode($cmpdata) !!};

    var _tcmplabels = {!! json_encode($tcmplabels) !!};
    var _tcmpdata = {!! json_encode($tcmpdata) !!};


    $(document).ready(function(){
        
        var mobile_num = "{{ Auth::user()->phone }}";
        console.log(mobile_num);
        if(mobile_num){
            $("#phoneModal").modal('hide');
        }else{
            $('#phoneModal').modal({backdrop: 'static', keyboard: false}); 
            $("#phoneModal").modal('show');
        }
       
    });


</script>

@endpush