@extends('layouts.sub')
@section('title','Project create')
@push('css')

@endpush

@section('content')

<div class="page-content">

    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Project</a></li>
            <li class="breadcrumb-item active" aria-current="page">Update</li>
        </ol>
    </nav>

    @if(!empty($taskPerm->create) && $taskPerm->create === 1)
    <div class="row">

        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">ADD Department Wise Task</h6>
                    @include('backend.slices.messages')
                    <form class="forms-sample" action="{{ route('user.tasks.duration.store') }}" method="post">
                        @csrf
                            <div class="row">
                            <div class="form-group col-3">
                                <label for="departmentId">Department</label>
                                <select class="form-control mb-3" name="department" id="departmentId">
                                    <option selected>Open this select menu</option>
                                    <option value="14">Land Development</option>
                                    <option value="8">Design & Development</option>
                                    <option value="15">Logistics</option>
                                    <option value="9">Engineering</option>
                                </select>
                            </div>
                            <div class="form-group col-3">
                                <label for="task">Task</label>
                                <select class="form-control mb-3" name="task" id="taskId">
                                    <option value="" selected>Open this select menu</option>
                                </select>
                            </div>
                            <div class="form-group col-3">
                                <label for="duration">Duration (Days)</label>
                                <input type="number" class="form-control" name="duration" id="duration" placeholder="Duration">
                                <input type="hidden" class="form-control" value="{{$project->id}}" name="project_id">
                            </div>
                            <div class="form-group col-3 mt-3 py-3">
                                <button type="submit" class="btn btn-primary mr-2">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Task List</h4>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        #
                                    </th>
                                    <th>
                                        Name
                                    </th>
                                    <th>
                                        Department
                                    </th>
                                    <th>
                                        Duration (Days)
                                    </th>
                                    <th>
                                        Created date
                                    </th>
                                    <th>
                                        Status
                                    </th>
                                    <th>
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tasks as $task)
                                <tr>
                                    <td> {{ $task->id }}</td>
                                    <td> {{ $task->prTask->title }}</td>
                                    <td> {{$task->department->name}} </td>
                                    <td> {{$task->duration}} </td>
                                    <td> {{ $task->created_at}}</td>
                                    <td>
                                        <span id="badge_{{$task->id}}" class="badge {{ !empty($task->status) && $task->status === 1 ? 'badge-danger' : 'badge-success'}}">
                                            @if(!empty($task->status) && $task->status === 1)
                                                Ongoing
                                            @elseif(!empty($task->status) && $task->status === 2)
                                                Completed
                                            @else
                                                New
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <a class="btn btn-light align-items-center" href="#" data-toggle="modal" data-tid="{{$task->id}}" data-tasklabel="{{ $task->prTask->title }}" data-project_id="{{$task->project_id}}"  data-target="#changeTaskStatus"><i data-feather="edit-2" class="icon-sm mr-2"></i> <span class="">Edit</span></a>
                                        <a class="btn btn-light align-items-center" href="#" data-toggle="modal" data-target="#exampleModal"><i data-feather="eye" class="icon-sm mr-2"></i> <span class="">View</span></a>
                                        <a href="#" class="btn btn-light btn-sm btn-sm-custom"><i data-feather="trash" width="18" height="18"></i></a>
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
        <div class="modal fade" id="changeTaskStatus" tabindex="-1" role="dialog" aria-labelledby="changeTaskStatus" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="taskLabel">Change Task Status</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form class="form-horizontal" action="#">
                        @csrf
                        <div class="modal-body">  
                            <div class="alert alert-danger" style="display:none"></div>                           
                            <div class="form-group row">
                                <label for="type" class="col-sm-2 col-form-label">Status</label>
                                <div class="col-sm-7">
                                    <select name="project_type" id="project_type" class="form-control mb-3">
                                        <option value="" selected>Change status</option>
                                        <option value="1">Ongoing</option>
                                        <option value="2">Completed</option>
                                    </select>
                                </div>
                                <input type="hidden" value="" id="task_id">
                                <input type="hidden" value="" id="project_id">
                                <input type="hidden" id="task_title">
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
<script src="{{asset('backend/assets/js/file-upload.js')}}"></script>
<script>
    // start country
    $('#departmentId').change(function(){
        var department_id = $(this).val(); 
        if(department_id){
            $.ajax({
                type:"GET",
                url:"{{url('get-task')}}?department_id="+department_id,
                success:function(res){           
                    if(res){
                        $("#taskId").empty();
                        $("#taskId").append('<option>Open this select menu</option>');
                        $.each(res.tasks,function(key,value){
                            $("#taskId").append('<option value="'+key+'">'+value+'</option>');
                        });
                    }else{
                        $("#taskId").empty();
                    }
                }
            });
        }else{
            $("#taskId").empty();
        }      
    });

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
                var task_id = $('input#task_id').val();
                var project_id = $('input#project_id').val();
                var status = $('#project_type option:selected').val()
                $.ajax({
                    url: "{{ route('user.projects.task.change') }}",
                    method: 'post',
                    data: { task_id:task_id, project_id:project_id, status:status},

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
                            console.log(result.status);

                            $('.alert-danger').hide();
                            $('#changeTaskStatus').modal('hide');
                            if(result.status==1){
                                $('#badge_'+task_id).text('Ongoing');
                            }else{
                                $('#badge_'+task_id).text('Completed');
                            }
                            
                        }
                    }
                });
            });

            // approval function

            $('#changeTaskStatus').on('show.bs.modal', function(event){

                var button = $(event.relatedTarget);
                var t_id = button.data('tid');
                var project_id = button.data('project_id');
                var taskLabel = button.data('tasklabel');
                var modal = $(this);
                modal.find('.modal-body #task_id').val(t_id);
                modal.find('.modal-body #project_id').val(project_id);
                modal.find('.modal-body #task_title').val(taskLabel)

            })


        });
</script>
@endpush