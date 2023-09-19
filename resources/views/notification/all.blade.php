@extends('layouts.user')
@section('title','Ticket')
@push('css')

@endpush

@section('content')

<div class="page-content">

    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Notification</a></li>
            <li class="breadcrumb-item active" aria-current="page">all</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-10 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">All Notifications </h6>

                    <div class="list-group">
                        @foreach($tickets as $ticket)
                        <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                            <div class="d-flex w-100 justify-content-between">
                                <p class="mb-1">{{ $ticket->data['ticket']['description'] }}</p>
                                <small>{{ $ticket->data['ticket']['created_at'] }}</small>
                            </div>
                        </a>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>

    </div>

</div>

          
@endsection

@push('js')


@endpush