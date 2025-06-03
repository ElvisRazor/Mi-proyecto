<!--**********************************
    Content body start
***********************************-->
<div class="content-body">
    <div class="container-fluid">
        <!-- Mostrar mensaje de éxito si existe -->
        <?php if ($this->session->flashdata('success')): ?>
            <script type="text/javascript">
                // Mostrar el mensaje flotante con Toastr
                toastr.success('<?php echo $this->session->flashdata('success'); ?>', '¡Éxito!', {
                    "positionClass": "toast-top-right", // Posición en la parte superior derecha
                    "closeButton": true,               // Botón de cerrar
                    "timeOut": 6000,                   // Tiempo en milisegundos (2 segundos)
                    "progressBar": true,               // Barra de progreso
                });
            </script>
        <?php endif; ?>
        <div class="form-head mb-sm-5 mb-3 d-flex flex-wrap align-items-center">
            <h2 class="font-w600 title mb-2 mr-auto">Dashboard</h2>
        </div>
        <div class="row">
            <!-- Estadísticas de Usuarios -->
            <div class="col-xl-3 col-sm-6 m-t35">
                <div class="card card-coin">
                    <div class="card-body text-center">
                        <i class="fas fa-user mb-3 currency-icon" style="font-size: 80px; color: #00ADA3;"></i>
                        <h2 class="text-black mb-2 font-w600"><?php echo $total_usuarios; ?></h2>
                        <p>Usuarios</p>
                        <p class="mb-0 fs-14">
                            <svg width="29" height="22" viewBox="0 0 29 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g filter="url(#filter0_d1)">
                                    <path d="M5 16C5.91797 14.9157 8.89728 11.7277 10.5 10L16.5 13L23.5 4" stroke="#2BC155" stroke-width="2" stroke-linecap="round"></path>
                                </g>
                                <defs>
                                    <filter id="filter0_d1" x="-3.05176e-05" y="-6.10352e-05" width="28.5001" height="22.0001" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
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
                            <span class="text-success mr-1"><?php echo $incremento_usuarios; ?>%</span>
                        </p>
                    </div>
                </div>
            </div>
            <!-- Estadísticas de Proveedores -->
            <div class="col-xl-3 col-sm-6 m-t35">
                <div class="card card-coin">
                    <div class="card-body text-center">
                        <i class="fas fa-truck mb-3 currency-icon" style="font-size: 80px; color: #FFAB2D;"></i>
                        <h2 class="text-black mb-2 font-w600"><?php echo $total_proveedores; ?></h2>
                        <p>Proveedores</p>
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
            <!-- Estadísticas de Clientes -->
            <div class="col-xl-3 col-sm-6 m-t35">
                <div class="card card-coin">
                    <div class="card-body text-center">
                        <i class="fas fa-user mb-3 currency-icon" style="font-size: 80px; color: #FFAB2D;"></i>
                        <h2 class="text-black mb-2 font-w600"><?php echo $total_clientes; ?></h2>
                        <p>Clientes</p>
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
            <!-- Estadísticas de Productos -->
            <div class="col-xl-3 col-sm-6 m-t35">
                <div class="card card-coin">
                    <div class="card-body text-center">
                        <i class="fas fa-box mb-3 currency-icon" style="font-size: 80px; color: #00ADA3;"></i>
                        <h2 class="text-black mb-2 font-w600"><?php echo $total_productos; ?></h2>
                        <p>Productos</p>
                        <p class="mb-0 fs-14">
                            <svg width="29" height="22" viewBox="0 0 29 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g filter="url(#filter0_d1)">
                                    <path d="M5 16C5.91797 14.9157 8.89728 11.7277 10.5 10L16.5 13L23.5 4" stroke="#2BC155" stroke-width="2" stroke-linecap="round"></path>
                                </g>
                                <defs>
                                    <filter id="filter0_d1" x="-3.05176e-05" y="-6.10352e-05" width="28.5001" height="22.0001" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
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
    </div>
	<div class="col-12">
		<div class="card">
    		<div class="card-header border-0 flex-wrap pb-0">
        		<h4 class="card-title">Estadísticas de Ventas</h4>
    		</div>
    		<div class="row">
                <div class="form-group col-sm-6">
        		<!-- Gráfico de Ventas por Mes -->
        		    <canvas id="ventasPorMesChart" height="120"></canvas>
       		 	</div>
        		<!-- Gráfico de Ventas Totales -->
        		<div class="form-group col-sm-6">
        	    	<canvas id="ventasTotalesChart" height="120"></canvas>
        		</div>
			</div>
				<!-- Gráfico de Ventas por Categoría -->
				<div class="form-group col-sm-3">
            		<canvas id="ventasPorCategoriaChart" height="100"></canvas>
        		</div>
			</div>
    	</div>
	</div>
</div>
<!--**********************************
    Content body end
***********************************-->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Datos para el gráfico de ventas por mes
    var ventasPorMesData = {
        labels: <?= json_encode(array_column($ventas_por_mes, 'mes')); ?>,
        datasets: [{
            label: 'Ventas por Mes',
            data: <?= json_encode(array_column($ventas_por_mes, 'total_venta')); ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    };
    var ventasPorMesConfig = {
        type: 'line',
        data: ventasPorMesData,
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    };
    // Datos para el gráfico de ventas totales
    var ventasTotalesData = {
        labels: ['Ventas Totales'],
        datasets: [{
            label: 'Ventas Totales',
            data: [<?= $ventas_totales->total_venta; ?>],
            backgroundColor: 'rgba(75, 192, 192, 0.5)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    };
    var ventasTotalesConfig = {
        type: 'bar',
        data: ventasTotalesData,
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    };
    // Datos para el gráfico de ventas por categoría
    var ventasPorCategoriaData = {
    labels: <?= json_encode(array_column($ventas_por_categoria, 'categoria')); ?>,
    datasets: [{
        label: 'Ventas por Productos',
        data: <?= json_encode(array_column($ventas_por_categoria, 'total_venta')); ?>,
        backgroundColor: [
            'rgba(255, 99, 132, 0.5)',   // Color 1
            'rgba(54, 162, 235, 0.5)',   // Color 2
            'rgba(255, 206, 86, 0.5)',   // Color 3
            'rgba(75, 192, 192, 0.5)',   // Color 4
            'rgba(153, 102, 255, 0.5)',  // Color 5
            'rgba(255, 159, 64, 0.5)',   // Color 6
            'rgba(255, 99, 71, 0.5)',    // Color 7
            'rgba(123, 104, 238, 0.5)',  // Color 8
            'rgba(0, 255, 255, 0.5)',    // Color 9
            'rgba(0, 255, 0, 0.5)'       // Color 10
        ],
        borderColor: [
            'rgba(255, 99, 132, 1)',   // Color 1 border
            'rgba(54, 162, 235, 1)',   // Color 2 border
            'rgba(255, 206, 86, 1)',   // Color 3 border
            'rgba(75, 192, 192, 1)',   // Color 4 border
            'rgba(153, 102, 255, 1)',  // Color 5 border
            'rgba(255, 159, 64, 1)',   // Color 6 border
            'rgba(255, 99, 71, 1)',    // Color 7 border
            'rgba(123, 104, 238, 1)',  // Color 8 border
            'rgba(0, 255, 255, 1)',    // Color 9 border
            'rgba(0, 255, 0, 1)'       // Color 10 border
        ],
        borderWidth: 1
    }]
    };

    var ventasPorCategoriaConfig = {
        type: 'pie',
        data: ventasPorCategoriaData
    };

    // Inicializar los gráficos
    new Chart(document.getElementById('ventasPorMesChart'), ventasPorMesConfig);
    new Chart(document.getElementById('ventasTotalesChart'), ventasTotalesConfig);
    new Chart(document.getElementById('ventasPorCategoriaChart'), ventasPorCategoriaConfig);
</script>
