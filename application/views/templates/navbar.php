<div id="main-wrapper">
	<!--**********************************
			Nav header start
		***********************************-->
	<div class="nav-header">
		<a href="" class="brand-logo">
		<svg class="logo-abbr" width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
		    <rect width="50" height="50" rx="8" fill="#333333"></rect>
		    <circle cx="25" cy="25" r="15" fill="#FF6B35"></circle>
		    <path d="M25 15L30 30H20L25 15Z" fill="#FFFFFF"></path>
		    <path d="M23 25H27V35H23V25Z" fill="#333333"></path>
		</svg>
		SISTEMA
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
					<h5 class="dashboard_bar">SISTEMA DUKEL IMPORTACIONES</h5>
				</div>
			</div>
		</div>
	</div>