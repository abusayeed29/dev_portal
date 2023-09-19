@extends('layouts.user')
@section('title','Ticket')
@push('css')

@endpush

@section('content')

<div class="page-content">

    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Notification</a></li>
            <li class="breadcrumb-item active" aria-current="page">show</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Notification Details</h6>
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
                                    <td>{{ !empty($ticket->status) ? $ticket->status : ''}}</td>
                                </tr>
                                <tr>
                                    <td>Category</td>
                                    <td>:</td>
                                    <td> {{ !empty($ticket->ticketType->name) ? $ticket->ticketType->name:'Generated from Others' }} </td>
                                </tr>
                                <tr>
                                    <td>Priority</td>
                                    <td>:</td>
                                    <td>{{!empty($ticket->priority) ? $ticket->priority: ''}}</td>
                                </tr>
                                <tr>
                                    <td>Created</td>
                                    <td>:</td>
                                    <td>{{$ticket->created_at}}</td>
                                </tr>
                                <tr>
                                    <td>Last Update</td>
                                    <td>:</td>
                                    <td>{{$ticket->updated_at}}</td>
                                </tr>
                                <tr>
                                    <td>Owner</td>
                                    <td>:</td>
                                    <td>{{$ticket->user->name}}</td>
                                </tr>
                                <tr>
                                    <td>Company</td>
                                    <td>:</td>
                                    <td>{{$ticket->company->slug}}</td>
                                </tr>
                                <tr>
                                    <td>Give Feedback</td>
                                    <td>:</td>
                                    <td>
                                        @if(empty($ticket->feedback))
                                            @if(!empty($ticket->status) && $ticket->status=='completed')
                                                <p id="feedback_success" class="text-success"></p>
                                                <form class="feedback" id="feedback">
                                                    <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="feedback" id="solved" value="slv">
                                                    <label class="form-check-label" for="solved">OK</label>
                                                    </div>
                                                    <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="feedback" id="unsolved" value="uns">
                                                    <label class="form-check-label" for="unsolved">Not Ok</label>
                                                    </div>
                                                    <input type="hidden" name="t_id" id="t_id" value="{{ $ticket->id }}">
                                                </form>
                                            @endif
                                        @else
                                            <p class="text-success">Already you have sent your feedback !</p>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>

                </div>
            </div>
        </div>

    </div>

</div>

          
@endsection

@push('js')


@endpush