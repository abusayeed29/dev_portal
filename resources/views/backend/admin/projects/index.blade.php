@extends('layouts.admin')
@section('title','Dashboard')
@push('css')
    <link rel="stylesheet" href="{{asset('backend/assets/vendors/toastr.js/toastr.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css')}}">
@endpush

@section('content')

<div class="page-content">

    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Projects</li>
        </ol>
    </nav>

    <div class="row">
        @include('backend.slices.messages')
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title float-left">Projects</h6>
                    <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#exampleModal">  Add New </button>
                    <div class="table-responsive">
                        <table id="dataTableExample" class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>PROJECT NAME</th>
                                <th> Progress</th>
                                <th>Address</th>
                                <th>START DATE</th>
                                <th>STATUS</th>
                                <th>ASSIGN</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projects as $project)
                            <tr>
                                <td>{{$project->id}}</td>
                                <td>{{$project->name}}</td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </td>
                                <td>{{$project->location}}</td>
                                <td>{{$project->created_at}}</td>
                                <td><span class="badge {{$project->status=='upcoming' ? 'badge-warning ': 'badge-success'}}">{{$project->status}}</span></td>
                                <td></td>
                                <td>
                                    <a href="{{route('pnc.projects.edit',$project->id)}}" class="btn btn-light btn-sm btn-sm-custom"><i data-feather="edit-2" width="18" height="18"></i></a>
                                    <a href="{{route('pnc.projects.show',$project->id)}}" class="btn btn-light btn-sm btn-sm-custom"><i data-feather="eye" width="18" height="18"></i></a>
                                    <a href="#" class="btn btn-light btn-sm btn-sm-custom" data-toggle="modal" data-target="#projectTimelineModal"><i data-feather="printer" width="18" height="18"></i></a>
                                    <a href="{{route('pnc.projects.show',$project->id)}}" class="btn btn-light btn-sm btn-sm-custom"><i data-feather="download" width="18" height="18"></i></a>
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
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Projects</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-horizontal" action="{{ route('pnc.projects.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">  
                        <div class="alert alert-danger" style="display:none"></div>                           
                        <div class="form-group row">
                            <label for="name" class="col-sm-3 col-form-label">Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="name" name="name" autocomplete="off" placeholder="Project name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="location" class="col-sm-3 col-form-label">Location</label>
                            <div class="col-sm-9">
                                <input type="text" name="location" class="form-control" id="location" autocomplete="off" placeholder="location">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="type" class="col-sm-3 col-form-label">Project Type</label>
                            <div class="col-sm-9">
                                <select name="project_type" id="project_type" class="form-control mb-3">
                                    <option selected>Open this select menu</option>
                                    <option value="1">Residential</option>
                                    <option value="2">Commercial</option>
                                    <option value="3">Condominium</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="area_stories" class="col-sm-3 col-form-label">Area & Stories</label>
                            <div class="col-sm-9">
                                <input type="text" name="area_stories" class="form-control" id="area_stories" autocomplete="off" placeholder="Area and stories">
                            </div>
                        </div>                
                    </div>
                    <div class="modal-footer">
                        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                        <button type="button" id="formSubmit" class="btn btn-primary mr-2">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
        <div class="modal fade" id="projectTimelineModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
<script src="{{asset('backend/assets/vendors/toastr.js/toastr.min.js')}}"></script>
<script src="{{asset('backend/assets/js/data-table.js')}}"></script>
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

            $('#formSubmit').click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{ route('pnc.projects.store') }}",
                    method: 'post',
                    data: {
                        name: $('input#name').val(),
                        location: $('input#location').val(),
                        project_type: $('select#project_type option:selected').val(),
                        area_stories: $('input#area_stories').val(),
                    },
                    success: function(result){
                        if(result.errors)
                        {
                            //printErrorMsg(result.errors);
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
                            $('#exampleModal').modal('hide');
                        }
                    }
                });
            });
        });
</script>
@endpush