<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Consulta de Compras</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-lg-6 mb-3">
                            <a href="#" onclick="generarComprasPDF();" class="btn btn-secondary text-default">Generar PDF</a>
                            <a href="<?= base_url('dashboard'); ?>" class="btn btn-secondary text-default">Volver</a>
                        </div>

                        <div class="card-body">
                            <!-- Formulario para buscar por rango de fechas -->
                            <form action="<?= base_url('reporte/comprasPorFechas'); ?>" method="GET">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="fecha_inicio">Fecha de Inicio:</label>
                                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="fecha_fin">Fecha de Fin:</label>
                                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Buscar</button>
                            </form>

                            <!-- Mensaje de rango de fechas -->
                            <?php if (isset($mensaje)): ?>
                                <div class="alert alert-info mt-3"><?= $mensaje; ?></div>
                            <?php endif; ?>

                            <!-- Mostrar fechas de consulta -->
                            <?php if (!empty($this->input->get('fecha_inicio')) && !empty($this->input->get('fecha_fin'))): ?>
                                <h5 class="mt-3">Consulta de compras desde: <?= date('d/m/Y', strtotime($this->input->get('fecha_inicio'))) ?> hasta <?= date('d/m/Y', strtotime($this->input->get('fecha_fin'))) ?></h5>
                            <?php endif; ?>

                            <!-- Tabla con los resultados -->
                            <div class="table-responsive mt-4">
                                <table id="purchasesTable" class="table align-items-center table-flush">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">N°</th>
                                            <th scope="col">Proveedor</th>
                                            <th scope="col">Producto</th>
                                            <th scope="col">Fecha</th>
                                            <th scope="col">Cantidad de Productos</th>
                                            <th scope="col">Total Compras</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($compras)): ?>
                                            <?php $number = 1; foreach ($compras as $compra): ?>
                                                <tr>
                                                    <td><?= $number++; ?></td>
                                                    <td><?= $compra->proveedor; ?></td>
                                                    <td><?= $compra->producto; ?></td>
                                                    <td><?= date("Y-m-d", strtotime($compra->fecha)); ?></td>
                                                    <td><?= $compra->cantidad_productos; ?></td>
                                                    <td>Bs. <?= number_format($compra->total_compra, 2); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="6">No hay datos disponibles.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Mostrar total de las compras -->
                            <?php if (isset($total_general) && !empty($compras)): ?>
                                <h5 class="mt-4">El total de las compras desde <?= $this->input->get('fecha_inicio'); ?> hasta <?= $this->input->get('fecha_fin'); ?> es: <strong>Bs. <?= number_format($total_general, 2); ?></strong></h5>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfico de compras -->
        <div class="card mt-12 col-md-6">
            <div class="card-body">
                <h5>Gráfico de Compras por Fechas</h5>
                <canvas id="comprasChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Importar la librería de Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const labels = <?= json_encode(array_column($compras, 'fecha')); ?>;
    const dataCantidad = <?= json_encode(array_column($compras, 'cantidad_productos')); ?>;
    const dataTotalCompras = <?= json_encode(array_map(function($compra) {
        return (float) $compra->total_compra;
    }, $compras)); ?>;

    // Configuración del gráfico
    const ctx = document.getElementById('comprasChart').getContext('2d');
    const comprasChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Cantidad de Productos Comprados',
                    data: dataCantidad,
                    backgroundColor: 'rgba(0, 123, 255, 0.7)', // Azul claro
                    borderColor: 'rgba(0, 123, 255, 1)', // Azul intenso
                    borderWidth: 1
                },
                {
                    label: 'Total de Compras Bs',
                    data: dataTotalCompras,
                    backgroundColor: 'rgba(40, 167, 69, 0.7)', // Verde claro
                    borderColor: 'rgba(40, 167, 69, 1)', // Verde intenso
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    labels: {
                        color: '#ffffff' // Etiquetas blancas
                    }
                },
                title: {
                    display: true,
                    text: 'Cantidad de Productos y Total de Compras por Fecha',
                    color: '#ffffff' // Título blanco
                }
            },
            scales: {
                x: {
                    ticks: {
                        color: '#ffffff' // Etiquetas del eje X en blanco
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.2)' // Líneas de la cuadrícula en gris claro
                    }
                },
                y: {
                    ticks: {
                        color: '#ffffff' // Etiquetas del eje Y en blanco
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.2)' // Líneas de la cuadrícula en gris claro
                    }
                }
            }
        },
        plugins: [{
            beforeDraw: (chart) => {
                const ctx = chart.canvas.getContext('2d');
                ctx.save();
                ctx.fillStyle = '#222831'; // Fondo oscuro
                ctx.fillRect(0, 0, chart.width, chart.height);
                ctx.restore();
            }
        }]
    });

    // Función para generar el PDF
    function generarComprasPDF() {
        const canvas = document.getElementById('comprasChart');
        const imgData = canvas.toDataURL('image/png');

        fetch('<?= base_url("reporte/imprimirConsultaCompras") ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                grafico: imgData,
                compras: <?= json_encode($compras); ?>,
                fecha_inicio: '<?= $fecha_inicio; ?>',
                fecha_fin: '<?= $fecha_fin; ?>',
                total_general: <?= $total_general; ?>
            })
        }).then(response => response.blob())
          .then(blob => {
              const url = URL.createObjectURL(blob);
              window.open(url, '_blank');
          });
    }
</script>
