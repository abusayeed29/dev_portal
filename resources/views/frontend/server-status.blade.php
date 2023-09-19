@extends('layouts.frontmaster')
@section('title','Server Status')
@push('css')
  <style>
      .online{
          background-color: #8bc34a !important;
          color: #fff !important;
      }
      .offline{
          background-color:#ff5722 !important;
          color: #fff !important;
      }
      .card-title{
          color: #fff !important;
          font-size: 20px !important;
      }
    </style>
@endpush

@section('content')
    <div class="horizontal-menu">
        <nav class="navbar top-navbar">
            <div class="container">
                <div class="navbar-content justify-content-center">
                    <a href="{{url('/server')}}" class="navbar-brand" style="color: #000;">
                        LIVE SERVER  <span> STATUS </span>
                    </a>
                    <div class="d-flex my-auto">
                    <div class="spinner-grow text-primary mr-1" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <div class="spinner-grow text-secondary mr-1" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <div class="spinner-grow text-info mr-1" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <div class="spinner-grow text-success mr-1" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
    <!-- partial -->

    <div class="page-wrapper">
        <div class="container-fluid" >
            <div class="row profile-body mt-1 mb-5 pb-5" id="result">
            </div>
        </div>
        <footer class="footer d-flex flex-column flex-md-row align-items-center justify-content-between">
            <p class="text-muted text-center text-md-left">Copyright © 2021 <a href="#" target="_blank">NREL</a>. All rights reserved</p>
            <p class="text-muted text-center text-md-left mb-0 d-none d-md-block">Design & Developed By NREL MIS</p>
        </footer>
        <!-- partial -->

    </div>



@endsection

@push('js')
    <script>
        function getServerStatus(){
            const result =  document.querySelector('#result');
            fetch('http://localhost/nrel_soft/public/getserver')
                .then((res) => res.json())
                .then((data) => {
                    console.log(data.networks);
                    result.innerHTML = data.networks;
                });
        }
        getServerStatus();
        setInterval(function(){
            getServerStatus();
        },20000);
    </script>
@endpush

