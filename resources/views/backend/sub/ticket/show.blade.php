@extends('layouts.sub')
@section('title','Ticket show')
@push('css')
<style>
    .chat-wrapper .chat-content .chat-body{
        max-height: 300px;
        overflow-y: scroll !important;
    }
    .table td, .table th {
        white-space: break-spaces !important;
    }
</style>
@endpush

@section('content')

<div class="page-content">

    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Ticket</a></li>
            <li class="breadcrumb-item active" aria-current="page">Show</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Ticket Details</h6>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 table-striped">
                            <tbody>
                                <tr>
                                    <td>Descriptions:</td>
                                    <td>:</td>
                                    <td>{{!empty($ticket->description) ? $ticket->description : ''}}</td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td>:</td>
                                    <td><span class="badge" style=" color:#fff; background-color: {{$ticket->tktStatus->color}};">{{!empty($ticket->tkt_status_id) ? $ticket->tktStatus->name : ''}} </span></td>
                                </tr>
                                <tr>
                                    <td>Category</td>
                                    <td>:</td>
                                    <td>{{!empty($ticket->ticketType->name) ? $ticket->ticketType->name: 'Self'}}</td>
                                </tr>
                                
                                <tr>
                                    <td>Priority</td>
                                    <td>:</td>
                                    <td>{{!empty($ticket->priority) ? $ticket->priority : ''}}</td>
                                </tr>
                                <tr>
                                    <td>Created</td>
                                    <td>:</td>
                                    <td>{{!empty($ticket->created_at) ? $ticket->created_at : ''}}</td>
                                </tr>
                                <tr>
                                    <td>Assign time</td>
                                    <td>:</td>
                                    <td>{{!empty($ticket->assign_time) ? $ticket->assign_time : ''}}</td>
                                </tr>
                                @foreach($ticket->tktSupportActivity as $single)
                                <tr>
                                    <td style="text-transform: capitalize;">{{$single->status_name}}</td>
                                    <td>:</td>
                                    <td>{{$single->added_on}}</td>
                                @endforeach
                                </tr>
                                <tr>
                                    <td>Last Update</td>
                                    <td>:</td>
                                    <td>{{!empty($ticket->updated_at) ? $ticket->updated_at : ''}}</td>
                                </tr>
                                <tr>
                                    <td>Ticket Owner</td>
                                    <td>:</td>
                                    <td><strong>Name: </strong> {{ $ticket->user->name}} <br>
                                        <strong>Employee Id: </strong> {{ $ticket->user->emp_id }}<br>
                                        <strong>Location: </strong> {{!empty($ticket->lookUp) ? $ticket->lookUp->data: ''}} <br>
                                        <strong>Designation: </strong>{{ !empty($ticket->user->designation->name) ? $ticket->user->designation->name :''}} <br>
                                        <strong>Department: </strong> {{ !empty($ticket->user->department->name) ? $ticket->user->department->name :''}} <br>
                                        <strong>Mobile: </strong> {{ $ticket->user->phone }}<br>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Assigned {{$ticket->tkt_dprtmnt == 1 ? 'Engineer' : 'Officer'}}</td>
                                    <td>:</td>
                                    <td><strong>Name:</strong> {{ !empty($ticket->userAssign->name)? $ticket->userAssign->name:'Not yet'}} <br>
                                        <strong>Mobile:</strong> {{ !empty($ticket->userAssign->phone)? $ticket->userAssign->phone:'Not yet'}} <br>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Company</td>
                                    <td>:</td>
                                    <td>{{!empty($ticket->company_id) ? $ticket->company->name : ''}}</td>
                                </tr>
                                <!-- <tr>
                                    <td>Give Feedback</td>
                                    <td>:</td>
                                    <td> {{!empty($ticket->feedback) ? $ticket->feedback : 'Not Given'}}</td>
                                </tr> -->
                            </tbody>
                        </table>

                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Attached</h6>
                    @foreach($ticket->files as $file)
                        @if(strtolower($file->type)==='pdf')
                            <a target="_blank" href="{{asset($file->path.'/'.$file->name)}}">{{$file->name}}</a>
                        @else
                            <a target="_blank" href="{{asset($file->path.'/'.$file->name)}}"><img src="{{asset($file->path.'/'.$file->name)}}" class="img-fluid"></a>
                        @endif
                        
                    @endforeach

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Ticket Comments</h6>

                    <div class="chat-wrapper" style="height: auto;">
                        <div class="chat-content">
                            <div class="chat-body ps ps--active-y"> 
                                <ul class="messages" id="comments_box">
                                   
                                    @foreach($comments as $comment)
                                    <li class="message-item {{ Auth::id() == $comment->user_id ? 'me':'friend'}}">
                                        <img src="{{asset('/uploads/img/')}}/{{$comment->user->image}}" class="img-xs rounded-circle" title="{{$comment->user->name}}" alt="{{$comment->user->name}}">
                                        <div class="content">
                                            <div class="message">
                                                <div class="bubble">
                                                    <p>{{$comment->comment}}</p>
                                                </div>
                                                <span>{{$comment->created_at}}</span>
                                            </div>
                                        </div>
                                    </li>
                                    @endforeach

                                </ul>
                            </div>
                        </div>

                        <div class="chat-footer d-flex">
                            <form class="search-form flex-grow-1 mr-2">
                                <div class="input-group">
                                    <input type="text" class="form-control rounded-pill" id="commentForm" placeholder="Type a message">
                                    <input type="hidden" name="ticket_id" value="{{$ticket->id}}" id="ticket_id">
                                </div>
                            </form>
                            <div>
                                <button type="button" id="commentSubmit" class="btn btn-primary btn-icon rounded-circle">
                                <i data-feather="send"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    

