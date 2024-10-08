<div id="main-wrapper">
	<!--**********************************
			Nav header start
		***********************************-->
	<div class="nav-header">
		<a href="" class="brand-logo">
			<svg class="logo-abbr" width="50" height="50" viewbox="0 0 50 50" fill="none"
				xmlns="http://www.w3.org/2000/svg">
				<rect class="svg-logo-rect" width="50" height="50" rx="6" fill="#EB8153"></rect>
				<path class="svg-logo-path"
					d="M17.5158 25.8619L19.8088 25.2475L14.8746 11.1774C14.5189 9.84988 15.8701 9.0998 16.8205 9.75055L33.0924 22.2055C33.7045 22.5589 33.8512 24.0717 32.6444 24.3951L30.3514 25.0095L35.2856 39.0796C35.6973 40.1334 34.4431 41.2455 33.3397 40.5064L17.0678 28.0515C16.2057 27.2477 16.5504 26.1205 17.5158 25.8619ZM18.685 14.2955L22.2224 24.6007L29.4633 22.6605L18.685 14.2955ZM31.4751 35.9615L27.8171 25.6886L20.5762 27.6288L31.4751 35.9615Z"
					fill="white"></path>
			</svg>
		</a>
		<div class="nav-control">
			<div class="hamburger">
				<span class="line"></span>
				<span class="line"></span>
				<span class="line"></span>
			</div>
		</div>
	</div>
	<!--**********************************
			Nav header end
		***********************************-->

	<!--**********************************
			Header start
		***********************************-->
	<div class="header">
		<div class="header-content">
			<nav class="navbar navbar-expand">
				<div class="collapse navbar-collapse justify-content-between">
					<div class="header-left">
					</div>
					<ul class="navbar-nav header-right main-notification">
						<li class="nav-item dropdown notification_dropdown">
							<a class="nav-link bell dz-theme-mode" href="#">
								<i id="icon-light" class="fa fa-sun-o"></i>
								<i id="icon-dark" class="fa fa-moon-o"></i>
							</a>
						</li>
						<li class="nav-item dropdown notification_dropdown">
							<a class="nav-link bell dz-fullscreen" href="#">
								<svg id="icon-full" viewbox="0 0 24 24" width="20" height="20" stroke="currentColor"
									stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"
									class="css-i6dzq1">
									<path
										d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"
										style="stroke-dasharray: 37, 57; stroke-dashoffset: 0;"></path>
								</svg>
								<svg id="icon-minimize" width="20" height="20" viewbox="0 0 24 24" fill="none"
									stroke="currentColor" stroke-width="2" stroke-linecap="round"
									stroke-linejoin="round" class="feather feather-minimize">
									<path
										d="M8 3v3a2 2 0 0 1-2 2H3m18 0h-3a2 2 0 0 1-2-2V3m0 18v-3a2 2 0 0 1 2-2h3M3 16h3a2 2 0 0 1 2 2v3"
										style="stroke-dasharray: 37, 57; stroke-dashoffset: 0;"></path>
								</svg>
							</a>
						</li>		
						<li class="nav-item dropdown header-profile">
							<a class="nav-link" href="#" role="button" data-toggle="dropdown">
								<!--<img src="images/profile/pic1.jpg" width="20" alt="">-->
								<div class="header-info">
									<span><?php 
                						$nombre = $this->session->userdata('nombre'); 
                						echo isset($nombre) ? $nombre : 'Invitado';?>
									</span>
									<small><?php 
                						$rol = $this->session->userdata('rol'); 
                						echo isset($rol) ? $rol : 'Invitado';?>
									</small>
								</div>
							</a>
							<div class="dropdown-menu dropdown-menu-right">
								<!--<a href="app-profile.html" class="dropdown-item ai-icon">
									<svg id="icon-user1" xmlns="http://www.w3.org/2000/svg" class="text-primary"
										width="18" height="18" viewbox="0 0 24 24" fill="none" stroke="currentColor"
										stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
										<circle cx="12" cy="7" r="4"></circle>
									</svg>
									<span class="ml-2">Perfil </span>
								</a>-->
								<a href="<?= base_url('logout') ?>" href="page-login.html" class="dropdown-item ai-icon">
									<svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger"
										width="18" height="18" viewbox="0 0 24 24" fill="none" stroke="currentColor"
										stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
										<polyline points="16 17 21 12 16 7"></polyline>
										<line x1="21" y1="12" x2="9" y2="12"></line>
									</svg>
									<span class="ml-2">Cerrar Sesión</span>
								</a>
							</div>
						</li>
					</ul>
				</div>
			</nav>
			<div class="sub-header">
				<div class="d-flex align-items-center flex-wrap mr-auto">
					<h5 class="dashboard_bar">SISTEMA PISOSBOL</h5>
				</div>
			</div>
		</div>
	</div>