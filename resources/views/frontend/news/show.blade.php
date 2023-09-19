@extends('layouts.master')

@section('title','Home')

@push('css')
    <link rel="stylesheet" href="{{asset('backend/assets/vendors/owl.carousel/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/vendors/owl.carousel/owl.theme.default.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/vendors/animate.css/animate.min.css')}}">
@endpush

@section('content')
    <div class="page-content">

        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
            <div>
                <h4 class="mb-3 mb-md-0">Welcome to Navana Gateway</h4>
            </div>
        </div>

        <div class="row profile-body">
            <!-- middle wrapper start -->
            <div class="col-md-8 col-xl-8 d-md-block left-wrapper">
                <div class="row">
                    <div class="col-md-12 grid-margin">
                        <div class="card rounded">
                            <div class="card-header">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <img class="img-xs rounded-circle" src="https://via.placeholder.com/37x37" alt="">														
                                        <div class="ml-2">
                                            <p>Admin</p>
                                            <p class="tx-11 text-muted">1 min ago</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="mb-3 tx-14">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus minima delectus nemo unde quae recusandae assumenda.</p>
                                <img class="img-fluid" src="{{asset('frontend/assets/images/1.jpg')}}" alt="">
                            </div>
                            <div class="card-footer">
                                <div class="d-flex post-actions">
                                    <a href="javascript:;" class="d-flex align-items-center text-muted mr-4">
                                        <i class="icon-md" data-feather="heart"></i>
                                        <p class="d-none d-md-block ml-2">Like</p>
                                    </a>
                                    <a href="javascript:;" class="d-flex align-items-center text-muted mr-4">
                                        <i class="icon-md" data-feather="message-square"></i>
                                        <p class="d-none d-md-block ml-2">Comment</p>
                                    </a>
                                    <a href="javascript:;" class="d-flex align-items-center text-muted">
                                        <i class="icon-md" data-feather="share"></i>
                                        <p class="d-none d-md-block ml-2">Share</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- middle wrapper end -->
            <!-- right wrapper start -->
            <div class="d-none d-xl-block col-xl-4 right-wrapper">
                <div class="row">
                    <div class="col-md-12 grid-margin">
                        <div class="card rounded">
                            <div class="card-body">
                                <h6 class="card-title">Latest Groups News</h6>
                                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                                    <div class="d-flex align-items-center hover-pointer">
                                        <img class="img-xs" src="https://via.placeholder.com/37x37" alt="">													
                                        <div class="ml-2">
                                            <p>The beautiful Prologue is a new visionary design for Audi</p>
                                            <p class="tx-11 text-muted">March 19, 2015</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                                    <div class="d-flex align-items-center hover-pointer">
                                        <img class="img-xs" src="https://via.placeholder.com/37x37" alt="">													
                                        <div class="ml-2">
                                            <p>The beautiful Prologue is a new visionary design for Audi</p>
                                            <p class="tx-11 text-muted">March 19, 2015</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                                    <div class="d-flex align-items-center hover-pointer">
                                        <img class="img-xs" src="https://via.placeholder.com/37x37" alt="">													
                                        <div class="ml-2">
                                            <p>The beautiful Prologue is a new visionary design for Audi</p>
                                            <p class="tx-11 text-muted">March 19, 2015</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                                    <div class="d-flex align-items-center hover-pointer">
                                        <img class="img-xs" src="https://via.placeholder.com/37x37" alt="">													
                                        <div class="ml-2">
                                            <p>The beautiful Prologue is a new visionary design for Audi</p>
                                            <p class="tx-11 text-muted">March 19, 2015</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                                    <div class="d-flex align-items-center hover-pointer">
                                        <img class="img-xs" src="https://via.placeholder.com/37x37" alt="">													
                                        <div class="ml-2">
                                            <p>The beautiful Prologue is a new visionary design for Audi</p>
                                            <p class="tx-11 text-muted">March 19, 2015</p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- right wrapper end -->
        </div>


    </div>

@endsection

@push('js')
    <!-- plugin js for this page -->
    <script src="{{asset('backend/assets/vendors/owl.carousel/owl.carousel.min.js')}}"></script>
    <script src="{{asset('backend/assets/vendors/jquery-mousewheel/jquery.mousewheel.js')}}"></script>
    <!-- custom js for this page -->
    <script src="{{asset('backend/assets/js/carousel.js')}}"></script>
@endpush    