@extends('layouts.master')

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
<div class="row">
            <div class="col-lg-7 col-xl-8 grid-margin">

                <div class="card" style="background-color: #151515;">
                    <div class="card-body p-0">
                        <!-- start row -->
                        <div class="row">
                            <!-- start col -->
                            <div class="col-lg-8">
                                <div class="mt-2 ml-2 pb-3">
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
                                    <li class="media d-block d-sm-flex bg-white mt-2 mr-2">
                                        <img src="{{asset('frontend/assets/images/slider/slider_31.jpg')}}" class="img-fluid" alt="...">
                                        <!-- <div class="media-body">
                                            <p class="tx-11 text-muted pt-1">June 26, 2021</p>
                                            <h5 class="mt-0 mb-1">List-based media object</h5>
                                        </div> -->
                                    </li>
                                    <li class="media d-block d-sm-flex my-2 bg-white mr-2">
                                        <img src="{{asset('frontend/assets/images/slider/slider_31.jpg')}}" class="img-fluid" alt="...">
                                        <!-- <div class="media-body pt-3">
                                            <p class="tx-11 text-muted">June 26, 2021</p>
                                            <h5 class="mt-0 mb-1">List-based media object</h5>
                                        </div> -->
                                    </li>  
                                </ul>
                                <div class="text-center">
                                    <button type="button" class="btn btn-outline-light text-center mb-1">View More News</button>
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
                                        <img class="img-xs" src="{{asset('frontend/assets/images/newsletter-img.png')}}" alt="">													
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
                            </div>
                        </div>


                    </div>
                </div>

                @if(!empty($data))
                <div class="card rounded">
                    <div class="card-body">
                        <div class="row">

                            <div class="col-lg-12">
                                <div class="d-flex justify-content-between align-items-baseline mb-3">
                                    <h6 class="card-title mb-0 card-title-1"> New Joiner </h6>
                                </div>


                                @forelse($data as $key => $emp)
                                <div class="d-flex flex-row justify-content-between mb-2 pb-2 border-bottom">
                                 
                                    <div class="d-flex align-items-center hover-pointer">
                                        <img class="img-xs rounded-circle" src="{{asset('uploads/img/default.jpg')}}" alt="">													
                                        <div class="ml-2">
                                            <p>{{$emp->EMPLOYEE_NAME}} ({{$emp->EMPLOYEE_ID}})</p>
                                            <p class="tx-11 text-muted">Designation: {{$emp->DESIGNATION_NAME}}</p>
                                            <p class="tx-11 text-muted">Department: {{$emp->DEPARTMENT_NAME}}</p>
                                            <p class="tx-11 text-muted">Company: {{$emp->COMPANY_NAME}}</p>
                                            <p class="tx-11 text-muted">Mobile: {{$emp->MOBILE}}</p>
                                            <p class="tx-11 text-muted">Place of Work: {{$emp->PROJECT_NAME}}</p>
                                        </div>
                                    </div>
                                    <div class="p-2">
                                        <p class="tx-11 text-muted">Joining Date <br>{{$emp->JOINING_DATE}}</p>
                                    </div>
                                  
                                </div>
                                @empty
                                    <p>No users</p>
                                @endforelse
                            


                            </div>

                        </div>


                    </div>
                </div>
                @endif

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
                                <img class="img-xs" src="{{asset('frontend/assets/images/newsletter-img.png')}}" alt="">													
                                <div class="ml-2">
                                    <p>The Newsletter – June 2021</p>
                                    <p class="tx-11 text-muted">June 26, 2021</p>
                                </div>
                            </div>
                            </a>
                        </div>
                        <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                            <a href="#" class="color-black" target="_blank">
                            <div class="d-flex align-items-center hover-pointer">
                                
                                <img class="img-xs" src="{{asset('frontend/assets/images/privacy-policy.jpg')}}" alt="">													
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
                        <h6 class="card-title card-title-1">Navigation</h6>
                        <div class="d-flex mb-2 pb-2">
                            <ul class="nav flex-column nav-menu">
                                <li class="nav-item"> <a href="#" class="nav-link"> <span> <i data-feather="arrow-right" width="16" height="16"></i> </span> Latest Group News</a></li>
                                <li class="nav-item"><a href="#" class="nav-link"> <span> <i data-feather="arrow-right" width="16" height="16"></i> </span> Group Policies</a></li>
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
                            <div id="collapseOne" class="collapse" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">
                                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Dicta eaque eveniet facere vitae reprehenderit aliquam blanditiis perferendis illo repellat itaque odit obcaecati quas alias voluptatem error, esse pariatur. Vitae, ipsum?
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
                                Lorem ipsum dolor sit amet consectetur, adipisicing elit. Aliquid, distinctio reiciendis tempora exercitationem deleniti nihil ipsum reprehenderit. Earum esse minima excepturi aliquid nulla voluptate blanditiis ut molestiae! Omnis, illum exercitationem!
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
                                Lorem ipsum dolor sit amet consectetur, adipisicing elit. Illum dolorem harum accusamus reprehenderit esse porro recusandae facilis, asperiores ratione ut ab tempore fugiat, veniam ipsum ea quos quas perferendis officia?
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

@endpush