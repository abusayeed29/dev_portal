<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>@yield('title') | {{ config('app.name','') }}</title>
	<meta name="userId" content="{{ Auth::check() ? Auth::user()->id : '' }}">
	<!-- core:css -->
	<!-- Fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
	<!-- End fonts -->

	<link rel="stylesheet" href="{{asset('backend/assets/vendors/core/core.css')}}">
	<!-- endinject -->
	<!-- plugin css for this page -->
	<link rel="stylesheet" href="{{asset('backend/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
	<!-- end plugin css for this page -->
	@stack('css')
	<!-- End plugin css for this page -->
	<!-- inject:css -->
	<link rel="stylesheet" href="{{asset('backend/assets/fonts/feather-font/css/iconfont.css')}}">
	<link rel="stylesheet" href="{{asset('backend/assets/vendors/flag-icon-css/css/flag-icon.min.css')}}">
	<!-- endinject -->
	<!-- Layout styles -->
	<link rel="stylesheet" href="{{asset('backend/assets/css/demo_1/style.css')}}">
	<link rel="stylesheet" href="{{asset('backend/assets/css/custom.css')}}">
	<!-- End layout styles -->
	<link rel="shortcut icon" href="{{asset('backend/assets/images/favicon.png')}}" />

	<script src="https://momentjs.com/downloads/moment.js" defer></script>
	
	<script>
		window.Laravel = {!! json_encode(['csrfToken' => csrf_token()]) !!};
	</script>
	@if(!auth()->guest())
	<script>
		window.Laravel.user_id = {!! auth()->user()->id !!}
	</script>
	@endif	
</head>

