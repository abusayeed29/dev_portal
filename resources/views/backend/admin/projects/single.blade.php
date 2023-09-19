@extends('layouts.admin')
@section('title','Dashboard')
@push('css')
    <!--  <link rel="stylesheet" href="{{asset('public/frontend/vendor/wowjs/animate.css')}}"> -->
    <link rel="stylesheet" href="{{asset('backend/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css')}}">
@endpush

@section('content')

    <div class="page-content">

        <nav class="page-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Projects</a></li>
                <li class="breadcrumb-item active" aria-current="page">Timeline</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Task Report</h4>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th> Name</th>
                                        <th> Department</th>
                                        <th>Start date</th>
                                        <th>Duration</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tasks as $task)
                                    <tr>
                                        <td> {{ $task->prTask->title }}</td>
                                        <td> {{$task->department->name}} </td>
                                        <td> {{ $task->created_at}}</td>
                                        <td> {{$task->duration}} </td>
                                        <td><span id="badge_{{$task->id}}" class="badge {{ !empty($task->status) && $task->status === 1 ? 'badge-danger' : 'badge-success'}}">
                                            @if(!empty($task->status) && $task->status === 1)
                                                Ongoing
                                            @elseif(!empty($task->status) && $task->status === 2)
                                                Completed
                                            @else
                                                New
                                            @endif
                                        </span></td>
                                        <td>
                                            <a href="#" class="btn btn-light btn-sm btn-sm-custom" data-toggle="modal" data-target="#exampleModal"><i data-feather="eye" width="18" height="18"></i></a>
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

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Work Timeline</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form class="forms-sample" action="">
                        <div class="modal-body">                             
                            <ul class="timeline">
                                <li class="event" data-date="12:30 - 1:00pm">
                                    <h3>Registration</h3>
                                    <p>Get here on time, it's first come first serve. Be late, get turned away.</p>
                                </li>
                                <li class="event" data-date="2:30 - 4:00pm">
                                    <h3>Opening Ceremony</h3>
                                    <p>Get ready for an exciting event, this will kick off in amazing fashion with MOP & Busta Rhymes as an opening show.</p>    
                                </li>
                                <li class="event" data-date="5:00 - 8:00pm">
                                    <h3>Main Event</h3>
                                    <p>This is where it all goes down. You will compete head to head with your friends and rivals. Get ready!</p>    
                                </li>
                                <li class="event" data-date="8:30 - 9:30pm">
                                    <h3>Closing Ceremony</h3>
                                    <p>See how is the victor and who are the losers. The big stage is where the winners bask in their own glory.</p>    
                                </li>
                            </ul>               
                        </div>
                        <div class="modal-footer">
                            <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                            <!-- <button type="submit" class="btn btn-primary mr-2">Save</button> -->
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
@endpush