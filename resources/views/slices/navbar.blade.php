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

						<ticket v-bind:tickets="tickets"></ticket>
					
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
										<a href="{{route('head.settings.profile')}}" class="text-body ms-0">
											<i class="me-2 icon-md" data-feather="user"></i>
											<span>Profile</span>
										</a>
									</li>
									<li class="dropdown-item py-2">
										<a href="{{route('head.settings.changePasswordGet')}}" class="text-body ms-0">
											<i class="me-2 icon-md" data-feather="edit"></i>
											<span>Password Change</span>
										</a>
									</li>
									@elseif(Auth::user()->role_id ===3)
									<li class="dropdown-item py-2">
										<a href="{{route('sub.settings.profile')}}" class="text-body ms-0">
											<i class="me-2 icon-md" data-feather="user"></i>
											<span>Profile</span>
										</a>
									</li>
									<li class="dropdown-item py-2">
										<a href="{{route('sub.settings.changePasswordGet')}}" class="text-body ms-0">
											<i class="me-2 icon-md" data-feather="edit"></i>
											<span>Password Change</span>
										</a>
									</li>
									@else
									<li class="dropdown-item py-2">
										<a href="{{route('user.settings.profile')}}" class="text-body ms-0">
											<i class="me-2 icon-md" data-feather="user"></i>
											<span>Profile</span>
										</a>
									</li>
									<li class="dropdown-item py-2">
										<a href="{{route('user.settings.changePasswordGet')}}" class="text-body ms-0">
											<i class="me-2 icon-md" data-feather="edit"></i>
											<span>Password Change</span>
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
					</ul>
				</div>
			</nav>