<body>
	<div class="main-wrapper" id="app">

		<!-- partial:partials/_sidebar.html -->
		<nav class="sidebar">
			<div class="sidebar-header">
				<a href="#" class="sidebar-brand">
					IT<span>HelpDesk</span>
				</a>
				<div class="sidebar-toggler not-active">
					<span></span>
					<span></span>
					<span></span>
				</div>
			</div>
			<div class="sidebar-body">
				<ul class="nav">
					<li class="nav-item nav-category">Main</li>
					<li class="nav-item">
						<a href="{{route('admin.dashboard')}}" class="nav-link">
							<i class="link-icon" data-feather="box"></i>
							<span class="link-title">Dashboard</span>
						</a>
					</li>
					<li class="nav-item nav-category">User Management</li>
					<li class="nav-item">
						<a href="{{route('admin.user.index')}}" class="nav-link">
						<i class="link-icon" data-feather="users"></i>
							<span class="link-title">Users</span>
						</a>
					</li>

					<li class="nav-item">
						<a href="{{route('admin.user.all.employees')}}" class="nav-link">
						<i class="link-icon" data-feather="users"></i>
							<span class="link-title">Employees</span>
						</a>
					</li>

					<li class="nav-item nav-category">Vehicle Management (VMS)</li>
					<li class="nav-item">
						<a href="{{route('admin.vms.settings.index')}}" class="nav-link">
							<i class="link-icon" data-feather="activity"></i>
							<span class="link-title">Settings</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="{{route('admin.vms.team.index')}}" class="nav-link">
							<i class="link-icon" data-feather="activity"></i>
							<span class="link-title">Team Settings</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="{{route('admin.vms.vehicle.manage')}}" class="nav-link">
							<i class="link-icon" data-feather="activity"></i>
							<span class="link-title">Vehicles</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="{{route('admin.vms.driver.manage')}}" class="nav-link">
							<i class="link-icon" data-feather="activity"></i>
							<span class="link-title">Drivers</span>
						</a>
					</li>
					
					<li class="nav-item">
						<a href="{{route('admin.vms.manage')}}" class="nav-link">
							<i class="link-icon" data-feather="activity"></i>
							<span class="link-title">Requisition</span>
						</a>
					</li>

					<li class="nav-item nav-category">Assets Management</li>
					<li class="nav-item">
						<a href="#" class="nav-link">
							<i class="link-icon" data-feather="activity"></i>
							<span class="link-title">Assets</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#" class="nav-link">
							<i class="link-icon" data-feather="activity"></i>
							<span class="link-title">Components</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#" class="nav-link">
							<i class="link-icon" data-feather="activity"></i>
							<span class="link-title">Maintenances</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#" class="nav-link">
							<i class="link-icon" data-feather="activity"></i>
							<span class="link-title">Asset Types</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#" class="nav-link">
							<i class="link-icon" data-feather="activity"></i>
							<span class="link-title">Brands</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#" class="nav-link">
							<i class="link-icon" data-feather="activity"></i>
							<span class="link-title">Suppliers</span>
						</a>
					</li>
					
					<li class="nav-item">
						<a href="{{route('admin.tasks.index')}}" class="nav-link">
							<i class="link-icon" data-feather="message-square"></i>
							<span class="link-title">Tasks</span>
						</a>
					</li>
					
				</ul>
			</div>
		</nav>

		<!-- partial -->

		<div class="page-wrapper">

			<!-- partial:partials/_navbar.html -->
			<nav class="navbar">
				<a href="#" class="sidebar-toggler">
					<i data-feather="menu"></i>
				</a>
				<div class="navbar-content">
					<form class="search-form">
						<div class="input-group">
							<div class="input-group-text">
								<i data-feather="search"></i>
							</div>
							<input type="text" class="form-control" id="navbarForm" placeholder="Search here...">
						</div>
					</form>
					<ul class="navbar-nav">
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="appsDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i data-feather="grid"></i>
							</a>
							<div class="dropdown-menu p-0" aria-labelledby="appsDropdown">
								<div class="px-3 py-2 d-flex align-items-center justify-content-between border-bottom">
									<p class="mb-0 fw-bold">Web Apps</p>
									<a href="javascript:;" class="text-muted">Edit</a>
								</div>
								<div class="row g-0 p-1">
									<div class="col-3 text-center">
										<a href="pages/apps/chat.html" class="dropdown-item d-flex flex-column align-items-center justify-content-center wd-70 ht-70"><i data-feather="message-square" class="icon-lg mb-1"></i>
											<p class="tx-12">Chat</p>
										</a>
									</div>
									<div class="col-3 text-center">
										<a href="pages/apps/calendar.html" class="dropdown-item d-flex flex-column align-items-center justify-content-center wd-70 ht-70"><i data-feather="calendar" class="icon-lg mb-1"></i>
											<p class="tx-12">Calendar</p>
										</a>
									</div>
									<div class="col-3 text-center">
										<a href="pages/email/inbox.html" class="dropdown-item d-flex flex-column align-items-center justify-content-center wd-70 ht-70"><i data-feather="mail" class="icon-lg mb-1"></i>
											<p class="tx-12">Email</p>
										</a>
									</div>
									<div class="col-3 text-center">
										<a href="pages/general/profile.html" class="dropdown-item d-flex flex-column align-items-center justify-content-center wd-70 ht-70"><i data-feather="instagram" class="icon-lg mb-1"></i>
											<p class="tx-12">Profile</p>
										</a>
									</div>
								</div>
								<div class="px-3 py-2 d-flex align-items-center justify-content-center border-top">
									<a href="javascript:;">View all</a>
								</div>
							</div>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="messageDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i data-feather="mail"></i>
							</a>
							<div class="dropdown-menu p-0" aria-labelledby="messageDropdown">
								<div class="px-3 py-2 d-flex align-items-center justify-content-between border-bottom">
									<p>9 New Messages</p>
									<a href="javascript:;" class="text-muted">Clear all</a>
								</div>
								<div class="p-1">
									<a href="javascript:;" class="dropdown-item d-flex align-items-center py-2">
										<div class="me-3">
											<img class="wd-30 ht-30 rounded-circle" src="/assets/images/faces/face2.jpg" alt="userr">
										</div>
										<div class="d-flex justify-content-between flex-grow-1">
											<div class="me-4">
												<p>Leonardo Payne</p>
												<p class="tx-12 text-muted">Project status</p>
											</div>
											<p class="tx-12 text-muted">2 min ago</p>
										</div>
									</a>
									
								</div>
								<div class="px-3 py-2 d-flex align-items-center justify-content-center border-top">
									<a href="javascript:;">View all</a>
								</div>
							</div>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i data-feather="bell"></i>
								<div class="indicator">
									<div class="circle"></div>
								</div>
							</a>
							<div class="dropdown-menu p-0" aria-labelledby="notificationDropdown">
								<div class="px-3 py-2 d-flex align-items-center justify-content-between border-bottom">
									<p>6 New Notifications</p>
									<a href="javascript:;" class="text-muted">Clear all</a>
								</div>
								<div class="p-1">
									<a href="javascript:;" class="dropdown-item d-flex align-items-center py-2">
										<div class="wd-30 ht-30 d-flex align-items-center justify-content-center bg-primary rounded-circle me-3">
											<i class="icon-sm text-white" data-feather="gift"></i>
										</div>
										<div class="flex-grow-1 me-2">
											<p>New Order Recieved</p>
											<p class="tx-12 text-muted">30 min ago</p>
										</div>
									</a>
									
								</div>
								<div class="px-3 py-2 d-flex align-items-center justify-content-center border-top">
									<a href="javascript:;">View all</a>
								</div>
							</div>
						</li>
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
										<p class="email text-muted mb-3">{{Auth::user()->email}}</p>
									</div>
								</div>
								<ul class="list-unstyled p-1">
									<li class="dropdown-item py-2">
										<a href="pages/general/profile.html" class="text-body ms-0">
											<i class="me-2 icon-md" data-feather="user"></i>
											<span>Profile</span>
										</a>
									</li>
									<li class="dropdown-item py-2">
										<a href="javascript:;" class="text-body ms-0">
											<i class="me-2 icon-md" data-feather="edit"></i>
											<span>Edit Profile</span>
										</a>
									</li>
									<li class="dropdown-item py-2">
										<a class="nav-link" href="{{ route('logout') }}"
										onclick="event.preventDefault();
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
					</ul>
				</div>
			</nav>
			<!-- partial -->

			@yield('content')
			
			<!-- partial:partials/_footer.html -->
			@include('slices.footer')
			<!-- partial -->

		</div>
	</div>


	<!-- core:js -->
	<script src="{{asset('backend/assets/vendors/core/core.js')}}"></script>
	<!-- endinject -->
	<!-- plugin js for this page -->
	<script src="{{asset('backend/assets/vendors/chartjs/Chart.min.js')}}"></script>
	<script src="{{asset('backend/assets/vendors/jquery.flot/jquery.flot.js')}}"></script>
	<script src="{{asset('backend/assets/vendors/jquery.flot/jquery.flot.resize.js')}}"></script>
	<script src="{{asset('backend/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
	<script src="{{asset('backend/assets/vendors/progressbar.js/progressbar.min.js')}}"></script>
	<!-- end plugin js for this page -->
	<!-- inject:js -->
	<script src="{{asset('backend/assets/vendors/feather-icons/feather.min.js')}}"></script>
	<script src="{{asset('backend/assets/js/template.js')}}"></script>
	<!-- endinject -->
	<!-- custom js for this page -->
	<script src="{{asset('backend/assets/js/dashboard.js')}}"></script>
	<script src="{{asset('backend/assets/js/datepicker.js')}}"></script>
	<!-- end custom js for this page -->
	@stack('js')
</body>

</html>