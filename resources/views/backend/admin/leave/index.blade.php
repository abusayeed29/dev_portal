@extends('layouts.pnc')
@section('title','Dashboard')
@push('css')
    <!--  <link rel="stylesheet" href="{{asset('public/frontend/vendor/wowjs/animate.css')}}"> -->
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
                                <th>Address</th>
                                <th>START DATE</th>
                                <th>DUE DATE</th>
                                <th>STATUS</th>
                                <th>ASSIGN</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Unnamed</td>
                                <td>p-50, Gulshan Avenue</td>
                                <td>01/01/2020</td>
                                <td>26/04/2020</td>
                                <td><span class="badge badge-danger">Released</span></td>
                                <td>Leonardo Payne</td>
                                <td>
                                    <a href="{{route('pnc.projects.show','1')}}" class="btn btn-light btn-sm btn-sm-custom"><i data-feather="edit" width="18" height="18"></i></a>
                                    <a href="{{route('pnc.projects.details','1')}}" class="btn btn-light btn-sm btn-sm-custom"><i data-feather="eye" width="18" height="18"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>Unnamed</td>
                                <td>p-50, Gulshan Avenue</td>
                                <td>01/01/2020</td>
                                <td>26/04/2020</td>
                                <td><span class="badge badge-success">Review</span></td>
                                <td>Leonardo Payne</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>Unnamed</td>
                                <td>p-50, Gulshan Avenue</td>
                                <td>01/01/2020</td>
                                <td>26/04/2020</td>
                                <td><span class="badge badge-info-muted">Pending</span></td>
                                <td>Leonardo Payne</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>Unnamed</td>
                                <td>p-50, Gulshan Avenue</td>
                                <td>01/01/2020</td>
                                <td>26/04/2020</td>
                                <td><span class="badge badge-warning">Work in Progress</span></td>
                                <td>Leonardo Payne</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>Unnamed</td>
                                <td>p-50, Gulshan Avenue</td>
                                <td>01/01/2020</td>
                                <td>26/04/2020</td>
                                <td><span class="badge badge-primary">Coming soon</span></td>
                                <td>Leonardo Payne</td>
                                <td></td>
                            </tr>
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

</div>
            
@endsection

@push('js')
<script src="{{asset('backend/assets/vendors/datatables.net/jquery.dataTables.js')}}"></script>
<script src="{{asset('backend/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js')}}"></script>
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
                            $('.alert-danger').html('');

                            $.each(result.errors, function(key, value){
                                $('.alert-danger').show();
                                $('.alert-danger').append('<li>'+value+'</li>');
                            });
                        }
                        else
                        {
                            $('.alert-danger').hide();
                            $('#exampleModal').modal('hide');
                        }
                    }
                });
            });
        });
</script>
@endpush