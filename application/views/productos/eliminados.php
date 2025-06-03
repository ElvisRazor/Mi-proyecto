<div class="content-body">
    <div class="container-fluid">
        <!-- Mostrar mensaje de éxito si existe -->
        <?php if ($this->session->flashdata('mensaje')): ?>
            <script type="text/javascript">
                // Mostrar el mensaje flotante con Toastr
                toastr.success('<?php echo $this->session->flashdata('mensaje'); ?>', '¡Éxito!', {
                    "positionClass": "toast-top-center", // Posición en la parte superior derecha
                    "closeButton": true,               // Botón de cerrar
                    "timeOut": 2000,                   // Tiempo en milisegundos (2 segundos)
                    "progressBar": true,               // Barra de progreso
                });
            </script>
        <?php endif; ?>
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Productos Inactivos</h4>
                    <p class="mb-0">Lista de Productos Inactivos</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= site_url('productos') ?>">Productos Activos</a></li>
                    <li class="breadcrumb-item active">Productos Inactivos</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Lista de Productos Inactivos</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example3" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th><strong>Nombre</strong></th>
                                        <th><strong>Código</strong></th>
                                        <th><strong>Precio Compra</strong></th>
                                        <th><strong>Precio Venta</strong></th>
                                        <th><strong>Stock</strong></th>
                                        <th><strong>Descripción</strong></th>
                                        <th><strong>Imagen</strong></th>
                                        <th><strong>Estado</strong></th>
                                        <th><strong>Acciones</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($producto as $producto): ?>
                                        <tr>
                                            <td><?= $producto['nombre'] ?></td>
                                            <td><?= $producto['codigo'] ?></td>
                                            <td>Bs.<?= number_format($producto['precioCompra'], 2) ?></td>
                                            <td>Bs.<?= number_format($producto['precioVenta'], 2) ?></td>
                                            <td><?= $producto['stock'] ?></td>
                                            <td><?= $producto['descripcion'] ?></td>
                                            <td>
                                                <img src="<?= base_url('uploads/productos/' . $producto['imagen']) ?>" alt="Imagen del producto" width="50">
                                            </td>
                                            <td><?= $producto['estado'] == 1 ? 'Activo' : 'Inactivo' ?></td>
                                            <td>
                                                <a href="<?= site_url('productos/habilitar/'.$producto['idProducto']) ?>" class="btn btn-success btn-sm">Habilitar</a>
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