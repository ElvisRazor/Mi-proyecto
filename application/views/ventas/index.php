<div class="content-body">
    <div class="container-fluid">
        <?php if ($this->session->flashdata('success')): ?>
            <script>
                toastr.success('<?= $this->session->flashdata('success'); ?>', 'Éxito', {
                    "positionClass": "toast-top-center",  // Mostrar en el centro
                    "closeButton": true,
                    "timeOut": 5000,  // Desaparece después de 5 segundos
                    "progressBar": true
                });
            </script>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <script>
                toastr.error('<?= $this->session->flashdata('error'); ?>', 'Error', {
                    "positionClass": "toast-top-center",  // Mostrar en el centro
                    "closeButton": true,
                    "timeOut": 5000,  // Desaparece después de 5 segundos
                    "progressBar": true
                });
            </script>
        <?php endif; ?>
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>VENTAS</h4>
                    <p class="mb-0">Lista de Ventas</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= site_url('ventas/agregar') ?>">Realizar Nueva Venta</a></li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Lista de Ventas</h4>
                        <a href="<?= site_url('ventas/imprimirTodas') ?>" class="btn btn-primary btn-sm" target="_blank">
                            <i class="fa fa-print"></i> Imprimir Todas las Ventas
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example3" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th><strong>N°</strong></th>
                                        <th><strong>Cliente</strong></th>
                                        <th><strong>Producto</strong></th>
                                        <th><strong>Número Comprobante</strong></th>
                                        <th><strong>SubTotal</strong></th>
                                        <th><strong>Descuento</strong></th>
                                        <th><strong>Total Venta</strong></th>
                                        <th><strong>Estado</strong></th>
                                        <th><strong>Imprimir</strong></th>
                                        <th><strong>Eliminar</strong></th> <!-- Columna para eliminar -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($venta as $item): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($item['idVenta']) ?></td>
                                            <td><?= htmlspecialchars($item['nombre_cliente']) ?></td>
                                            <td><?= htmlspecialchars($item['nombre_producto']) ?></td>
                                            <td><?= htmlspecialchars($item['numComprobante']) ?></td>
                                            <td>Bs.<?= htmlspecialchars($item['subTotalVenta']) ?></td>
                                            <td>Bs.<?= htmlspecialchars($item['descuento']) ?></td>
                                            <td>Bs.<?= htmlspecialchars($item['totalVenta']) ?></td>
                                            <td>
                                                <?= ($item['estado'] == 1) ? 'Activo' : 'Inactivo' ?>
                                            </td>
                                            <td>
                                                <a href="<?= site_url('ventas/imprimir/'.$item['idVenta']) ?>" class="btn btn-primary btn-sm" target="_blank">
                                                    <i class="fa fa-print"></i> Imprimir
                                                </a>
                                            </td>
                                            <td>
                                                <!-- Ícono de eliminar en rojo con confirmación -->
                                                <a href="javascript:void(0);" onclick="showConfirmationMessage('<?= site_url('ventas/eliminar/' . $item['idVenta']); ?>')" class="btn btn-danger btn-sm">
                                                    <i class="fa fa-trash"></i> Eliminar
                                                </a>
                                                <script type="text/javascript"> 
                                                    // Función para mostrar el mensaje de confirmación y ejecutar la eliminación si el usuario acepta
                                                    function showConfirmationMessage(deleteUrl) {
                                                        // Mostrar el mensaje flotante usando Toastr
                                                        toastr.warning('¿Estás seguro de que deseas eliminar esta venta?', 'Confirmar eliminación', {
                                                            "positionClass": "toast-top-center", // Posición en el centro de la pantalla
                                                            "closeButton": true,                // Botón de cerrar
                                                            "timeOut": 6000,                    // Tiempo en milisegundos (6 segundos)
                                                            "progressBar": true,                // Barra de progreso
                                                            "onclick": function() {
                                                                // Crear un cuadro de confirmación directamente con botones de "Sí" y "No"
                                                                var confirmAction = confirm('¿Estás seguro de que deseas eliminar esta venta?');
                                                                
                                                                // Si el usuario confirma la acción
                                                                if (confirmAction) {
                                                                    // Si el usuario confirma, redirigir a la URL para eliminar
                                                                    window.location.href = deleteUrl;
                                                                } else {
                                                                    // Si el usuario cancela, solo cerrar el mensaje
                                                                    toastr.clear();
                                                                }
                                                            }
                                                        });
                                                    }
                                                </script>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    /* Aumentar el tamaño de los mensajes de Toastr */
    .toast {
        font-size: 20px !important;  /* Aumentar el tamaño de la fuente */
        padding: 35px !important;    /* Aumentar el espacio alrededor del texto */
        width: 350px !important;     /* Aumentar el ancho horizontal del mensaje */
        max-width: 70% !important;  /* Asegurar que el ancho no supere el contenedor */
    }

    /* Aumentar el tamaño del título */
    .toast-title {
        font-size: 20px !important;  /* Título más grande */
    }

    /* Aumentar el tamaño del mensaje */
    .toast-message {
        font-size: 20px !important;  /* Mensaje más grande */
    }

    /* Estilo para los botones con el ícono de eliminar */
    .btn-danger {
        color: white;
        background-color: red;
        border-color: red;
    }

    .btn-danger:hover {
        background-color: darkred;
        border-color: darkred;
    }
</style>