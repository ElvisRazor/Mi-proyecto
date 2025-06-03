<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Clientes Clasificados</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-lg-6">
                            <a href="#" onclick="generarPDF();" class="btn btn-secondary text-default">Generar PDF</a>
                            <a href="<?= base_url('dashboard'); ?>" class="btn btn-secondary text-default">Volver</a>
                        </div>
                        <!-- Formulario para buscar por rango de fechas -->
                        <form action="<?= base_url('reporte/clientesFieles'); ?>" method="GET">
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

                        <!-- Tabla con los resultados -->
                        <div class="table-responsive mt-4">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">N°</th>
                                        <th scope="col">Cliente</th>
                                        <th scope="col">Total Compras</th>
                                        <th scope="col">Total Gasto (Bs)</th>
                                        <th scope="col">Clasificación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($clientes)): ?>
                                        <?php 
                                            $number = 1; 
                                            $clasificaciones = ['Fiel' => 0, 'Frecuente' => 0, 'Regular' => 0, 'Poco Frecuente' => 0];
                                            foreach ($clientes as $cliente): 
                                                $clasificaciones[$cliente->clasificacion]++;
                                        ?>
                                            <tr>
                                                <td><?= $number++; ?></td>
                                                <td><?= $cliente->nombre; ?></td>
                                                <td><?= $cliente->total_compras; ?></td>
                                                <td>Bs. <?= number_format($cliente->total_gasto, 2); ?></td>
                                                <td><?= $cliente->clasificacion; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5">No hay datos disponibles.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Gráfico de Torta -->
    <div class="card mt-12 col-md-6">
        <div class="card-body">
            <h5>Distribución de Clientes por Clasificación</h5>
            <canvas id="graficoClientes"></canvas>
        </div>
    </div>
    </div>
</div>

<!-- Agregar Script para Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Obtener datos de PHP
    const clasificaciones = <?= json_encode($clasificaciones ?? []); ?>;

    // Configurar los datos para el gráfico
    const data = {
        labels: Object.keys(clasificaciones), // Categorías
        datasets: [{
            label: 'Distribución de Clientes',
            data: Object.values(clasificaciones), // Valores de las categorías
            backgroundColor: [
                '#4CAF50', // Verde
                '#FFC107', // Amarillo
                '#2196F3', // Azul
                '#F44336'  // Rojo
            ],
            hoverOffset: 4
        }]
    };

    // Configuración del gráfico
    const config = {
        type: 'pie',
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let total = context.dataset.data.reduce((a, b) => a + b, 0);
                            let value = context.raw;
                            let percentage = ((value / total) * 100).toFixed(2);
                            return `${context.label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    };

    // Renderizar el gráfico
    const graficoClientes = new Chart(
        document.getElementById('graficoClientes'),
        config
    );
    
    function generarPDF() {
    // Obtener el gráfico como imagen base64
    const canvas = document.getElementById('graficoClientes');
    const imgData = canvas.toDataURL('image/png');

    // Enviar la imagen y los datos de la tabla al servidor
    fetch('<?= base_url("reporte/imprimir_pdf") ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            imagen: imgData, // Imagen base64
            clientes: <?= json_encode($clientes); ?> // Datos de la tabla
        })
    }).then(response => response.blob())
      .then(blob => {
          const url = URL.createObjectURL(blob);
          window.open(url, '_blank');
      });
    }
</script>
