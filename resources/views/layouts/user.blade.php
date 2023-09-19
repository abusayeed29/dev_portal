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
	
	<script src="{{ asset('js/app.js') }}" defer></script>
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
					N<span>HD</span>
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
						<a href="{{route('user.dashboard')}}" class="nav-link">
							<i class="link-icon" data-feather="box"></i>
							<span class="link-title">Dashboard</span>
						</a>
					</li>

					<li class="nav-item nav-category">HelpDesk System</li>
					<li class="nav-item">
						<a href="{{route('user.ticket.index')}}" class="nav-link">
							<i class="link-icon" data-feather="activity"></i>
							<span class="link-title">Tickets</span>
						</a>
					</li>
					@php
					$dailyReadPerm = getReadPermission(7,1);
					$tktReportView = getReadPermission(9,1);
					$ast_perm = getReadPermission(12,1);

					$stage_1 = App\Models\VmsApprovalPath::where('user_id', Auth::user()->id)
									->where('stage', 1)->first();

					$stage_2 = App\Models\VmsApprovalPath::where('user_id', Auth::user()->id)
										->where('stage', 2)->first();

					$stage_3 = App\Models\VmsApprovalPath::where('user_id', Auth::user()->id)
										->where('stage', 3)->first();

					@endphp

					@if(!empty($dailyReadPerm))
					<li class="nav-item">
						<a href="{{route('user.ticket.support')}}" class="nav-link">
							<i class="link-icon" data-feather="activity"></i>
							<span class="link-title">Daily Activities</span>
						</a>
					</li>
					@endif

					@if(!empty($tktReportView))
					<li class="nav-item">
						<a href="{{url('/tickets/company-reports')}}" class="nav-link">
							<i class="link-icon" data-feather="activity"></i>
							<span class="link-title">Reports</span>
						</a>
					</li>
					@endif

					<li class="nav-item nav-category">Employees</li>
					<li class="nav-item">
						<a href="{{route('user.employee.index')}}" class="nav-link">
							<i class="link-icon" data-feather="users"></i>
							<span class="link-title">Employees</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="{{route('roster.index')}}" class="nav-link">
							<i class="feather feather-calendar link-icon" data-feather="calendar"></i>
							<span class="link-title">Roster</span>
						</a>
					</li>

					<li class="nav-item">
						<a href="{{route('book.index')}}" class="nav-link">
							<i class="feather feather-calendar link-icon" data-feather="calendar"></i>
							<span class="link-title">Book mRoom</span>
						</a>
					</li>

					@if(!empty($ast_perm))
					<li class="nav-item nav-category">Assets Management</li>
					<li class="nav-item">
						<a class="nav-link" data-bs-toggle="collapse" href="#asset" role="button" aria-expanded="false" aria-controls="asset">
							<i class="link-icon" data-feather="activity"></i>
							<span class="link-title">Assets</span>
							<i class="link-arrow" data-feather="chevron-down"></i>
						</a>
						<div class="collapse" id="asset">
							<ul class="nav sub-menu">
								<li class="nav-item">
									<a href="{{route('asset.dashboard')}}" class="nav-link">Overview</a>
								</li>
								<li class="nav-item">
									<a href="{{route('asset.manage')}}" class="nav-link">All Lists</a>
								</li>
								<li class="nav-item">
									<a href="{{route('asset.component.manage')}}" class="nav-link">Components</a>
								</li>
								<li class="nav-item">
									<a href="{{route('asset.maintenance.manage')}}" class="nav-link">Maintenances</a>
								</li>
								<li class="nav-item">
									<a href="#" class="nav-link">Brands</a>
								</li>
								<li class="nav-item">
									<a href="#" class="nav-link">Suppliers</a>
								</li>
							</ul>
						</div>
					</li>
					@endif
					
					<li class="nav-item nav-category">Vehicle Management (VMS)</li>

					@if (getCreatePermission(16, 1) == true)
					<li class="nav-item">
						<a href="{{route('vms.vehicle.manage')}}" class="nav-link">
							<i class="link-icon" data-feather="activity"></i>
							<span class="link-title">Vehicles</span>
						</a>
					</li>
					@endif
					
					<li class="nav-item">
						<a href="{{route('vms.driver.manage')}}" class="nav-link">
							<i class="link-icon" data-feather="activity"></i>
							<span class="link-title">Drivers</span>
						</a>
					</li>
					
					<li class="nav-item">
						<a href="{{route('vms.manage')}}" class="nav-link">
							<i class="link-icon" data-feather="activity"></i>
							<span class="link-title">My Requisition</span>
						</a>
					</li>
					
					@if(!empty($stage_1))
					<li class="nav-item">
						<a href="{{route('vms.requisition.stage', 1)}}" class="nav-link">
							<i class="link-icon" data-feather="activity"></i>
							<span class="link-title">Department Approval</span>
						</a>
					</li>
					@endif

					@if(!empty($stage_2))
					<li class="nav-item">
						<a href="{{route('vms.requisition.stage', 2)}}" class="nav-link">
							<i class="link-icon" data-feather="activity"></i>
							<span class="link-title">Admin Approval</span>
						</a>
					</li>
					@endif
					@if(!empty($stage_3))
					<li class="nav-item">
						<a href="{{route('vms.requisition.stage', 3)}}" class="nav-link">
							<i class="link-icon" data-feather="activity"></i>
							<span class="link-title">Transport Approval</span>
						</a>
					</li>
					@endif

					<!--<li class="nav-item nav-category">Stationery Management</li>
					<li class="nav-item">
						<a class="nav-link" data-bs-toggle="collapse" href="#requisitiondUI" role="button" aria-expanded="false" aria-controls="requisitiondUI">
							<i class="link-icon" data-feather="activity"></i>
							<span class="link-title">Requisition</span>
							<i class="link-arrow" data-feather="chevron-down"></i>
						</a>
						<div class="collapse" id="requisitiondUI">
							<ul class="nav sub-menu">
								<li class="nav-item">
									<a href="#" class="nav-link">All Requisition</a>
								</li>
								<li class="nav-item">
									<a href="#" class="nav-link">Approval Requisition</a>
								</li>
							</ul>
						</div>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-bs-toggle="collapse" href="#stationerydUI" role="button" aria-expanded="false" aria-controls="stationerydUI">
							<i class="link-icon" data-feather="activity"></i>
							<span class="link-title">Stock</span>
							<i class="link-arrow" data-feather="chevron-down"></i>
						</a>
						<div class="collapse" id="stationerydUI">
							<ul class="nav sub-menu">
								<li class="nav-item">
									<a href="{{route('user.srm.manage')}}" class="nav-link">All Items</a>
								</li>
								<li class="nav-item">
									<a href="#" class="nav-link">Categories</a>
								</li>
								<li class="nav-item">
									<a href="#" class="nav-link">Wirehouses</a>
								</li>
								<li class="nav-item">
									<a href="#" class="nav-link">All Purchase</a>
								</li>
								<li class="nav-item">
									<a href="#" class="nav-link">Purchase Approval</a>
								</li>
								<li class="nav-item">
									<a href="#" class="nav-link"> Manage Stock</a>
								</li>
							</ul>
						</div>
					</li> -->
				

					<!-- <li class="nav-item nav-category">CRD Manage</li>
					<li class="nav-item">
						<a href="#" class="nav-link">
							<i class="link-icon" data-feather="user-plus"></i>
							<span class="link-title">Leads</span>
						</a>
						<a href="{{route('user.projects.index')}}" class="nav-link">
							<i class="link-icon" data-feather="arrow-right"></i>
							<span class="link-title">Projects</span>
						</a>
						<a href="{{route('user.clients.index')}}" class="nav-link">
							<i class="link-icon" data-feather="user-plus"></i>
							<span class="link-title">Clients</span>
						</a>
						<a href="#" class="nav-link">
							<i class="link-icon" data-feather="arrow-right"></i>
							<span class="link-title">Task</span>
						</a>
					</li> -->


				</ul>
			</div>
		</nav>
		<nav class="settings-sidebar">
			<div class="sidebar-body">
				<a href="#" class="settings-sidebar-toggler">
					<svg xmlns="#" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings">
						<circle cx="12" cy="12" r="3"></circle>
						<path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
					</svg>
				</a>
				<activeusers :user="{{ auth()->user() }}"></activeusers>
			</div>
		</nav>
		<!-- partial -->

		<div class="page-wrapper">

			<!-- partial:partials/_navbar.html -->
			@include('slices.navbar')
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