		
		<!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
			<div class="container-fluid">
				<div class="form-head mb-sm-5 mb-3 d-flex flex-wrap align-items-center">
					<h2 class="font-w600 title mb-2 mr-auto ">Dashboard</h2>
					<a href="javascript:void(0);" class="btn btn-secondary mb-2"><i class="las la-calendar scale5 mr-3"></i>Filtrar Periodo</a>
				</div>
				<div class="row">
					<div class="col-xl-3 col-sm-6 m-t35">
				    	<div class="card card-coin">
				        	<div class="card-body text-center">
				            <svg class="mb-3 currency-icon" width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							  <circle cx="12" cy="12" r="12" fill="white"></circle>
							  <path d="M12 12C14.21 12 16 10.21 16 8C16 5.79 14.21 4 12 4C9.79 4 8 5.79 8 8C8 10.21 9.79 12 12 12ZM12 14C9.33 14 4 15.34 4 18V20H20V18C20 15.34 14.67 14 12 14Z" fill="#00ADA3"></path>
							</svg>
				            <!-- Estadísticas de usuarios -->
				            <h2 class="text-black mb-2 font-w600">
				                <?php echo $total_usuarios; ?> <!-- Muestra el total de usuarios -->
				            </h2>
							Usuarios
				            <p class="mb-0 fs-14">
				                <svg width="29" height="22" viewBox="0 0 29 22" fill="none" xmlns="http://www.w3.org/2000/svg">
				                    <g filter="url(#filter0_d1)">
				                        <path d="M5 16C5.91797 14.9157 8.89728 11.7277 10.5 10L16.5 13L23.5 4" stroke="#2BC155" stroke-width="2" stroke-linecap="round"></path>
				                    </g>
				                    <defs>
				                        <filter id="filter0_d1" x="-3.05176e-05" y="-6.10352e-05" width="28.5001" height="22.0001" filterunits="userSpaceOnUse" color-interpolation-filters="sRGB">
				                            <feflood flood-opacity="0" result="BackgroundImageFix"></feflood>
				                            <fecolormatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"></fecolormatrix>
				                            <feoffset dy="1"></feoffset>
				                            <fegaussianblur stddeviation="2"></fegaussianblur>
				                            <fecolormatrix type="matrix" values="0 0 0 0 0.172549 0 0 0 0 0.72549 0 0 0 0 0.337255 0 0 0 0.61 0"></fecolormatrix>
				                            <feblend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow"></feblend>
				                            <feblend mode="normal" in="SourceGraphic" in2="effect1_dropShadow" result="shape"></feblend>
				                        </filter>
				                    </defs>
				                </svg>
				                <span class="text-success mr-1">
				                    <?php echo $incremento_usuarios; ?>%
				                </span>
				            </p>
				        </div>
				    </div>
				</div>
				<div class="col-xl-3 col-sm-6 m-t35">
				    <div class="card card-coin">
				        <div class="card-body text-center">
				            <svg class="mb-3 currency-icon" width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				                <circle cx="12" cy="12" r="12" fill="white"></circle>
				                <path d="M21 16V8L12 3L3 8V16L12 21L21 16Z" fill="#FFAB2D"></path>
				                <path d="M3.27002 8.15015L12 13L20.73 8.15015" stroke="#FFAB2D" stroke-width="2"></path>
				                <path d="M12 21V13" stroke="#FFAB2D" stroke-width="2"></path>
				            </svg>
				            <h2 class="text-black mb-2 font-w600"><?php echo $total_proveedores; ?></h2>
				            Proveedores
				            <p class="mb-0 fs-13">
				                <svg width="29" height="22" viewBox="0 0 29 22" fill="none" xmlns="http://www.w3.org/2000/svg">
				                    <g filter="url(#filter0_d3)">
				                        <path d="M5 16C5.91797 14.9157 8.89728 11.7277 10.5 10L16.5 13L23.5 4" stroke="#2BC155" stroke-width="2" stroke-linecap="round"></path>
				                    </g>
				                    <defs>
				                        <filter id="filter0_d3" x="-3.05176e-05" y="-6.10352e-05" width="28.5001" height="22.0001" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
				                            <feFlood flood-opacity="0" result="BackgroundImageFix"></feFlood>
				                            <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"></feColorMatrix>
				                            <feOffset dy="1"></feOffset>
				                            <feGaussianBlur stdDeviation="2"></feGaussianBlur>
				                            <feColorMatrix type="matrix" values="0 0 0 0 0.172549 0 0 0 0 0.72549 0 0 0 0 0.337255 0 0 0 0.61 0"></feColorMatrix>
				                            <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow"></feBlend>
				                            <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow" result="shape"></feBlend>
				                        </filter>
				                    </defs>
				                </svg>
				                <span class="text-success mr-1"><?php echo number_format($incremento_proveedores, 2); ?>%</span>
				            </p>
				        </div>
				    </div>
				</div>
				<div class="col-xl-3 col-sm-6 m-t35">
				    <div class="card card-coin">
				        <div class="card-body text-center">
						<svg class="mb-3 currency-icon" width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						  <circle cx="12" cy="12" r="12" fill="white"></circle>
						  <path d="M12 12C14.21 12 16 10.21 16 8C16 5.79 14.21 4 12 4C9.79 4 8 5.79 8 8C8 10.21 9.79 12 12 12ZM12 14C9.33 14 4 15.34 4 18V20H20V18C20 15.34 14.67 14 12 14Z" fill="#FFAB2D"></path>
						</svg>
				            <h2 class="text-black mb-2 font-w600"><?php echo $total_clientes; ?></h2>
							Clientes
				            <p class="mb-0 fs-13">
				                <svg width="29" height="22" viewBox="0 0 29 22" fill="none" xmlns="http://www.w3.org/2000/svg">
				                    <g filter="url(#filter0_d3)">
				                        <path d="M5 16C5.91797 14.9157 8.89728 11.7277 10.5 10L16.5 13L23.5 4" stroke="#2BC155" stroke-width="2" stroke-linecap="round"></path>
				                    </g>
				                    <defs>
				                        <filter id="filter0_d3" x="-3.05176e-05" y="-6.10352e-05" width="28.5001" height="22.0001" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
				                            <feFlood flood-opacity="0" result="BackgroundImageFix"></feFlood>
				                            <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"></feColorMatrix>
				                            <feOffset dy="1"></feOffset>
				                            <feGaussianBlur stdDeviation="2"></feGaussianBlur>
				                            <feColorMatrix type="matrix" values="0 0 0 0 0.172549 0 0 0 0 0.72549 0 0 0 0 0.337255 0 0 0 0.61 0"></feColorMatrix>
				                            <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow"></feBlend>
				                            <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow" result="shape"></feBlend>
				                        </filter>
				                    </defs>
				                </svg>
				                <span class="text-success mr-1"><?php echo number_format($incremento_clientes, 2); ?>%</span>
				            </p>    
				        </div>
				    </div>
				</div>
				<div class="col-xl-3 col-sm-6 m-t35">
				    <div class="card card-coin">
				        <div class="card-body text-center">
				            <svg class="mb-3 currency-icon" width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				                <circle cx="12" cy="12" r="12" fill="white"></circle>
				                <path d="M4 4H20V8H4V4Z" fill="#FFAB2D"></path>
				                <path d="M4 8H20V12H4V8Z" fill="#FFAB2D"></path>
				                <path d="M4 12H20V16H4V12Z" fill="#FFAB2D"></path>
				                <path d="M4 16H20V20H4V16Z" fill="#FFAB2D"></path>
				            </svg>
				            <h2 class="text-black mb-2 font-w600"><?php echo $total_productos; ?></h2>
				            Productos
				            <p class="mb-0 fs-13">
				                <svg width="29" height="22" viewBox="0 0 29 22" fill="none" xmlns="http://www.w3.org/2000/svg">
				                    <g filter="url(#filter0_d3)">
				                        <path d="M5 16C5.91797 14.9157 8.89728 11.7277 10.5 10L16.5 13L23.5 4" stroke="#2BC155" stroke-width="2" stroke-linecap="round"></path>
				                    </g>
				                    <defs>
				                        <filter id="filter0_d3" x="-3.05176e-05" y="-6.10352e-05" width="28.5001" height="22.0001" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
				                            <feFlood flood-opacity="0" result="BackgroundImageFix"></feFlood>
				                            <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"></feColorMatrix>
				                            <feOffset dy="1"></feOffset>
				                            <feGaussianBlur stdDeviation="2"></feGaussianBlur>
				                            <feColorMatrix type="matrix" values="0 0 0 0 0.172549 0 0 0 0 0.72549 0 0 0 0 0.337255 0 0 0 0.61 0"></feColorMatrix>
				                            <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow"></feBlend>
				                            <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow" result="shape"></feBlend>
				                        </filter>
				                    </defs>
				                </svg>
				                <span class="text-success mr-1"><?php echo number_format($incremento_productos, 2); ?>%</span>
				            </p>
				        </div>
				    </div>
				</div>
				</div>
				<div class="row">
					<div class="col-xl-9 col-xxl-8">
					    <div class="card">
					        <div class="card-header border-0 flex-wrap pb-0">
					            <div class="mb-3">
					                <h4 class="fs-20 text-black">Ventas - Resumen</h4>
					                <p class="mb-0 fs-12 text-black">Estadísticas de ventas semanales y mensuales</p>
					            </div>
					            <select id="periodoVentas" class="style-1 btn-secondary default-select">
					                <option value="semanal">Semanal</option>
					                <option value="mensual">Mensual</option>
					            </select>
					        </div>
					        <div class="card-body pb-2 px-3">
					            <canvas id="ventasChart" class="market-line"></canvas>
					        </div>
					        <div class="card-footer text-center">
					            <p class="mb-0 fs-13">
					                <span class="text-success mr-1">Incremento Semanal: <?php echo number_format($incremento_semanal, 2); ?>%</span>
					                <br>
					                <span class="text-success mr-1">Incremento Mensual: <?php echo number_format($incremento_mensual, 2); ?>%</span>
					                <br>
					                <span class="text-primary mr-1">Ventas de Hoy: <?php echo $ventas_hoy; ?></span>
					            </p>
					        </div>
					    </div>
					</div>

					<script>
					    var ctx = document.getElementById('ventasChart').getContext('2d');
					    var ventasChart = new Chart(ctx, {
					        type: 'line',
					        data: {
					            labels: ['Semana Pasada', 'Esta Semana'], // Para el gráfico semanal
					            datasets: [{
					                label: 'Ventas',
					                data: [<?php echo $ventas_semana_anterior; ?>, <?php echo $ventas_semanales; ?>], // Datos semanales
					                backgroundColor: 'rgba(54, 162, 235, 0.2)',
					                borderColor: 'rgba(54, 162, 235, 1)',
					                borderWidth: 1
					            }]
					        },
					        options: {
					            responsive: true,
					            scales: {
					                y: {
					                    beginAtZero: true
					                }
					            }
					        }
					    });
					
					    // Cambiar el gráfico según el periodo seleccionado
					    document.getElementById('periodoVentas').addEventListener('change', function() {
					        var periodo = this.value;
					        if (periodo === 'semanal') {
					            ventasChart.data.labels = ['Semana Pasada', 'Esta Semana'];
					            ventasChart.data.datasets[0].data = [<?php echo $ventas_semana_anterior; ?>, <?php echo $ventas_semanales; ?>];
					        } else {
					            ventasChart.data.labels = ['Mes Pasado', 'Este Mes'];
					            ventasChart.data.datasets[0].data = [<?php echo $ventas_mes_anterior; ?>, <?php echo $ventas_mensuales; ?>];
					        }
					        ventasChart.update();
					    });
					</script>
					<div class="col-xl-3 col-xxl-4">
						<div class="card">
							<div class="card-header border-0 pb-0">
								<h4 class="fs-20 text-black">Current Statistic</h4>
							</div>
							<div class="card-body pb-0">
								<div id="currentChart" class="current-chart"></div>
								<div class="chart-content">	
									<div class="d-flex justify-content-between mb-2 align-items-center">
										<div>
											<svg class="mr-2" width="15" height="15" viewbox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
												<rect width="15" height="15" rx="7.5" fill="#EB8153"></rect>
											</svg>
											<span class="fs-14">Income (66%)</span>
										</div>
										<div>
											<h5 class="mb-0">$167,884.21</h5>
										</div>
									</div>
									<div class="d-flex justify-content-between mb-2 align-items-center">
										<div>
											<svg class="mr-2" width="15" height="15" viewbox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
												<rect width="15" height="15" rx="7.5" fill="#71B945"></rect>
											</svg>

											<span class="fs-14">Income (50%)</span>
										</div>
										<div>
											<h5 class="mb-0">$56,411.33</h5>
										</div>
									</div>
									<div class="d-flex justify-content-between mb-2 align-items-center">
										<div>
											<svg class="mr-2" width="15" height="15" viewbox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
												<rect width="15" height="15" rx="7.5" fill="#4A8CDA"></rect>
											</svg>
											<span class="fs-14">Income (11%)</span>
										</div>
										<div>
											<h5 class="mb-0">$81,981.22</h5>
										</div>
									</div>
									<div class="d-flex justify-content-between mb-2 align-items-center">
										<div>
											<svg class="mr-2" width="15" height="15" viewbox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
												<rect width="15" height="15" rx="7.5" fill="#6647BF"></rect>
											</svg>
											<span class="fs-14">Income (23%)</span>
										</div>
										<div>
											<h5 class="mb-0">$12,432.51</h5>
										</div>
									</div>
								</div>	
							</div>
						</div>
					</div>
				</div>
					<div class="row">
						<div class="col-xl-9 col-xxl-8">
						    <div class="card">
						        <div class="card-header border-0 flex-wrap pb-0">
						            <div class="mb-3">
						                <h4 class="fs-20 text-black">Compras - Resumen</h4>
						                <p class="mb-0 fs-12 text-black">Estadísticas de compras semanales y mensuales</p>
						            </div>
						            <select id="periodoCompras" class="style-1 btn-secondary default-select">
						                <option value="semanal">Semanal</option>
						                <option value="mensual">Mensual</option>
						            </select>
						        </div>
						        <div class="card-body pb-2 px-3">
						            <canvas id="comprasChart" class="market-line"></canvas>
						        </div>
						        <div class="card-footer text-center">
						            <p class="mb-0 fs-13">
						                <span class="text-success mr-1">Incremento Semanal: <?php echo number_format($incremento_semanal, 2); ?>%</span>
						                <br>
						                <span class="text-success mr-1">Incremento Mensual: <?php echo number_format($incremento_mensual, 2); ?>%</span>
						                <br>
						                <span class="text-primary mr-1">Compras de Hoy: <?php echo $compras_hoy; ?></span>
						            </p>
						        </div>
						    </div>
						</div>

						<script>
						    var ctx = document.getElementById('comprasChart').getContext('2d');
						    var comprasChart = new Chart(ctx, {
						        type: 'line',
						        data: {
						            labels: ['Semana Pasada', 'Esta Semana'], // Para el gráfico semanal
						            datasets: [{
						                label: 'Compras',
						                data: [<?php echo $compras_semana_anterior; ?>, <?php echo $compras_semanales; ?>], // Datos semanales
						                backgroundColor: 'rgba(255, 99, 132, 0.2)',
						                borderColor: 'rgba(255, 99, 132, 1)',
						                borderWidth: 1
						            }]
						        },
						        options: {
						            responsive: true,
						            scales: {
						                y: {
						                    beginAtZero: true
						                }
						            }
						        }
						    });
						
						    // Cambiar el gráfico según el periodo seleccionado
						    document.getElementById('periodoCompras').addEventListener('change', function() {
						        var periodo = this.value;
						        if (periodo === 'semanal') {
						            comprasChart.data.labels = ['Semana Pasada', 'Esta Semana'];
						            comprasChart.data.datasets[0].data = [<?php echo $compras_semana_anterior; ?>, <?php echo $compras_semanales; ?>];
						        } else {
						            comprasChart.data.labels = ['Mes Pasado', 'Este Mes'];
						            comprasChart.data.datasets[0].data = [<?php echo $compras_mes_anterior; ?>, <?php echo $compras_mensuales; ?>];
						        }
						        comprasChart.update();
						    });
						</script>
					<div class="col-xl-3 col-xxl-4">
						<div class="card">
							<div class="card-header border-0 pb-0">
								<h4 class="fs-20 text-black">Current Statistic</h4>
							</div>
							<div class="card-body pb-0">
								<div id="currentChart" class="current-chart"></div>
								<div class="chart-content">	
									<div class="d-flex justify-content-between mb-2 align-items-center">
										<div>
											<svg class="mr-2" width="15" height="15" viewbox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
												<rect width="15" height="15" rx="7.5" fill="#EB8153"></rect>
											</svg>
											<span class="fs-14">Income (66%)</span>
										</div>
										<div>
											<h5 class="mb-0">$167,884.21</h5>
										</div>
									</div>
									<div class="d-flex justify-content-between mb-2 align-items-center">
										<div>
											<svg class="mr-2" width="15" height="15" viewbox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
												<rect width="15" height="15" rx="7.5" fill="#71B945"></rect>
											</svg>

											<span class="fs-14">Income (50%)</span>
										</div>
										<div>
											<h5 class="mb-0">$56,411.33</h5>
										</div>
									</div>
									<div class="d-flex justify-content-between mb-2 align-items-center">
										<div>
											<svg class="mr-2" width="15" height="15" viewbox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
												<rect width="15" height="15" rx="7.5" fill="#4A8CDA"></rect>
											</svg>
											<span class="fs-14">Income (11%)</span>
										</div>
										<div>
											<h5 class="mb-0">$81,981.22</h5>
										</div>
									</div>
									<div class="d-flex justify-content-between mb-2 align-items-center">
										<div>
											<svg class="mr-2" width="15" height="15" viewbox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
												<rect width="15" height="15" rx="7.5" fill="#6647BF"></rect>
											</svg>
											<span class="fs-14">Income (23%)</span>
										</div>
										<div>
											<h5 class="mb-0">$12,432.51</h5>
										</div>
									</div>
								</div>	
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
        <!--**********************************
            Content body end
        ***********************************-->
