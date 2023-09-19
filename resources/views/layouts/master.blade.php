<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') | {{ config('app.name','') }}</title>
	<meta name="userId" content="{{ Auth::check() ? Auth::user()->id : '' }}">
    <!-- core:css -->
    <link rel="stylesheet" href="{{asset('backend/assets/vendors/core/core.css')}}">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- end plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{asset('backend/assets/fonts/feather-font/css/iconfont.css')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/vendors/flag-icon-css/css/flag-icon.min.css')}}">
    <!-- endinject -->
    <!-- Layout styles -->  
    <link rel="stylesheet" href="{{asset('backend/assets/css/demo_5/style.css')}}">
	<link rel="stylesheet" href="{{asset('backend/assets/css/custom.css')}}">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="{{asset('backend/assets/images/favicon.png')}}" />
	<!-- Scripts -->
    <script src="https://momentjs.com/downloads/moment.js" defer></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
	
	<script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
    @if(!auth()->guest())
    <script>
        window.Laravel.user_id = {!!auth()->user()->id!!}
    </script>
    @endif
	
    @stack('css')
</head>
<body>
	<div class="main-wrapper" id="app">

		<!-- partial:../../partials/_navbar.html -->
		@if(Auth::check())
		<div class="horizontal-menu">
			<nav class="navbar top-navbar">
				<div class="container">
					<div class="navbar-content">
						<!-- <a href="{{url('/home')}}" class="navbar-brand">
							Navana<span>Portal</span>
						</a> -->
						<form class="search-form">
							<div class="input-group">
								<div class="input-group-prepend">
									<div class="input-group-text">
										<i data-feather="search"></i>
									</div>
								</div>
								<input type="text" class="form-control" disabled id="navbarForm" placeholder="Search here...">
							</div>
						</form>
						<ul class="navbar-nav">
						@if(Auth::check())
							<ticket v-bind:tickets="tickets"></ticket>
						@endif
							
								@guest
									@if (Route::has('login'))
										<li class="nav-item">
											<a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
										</li>
									@endif
									
									@if (Route::has('register'))
										<!-- <li class="nav-item">
											<a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
											https://via.placeholder.com/30x30
										</li> -->
									@endif
								@else

								<li class="nav-item dropdown">
									<a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<img class="wd-30 ht-30 rounded-circle" src="{{asset('uploads/img/')}}/{{Auth::user()->image}}" alt="profile">
									</a>
									<div class="dropdown-menu p-0" aria-labelledby="profileDropdown">
										<div class="d-flex flex-column align-items-center border-bottom px-5 py-3">
											<!-- <div class="mb-3">
												<img class="wd-80 ht-80 rounded-circle" src="{{asset('uploads/img/')}}/{{Auth::user()->image}}" alt="">
											</div> -->
											<div class="text-center">
												<p class="name font-weight-bold mb-0">{{Auth::user()->name}}</p>
												<p class="email text-muted">{{Auth::user()->email}}</p>
											</div>
										</div>
										<ul class="list-unstyled p-1">
											@if(Auth::user()->role_id ===2)
											<li class="dropdown-item py-2">
												<a href="{{route('head.dashboard')}}" class="text-body ms-0">
													<i class="me-2 icon-md" data-feather="edit"></i>
													<span>Dashboard</span>
												</a>
											</li>
											@elseif(Auth::user()->role_id ===3)
											<li class="dropdown-item py-2">
												<a href="{{route('sub.dashboard')}}" class="text-body ms-0">
													<i class="me-2 icon-md" data-feather="edit"></i>
													<span>Dashboard</span>
												</a>
											</li>
											@else
											<li class="dropdown-item py-2">
												<a href="{{route('user.dashboard')}}" class="text-body ms-0">
													<i class="me-2 icon-md" data-feather="edit"></i>
													<span>Dashboard</span>
												</a>
											</li>
											@endif
											
											<li class="dropdown-item py-2">
												<a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();
																document.getElementById('logout-form').submit();"><i data-feather="log-out"></i>
													{{ __('Logout') }}
												</a>

												<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
													@csrf
												</form>
											</li>
										</ul>
									</div>
								</li>
								
								@endguest

							
						</ul>
						<button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="horizontal-menu-toggle">
							<i data-feather="menu"></i>					
						</button>
					</div>
				</div>
			</nav>
		
		</div>
		@endif
		<!-- partial -->
	
		<div class="page-wrapper">

                @yield('content')
			

			<!-- partial:../../partials/_footer.html -->
			@include('slices.footer')
			<!-- partial -->
	
		</div>
	</div>

    <!-- core:js -->
    <script src="{{asset('backend/assets/vendors/core/core.js')}}"></script>
    <!-- endinject -->
    <!-- plugin js for this page -->
    <!-- end plugin js for this page -->
    <!-- inject:js -->
    <script src="{{asset('backend/assets/vendors/feather-icons/feather.min.js')}}"></script>
    <script src="{{asset('backend/assets/js/template.js')}}"></script>
    <!-- endinject -->
    <!-- custom js for this page -->
    <!-- end custom js for this page -->
    @stack('js')
</body>
</html>