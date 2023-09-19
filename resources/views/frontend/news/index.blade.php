@extends('layouts.master')

@section('title','News-event')

@push('css')
    <link rel="stylesheet" href="{{asset('backend/assets/vendors/owl.carousel/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/vendors/owl.carousel/owl.theme.default.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/vendors/animate.css/animate.min.css')}}">
@endpush

@section('content')
    <div class="page-content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title text-center">This page is under construction</h6>

                    </div>
                </div>
            </div>
        </div>

        <!-- <div class="card-columns">
            <div class="card">
                <img src="{{asset('frontend/assets/images/1.jpg')}}" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Card title that wraps to a new line</h5>
                    <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                    <a href="#" class="btn btn-danger mt-3 btn-xs">See More -- </a>
                </div>
            </div>
            <div class="card p-3">
                <blockquote class="blockquote mb-0 card-body">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                    <footer class="blockquote-footer">
                        <small class="text-muted">
                        Someone famous in <cite title="Source Title">Source Title</cite>
                        </small>
                    </footer>
                </blockquote>
            </div>
            <div class="card">
                <img src="{{asset('frontend/assets/images/1.jpg')}}" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Card title</h5>
                    <p class="card-text">This card has supporting text below as a natural lead-in to additional content.</p>
                    <a href="#" class="btn btn-danger mt-3 btn-xs">See More --</a>
                    <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                </div>
            </div>
            <div class="card">
                <img src="{{asset('frontend/assets/images/1.jpg')}}" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Card title</h5>
                    <p class="card-text">This card has supporting text below as a natural lead-in to additional content.</p>
                    <a href="#" class="btn btn-danger mt-3 btn-xs">See More --</a>
                    <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                </div>
            </div>
        </div> -->

    </div>

@endsection

@push('js')
    <!-- plugin js for this page -->
    <script src="{{asset('backend/assets/vendors/owl.carousel/owl.carousel.min.js')}}"></script>
    <script src="{{asset('backend/assets/vendors/jquery-mousewheel/jquery.mousewheel.js')}}"></script>
    <!-- custom js for this page -->
    <script src="{{asset('backend/assets/js/carousel.js')}}"></script>
@endpush    