</div>
    
@endsection

@push('js')
    <!-- custom js for this page -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script> -->
    <!-- <script src="{{ asset('js/app.js') }}" defer></script> -->

    <script>

        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });

        $(document).ready(function(){ 

            $('input[name="feedback"]').change(function(e){  
                e.preventDefault();
                var feedback = $(this).val();
                var ticket_id = $("input#t_id").val();
                $.ajax({  
                    url:"{{route('sub.ticket.feedback')}}", 
                    method:"POST", 
                    data:{feedback:feedback, ticket_id:ticket_id},  
                    success:function(data){  
                    console.log(data.success);
                    $('#feedback_success').html(data.success); 
                    $('#feedback').css('display','none');  
                    }  
                });  
            });  
            // function for upload file

            /*$('#ticket_picture').change(function(){
                var file = this.files[0];
                var error = '';
                var form_data = new FormData();
                form_data.append("ticket_picture", file);
                form_data.append("t_id", $("input#ticket_id").val());
                console.log(form_data);
                if(error == ''){
                    jQuery.ajax({
                        url:"#",
                        method:"POST",
                        data:form_data,
                        contentType:false,
                        cache:false,
                        processData:false,
                        beforeSend:function(){
                            $('#ticket_picture').html("<label class='text-success'>Uploading...</label>");
                        },
                        success:function(data){
                            var editurl = "{{ route('sub.ticket.show','') }}/"+data.ticket_file.ticket_id;
                            window.location.href = editurl;
                        }
                    })

                } else{
                    alert(error);
                }

            }); */

            /*end file upload*/
            
            $("#commentSubmit").click(function(e){
                e.preventDefault();
                var ticket_id  =   $("input#ticket_id").val();
                var comment  =   $("input#commentForm").val();
                var image = "{{asset('/uploads/img/')}}" +'/'+ "{{Auth::user()->image}}";
                $.ajax({
                    type:'POST',
                    url:"{{route('sub.ticket.comments.store')}}",
                    data:{ticket_id:ticket_id, comment:comment},
                    success:function(data){
                        if(data.errors){
                            $('#toastrerrorShadow').click();
                        }else{
                            var name ="{{ Auth::user()->name }}";
                            $('#comments_box').append('<li class="message-item me">'+
                                        '<img src="'+image+'" class="img-xs rounded-circle">'+
                                        '<div class="content">'+
                                            '<div class="message">'+
                                                '<div class="bubble">'+
                                                    '<p>'+data.comment.comment+'</p>'+
                                                '</div>'+
                                                '<span>'+data.comment.created_at+'</span>'+
                                            '</div>'+
                                        '</div>'+
                                    '</li>');
                            //$('#toastrSuccessShadow').click();
                            $("input#commentForm").val('');
                        }
                        
                    }
                });
            }); 
            

        });  


        /*const app = new Vue({
            el: '#apps',
            data: {
                comments:{},
                commentBox: '',
            }, 
            methods:{
                getComments(){
                    axios.get(`/ticket/1/comments`)
                    .then((response) => {
                        this.comments = response.data;
                        console.log(comments);
                    })
                    .catch(function (error){
                        console.log(error);
                    })
                },

            }
            
        }) */
        
    </script>
@endpush