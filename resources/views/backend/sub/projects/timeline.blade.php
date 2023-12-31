@extends('layouts.sub')
@section('title','Dashboard')
@push('css')
    <!--  <link rel="stylesheet" href="{{asset('public/frontend/vendor/wowjs/animate.css')}}"> -->
    <link rel="stylesheet" href="{{asset('backend/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css')}}">
@endpush

@section('content')

    <div class="page-content">

        <nav class="page-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">General pages</a></li>
                <li class="breadcrumb-item active" aria-current="page">Timeline</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                    <h6 class="card-title">Timeline</h6>
                    <div id="content">
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
                    </div>
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