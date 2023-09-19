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
    <div class="container-fluid mt-3">

        <div class="row">
            <div class="col-lg-7 col-xl-8 grid-margin">

                <div class="card" style="background-color: #151515;">
                    <div class="card-body p-0">
                        <!-- start row -->
                        <div class="row">
                            <!-- start col -->
                            <div class="col-lg-8">
                                <div class="mt-4 ml-4 mb-4">
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
                            <!-- end col -->
                            <div class="col-lg-4">

                                <ul class="list-unstyled">
                                    <li class="media d-block d-sm-flex bg-white mt-4 mr-4">
                                        <img src="{{asset('frontend/assets/images/slider/slider_31.jpg')}}" class="wd-100p wd-sm-150 mb-3 mb-sm-0 mr-3" alt="...">
                                        <div class="media-body">
                                            <p class="tx-11 text-muted pt-3">June 26, 2021</p>
                                            <h5 class="mt-0 mb-1">List-based media object</h5>
                                        </div>
                                    </li>
                                    <li class="media d-block d-sm-flex my-4 bg-white mr-4">
                                        <img src="{{asset('frontend/assets/images/slider/slider_31.jpg')}}" class="wd-100p wd-sm-150 mb-3 mb-sm-0 mr-3" alt="...">
                                        <div class="media-body pt-3">
                                            <p class="tx-11 text-muted">June 26, 2021</p>
                                            <h5 class="mt-0 mb-1">List-based media object</h5>
                                        </div>
                                    </li>
                                    <li class="media d-block d-sm-flex my-4 bg-white mr-4">
                                        <img src="{{asset('frontend/assets/images/slider/slider_31.jpg')}}" class="wd-100p wd-sm-150 mb-3 mb-sm-0 mr-3" alt="...">
                                        <div class="media-body">
                                            <p class="tx-11 text-muted pt-3">June 26, 2021</p>
                                            <h5 class="mt-0 mb-1">List-based media object</h5>
                                        </div>
                                    </li>   
                                </ul>
                                <div class="text-center">
                                    <button type="button" class="btn btn-outline-light text-center">View More News</button>
                                </div>
                            </div>

                        </div> <!-- end row -->
                    </div>
                </div>

                <div class="card rounded">
                    <div class="card-body">
                        <div class="row">

                            <div class="col-lg-6  border-lg-right">
                                <div class="d-flex justify-content-between align-items-baseline mb-3">
                                    <h6 class="card-title mb-0 card-title-1"> Announcement </h6>
                                </div>

                                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
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

                                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
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

                            <div class="col-lg-6">
                                <div class="d-flex justify-content-between align-items-baseline mb-3">
                                    <h6 class="card-title mb-0 card-title-1"> Intranet Training Materials </h6>
                                </div>
                                <div class="training wrapper">
                                    <img src="{{asset('frontend/assets/images/training.png  ')}}" class="img-fluid" alt="image">
                                </div>


                                <!-- start chat -->
                                <div class="d-flex justify-content-between align-items-baseline mb-3 mt-5">
                                    <h6 class="card-title mb-0 card-title-1"> Yammer </h6>
                                </div>
                                <div class="chat-wrapper">
                                    <div class="col-lg-12 chat-content">
                                        <div class="chat-header border-bottom pb-2">
                                            <div class="d-flex justify-content-between">
                                                <div class="d-flex align-items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-corner-up-left icon-lg mr-2 ml-n2 text-muted d-lg-none" id="backToChatList"><polyline points="9 14 4 9 9 4"></polyline><path d="M20 20v-7a4 4 0 0 0-4-4H4"></path></svg>
                                                    <figure class="mb-0 mr-2">
                                                        <img src="{{asset('frontend/assets/images/faces/face2.jpg')}}" class="img-sm rounded-circle" alt="image">
                                                        <div class="status online"></div>
                                                        <div class="status online"></div>
                                                    </figure>
                                                    <div>
                                                        <p>Mariana Zenha</p>
                                                        <p class="text-muted tx-13">Front-end Developer</p>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center mr-n1">
                                                    <a href="#">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-video icon-lg text-muted mr-3" data-toggle="tooltip" title="" data-original-title="Start video call"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>
                                                    </a>
                                                    <a href="#">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone-call icon-lg text-muted mr-0 mr-sm-3" data-toggle="tooltip" title="" data-original-title="Start voice call"><path d="M15.05 5A5 5 0 0 1 19 8.95M15.05 1A9 9 0 0 1 23 8.94m-1 7.98v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                                    </a>
                                                    <a href="#" class="d-none d-sm-block">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus icon-lg text-muted" data-toggle="tooltip" title="" data-original-title="Add to contacts"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="chat-body ps ps--active-y">
                                            <ul class="messages">
                                                <li class="message-item friend">
                                                <img src="{{asset('frontend/assets/images/faces/face2.jpg')}}" class="img-xs rounded-circle" alt="avatar">
                                                <div class="content">
                                                    <div class="message">
                                                    <div class="bubble">
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                                                    </div>
                                                    <span>8:12 PM</span>
                                                    </div>
                                                </div>
                                                </li>
                                                <li class="message-item me">
                                                <img src="{{asset('frontend/assets/images/faces/face1.jpg')}}" class="img-xs rounded-circle" alt="avatar">
                                                <div class="content">
                                                    <div class="message">
                                                    <div class="bubble">
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry printing and typesetting industry.</p>
                                                    </div>
                                                    </div>
                                                    <div class="message">
                                                    <div class="bubble">
                                                        <p>Lorem Ipsum.</p>
                                                    </div>
                                                    <span>8:13 PM</span>
                                                    </div>
                                                </div>
                                                </li>

                                            </ul>
                                            <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
                                                <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;">
                                                </div>
                                            </div>
                                            <div class="ps__rail-y" style="top: 0px; height: 240px; right: 0px;">
                                                <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 82px;">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="chat-footer d-flex">
                                            <div>
                                                <button type="button" class="btn border btn-icon rounded-circle mr-2" data-toggle="tooltip" title="" data-original-title="Emoji">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-smile text-muted"><circle cx="12" cy="12" r="10"></circle><path d="M8 14s1.5 2 4 2 4-2 4-2"></path><line x1="9" y1="9" x2="9.01" y2="9"></line><line x1="15" y1="9" x2="15.01" y2="9"></line></svg>
                                                </button>
                                            </div>
                                            <div class="d-none d-md-block">
                                                <button type="button" class="btn border btn-icon rounded-circle mr-2" data-toggle="tooltip" title="" data-original-title="Attatch files">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-paperclip text-muted"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path></svg>
                                                </button>
                                            </div>
                                            <div class="d-none d-md-block">
                                                <button type="button" class="btn border btn-icon rounded-circle mr-2" data-toggle="tooltip" title="" data-original-title="Record you voice">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mic text-muted"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path><path d="M19 10v2a7 7 0 0 1-14 0v-2"></path><line x1="12" y1="19" x2="12" y2="23"></line><line x1="8" y1="23" x2="16" y2="23"></line></svg>
                                                </button>
                                            </div>
                                            <form class="search-form flex-grow mr-2">
                                                <div class="input-group">
                                                <input type="text" class="form-control rounded-pill" id="chatForm" placeholder="Type a message">
                                                </div>
                                            </form>
                                            <div>
                                                <button type="button" class="btn btn-primary btn-icon rounded-circle">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-send"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end chat -->

                            </div>
                        </div>


                    </div>
                </div>

            </div>
            <!-- start sidebar -->
            <div class="col-lg-5 col-xl-4 grid-margin">

                <div class="card mb-1">
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

                <div class="card rounded mb-1">
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

                <div class="card mb-1">
                    <div class="card-body">
                        <h6 class="card-title card-title-1">Frequently Asked Questions</h6>
                        <div id="accordion" class="accordion" role="tablist">
                        <div class="card">
                            <div class="card-header" role="tab" id="headingOne">
                                <h6 class="mb-0">
                                    <a data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Leave & Absence
                                    </a>
                                </h6>
                            </div>
                            <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">
                                Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                            </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" role="tab" id="headingTwo">
                            <h6 class="mb-0">
                                <a class="collapsed" data-toggle="collapse" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Training & Development
                                </a>
                            </h6>
                            </div>
                            <div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingTwo" data-parent="#accordion">
                            <div class="card-body">
                                Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                            </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" role="tab" id="headingThree">
                            <h6 class="mb-0">
                                <a class="collapsed" data-toggle="collapse" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Will we ever discover aliens?
                                </a>
                            </h6>
                            </div>
                            <div id="collapseThree" class="collapse" role="tabpanel" aria-labelledby="headingThree" data-parent="#accordion">
                            <div class="card-body">
                                Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                            </div>
                            </div>
                        </div>

                        </div>
                    </div>
                </div>

            </div>
            <!-- end sidebar -->
        </div> <!-- row -->

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