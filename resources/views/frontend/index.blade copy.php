@extends('layouts.frontmaster')

@section('title','Home')

@push('css')
    <link rel="stylesheet" href="{{asset('backend/assets/vendors/owl.carousel/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/vendors/owl.carousel/owl.theme.default.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/vendors/animate.css/animate.min.css')}}">
    <style>
        .caption {
            position: absolute;
            z-index: 10;
            bottom: 1%;
            color: #fff;
            right: 5%;
        }
    </style>
@endpush

@section('content')
    <div class="page-content">

        <!-- <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
            <div>
                <h4 class="mb-3 mb-md-0">Welcome</h4>
            </div>
        </div> -->

        <div class="row">
            <!-- <div class="col-lg-7 col-xl-8 grid-margin stretch-card">
                <div class="card text-white">
                    <img src="{{asset('frontend/assets/images/1.jpg')}}" class="card-img" alt="...">
                    <div class="card-img-overlay">
                    <h5 class="card-title">Empowerment and leadership trainings.</h5>
                    <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                    <p class="card-text">Last updated 3 mins ago</p>
                    </div>
                </div>

            </div> -->

            <div class="col-lg-7 col-xl-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body p-0">

                        <div class="owl-carousel owl-theme owl-fadeout">
                            <div class="item">
                                <img src="{{asset('frontend/assets/images/slider/slider_1.jpg')}}" class="img-fluid" alt="item-image">
                                <div class="caption">
                                    <h4></h4>
                                    <p>Bangabandhu Bridge, PC:Afzal Nazim</p>
                                </div>
                            </div>
                            <div class="item">
                                <img src="{{asset('frontend/assets/images/slider/slider_2.jpg')}}" class="img-fluid" alt="item-image">
                                <div class="caption">
                                    <h4></h4>
                                    <p>Sylhet, PC:Afzal Nazim </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 col-xl-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-baseline mb-3">
                            <h6 class="card-title mb-0 card-title-1"> What’s New</h6>
                        </div>
                        <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                            <a href="{{asset('uploads/the-newsletter-june-2021.pdf')}}" target="_blank" class="color-black">
                            <div class="d-flex align-items-center hover-pointer">
                                <img class="img-xs" src="{{asset('uploads/img/newsletter-img.png')}}" alt="">													
                                <div class="ml-2">
                                    <p>The Newsletter – June 2021</p>
                                    <p class="tx-11 text-muted">June 26, 2021</p>
                                </div>
                            </div>
                            </a>
                        </div>
                        <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                            <a href="{{route('policy.index')}}" class="color-black" target="_blank">
                            <div class="d-flex align-items-center hover-pointer">
                                
                                <img class="img-xs" src="{{asset('uploads/img/privacy-policy.jpg')}}" alt="">													
                                <div class="ml-2">
                                    <p>New Policies</p>
                                    <p class="tx-11 text-muted">May 30, 2021</p>
                                </div>
                            
                            </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- row -->

        <div class="row profile-body">

            <!-- middle wrapper start -->
            <div class="col-md-8 col-xl-8 d-md-block left-wrapper">
                <div class="row">
                    <div class="col-md-12 grid-margin">
                        <div class="card rounded">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-baseline mb-3">
                                    <h6 class="card-title mb-0 card-title-1"> Announcement </h6>
                                </div>
                                <div class="d-flex justify-content-between mb-2 pb-2">
                                    <a href="https://www.thedailystar.net/business/news/navana-appoints-wahed-azizur-rahman-its-ceo-2072209?fbclid=IwAR0nL2a_1kJFcNxekkQtg5m-anBCqCS8nmPp-tGUNyFC_XkPddQ9Wwo6EuE" target="_blank" class="color-black">
                                    <div class="d-flex align-items-center hover-pointer">
                                        <img class="img-xs" src="{{asset('uploads/img/newsletter-img.png')}}" alt="">													
                                        <div class="ml-2">
                                            <p>Navana appoints Wahed Azizur Rahman as its CEO</p>
                                            <p class="tx-11 text-muted">June 26, 2021</p>
                                        </div>
                                    </div>
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
                                <h6 class="card-title card-title-1"><a href="{{url('news')}}" class="color-black">Navigation</a></h6>
                                <div class="d-flex mb-2 pb-2">
                                    <ul class="nav flex-column nav-menu">
                                        <li class="nav-item"> <a href="{{url('news')}}" class="nav-link"> <span> <i data-feather="arrow-right" width="16" height="16"></i> </span> Latest Group News</a></li>
                                        <li class="nav-item"><a href="{{url('policies')}}" class="nav-link"> <span> <i data-feather="arrow-right" width="16" height="16"></i> </span> Group Policies</a></li>
                                    </ul>
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
    <script>

        $('.owl-carousel').owlCarousel({
            items:1,
            loop:true,
            margin:5,
            autoplay:true,
            dots: false,
            autoplayTimeout:2000,
            autoplayHoverPause:true,
            animateOut: 'fadeOut',
   
        })

    </script>
@endpush    