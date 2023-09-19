@extends('layouts.frontmaster')

@section('title','Picnic 2020')

@push('css')
    <link rel="stylesheet" href="{{asset('backend/assets/vendors/animate.css/animate.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/vendors/magnific-popup/magnific-popup.css')}}">
  <style>
        .container2{
            display:grid;
            grid-template-columns: repeat(6,1fr);
            grid-auto-rows:100px 300px;
            grid-gap:10px;
            grid-auto-flow: dense;
        }
        .gallery-item{
            width:100%;
            height:100%;
            position:relative;
        }

        .gallery-item .image{
            width:100%;
            height:100%;
            overflow:hidden;
        }

        .gallery-item .image img{
            width:100%;
            height:100%;
            object-fit: cover;
            object-position:50% 50%;
            cursor:pointer;
            transition:.5s ease-in-out;
        }
        .gallery-item:hover .image img{
            transform:scale(1.5);
        }

        .gallery-item .text{
            opacity:0;
            position:absolute;
            top:50%;
            left:50%;
            transform:translate(-50%,-50%);
            color:#fff;
            font-size:25px;
            pointer-events:none;
            z-index:4;
            transition: .3s ease-in-out;
            -webkit-backdrop-filter: blur(5px) saturate(1.8);
            backdrop-filter: blur(5px) saturate(1.8);
        }

        .gallery-item:hover .text{
            opacity:1;
            animation: move-down .3s linear;
            padding:1em;
            width:100%;
        }

        .w-1{
            grid-column: span 1;
        }
        .w-2{
            grid-column: span 2;
        }
        .w-3{
            grid-column: span 3;
        }
        .w-4{
            grid-column: span 4;
        }
        .w-5{
            grid-column: span 5;
        }
        .w-6{
            grid-column: span 6;
        }

        .h-1{
            grid-row: span 1;
        }
        .h-2{
            grid-row: span 2;
        }
        .h-3{
            grid-row: span 3;
        }
        .h-4{
            grid-row: span 4;
        }
        .h-5{
            grid-row: span 5;
        }
        .h-6{
            grid-row: span 6;
        }

        @media screen and (max-width:500px){
            .container2{
                grid-template-columns: repeat(1,1fr);
            }
            .w-1,.w-2,.w-3,.w-4,.w-5,.w-6{
                grid-column:span 1;
            }
        }

        @keyframes move-down{

            0%{
                top:10%;
            }
            50%{
                top:35%;
            }
            100%{
                top:50%;
            }
        }
    </style>
@endpush

@section('content')

    <div class="horizontal-menu">
        <nav class="navbar top-navbar" style="background: #000;">
            <div class="container">
                <div class="navbar-content">
                    <a href="{{url('/')}}" class="navbar-brand" style="color: #fff;">
                        Navana Annual Picnic <span> 2020 </span>
                    </a>
                </div>
            </div>
        </nav>
    </div>
    <!-- partial -->

    <div class="page-wrapper">
            
            <div class="container-fluid" style="background-color: #000;">
                <div class="row profile-body">
                    <div class="col-md-12 grid-margin">
                        <div class="container2 zoom-gallery">

                            @for ($i = 1; $i < 100; $i++)
                            <div class="gallery-container <?php if($i%2==0){echo 'w-2 h-3';}else{ echo 'w-1 h-2';}?>">
                                <div class="gallery-item">
                                    <div class="image">
                                        <a href="{{url('/')}}/archive/picnic/2020/{{$i}}.jpg">
                                            <img src="{{url('/')}}/archive/picnic/2020/{{$i}}.jpg" alt="nature">
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endfor
                            
                        </div>
                    </div>
                </div>
            </div>
        <!-- partial:../../partials/_footer.html -->
        <footer class="footer d-flex flex-column flex-md-row align-items-center justify-content-between">
            <p class="text-muted text-center text-md-left">Copyright © 2022 <a href="#" target="_blank">NREL</a>. All rights reserved</p>
            <p class="text-muted text-center text-md-left mb-0 d-none d-md-block">Design & Developed By NREL MIS</p>
        </footer>
        <!-- partial -->

    </div>

@endsection

@push('js')

    <script src="{{asset('backend/assets/vendors/magnific-popup/jquery.magnific-popup.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            document.addEventListener('contextmenu', event => event.preventDefault());
            $('.zoom-gallery .gallery-item').magnificPopup({
                delegate: 'a',
                type: 'image',
                closeOnContentClick: false,
                closeBtnInside: false,
                mainClass: 'mfp-with-zoom mfp-img-mobile',
                gallery: {
                    enabled: true
                },
                zoom: {
                    enabled: true,
                    duration: 300, // don't foget to change the duration also in CSS
                    opener: function(element) {
                        return element.find('img');
                    }
                }
                
            });
        });
    </script>
@endpush

