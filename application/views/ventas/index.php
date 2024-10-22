<div class="content-body">
    <div class="container-fluid">
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
                                        <th><strong>Cliente</strong></th>
                                        <th><strong>Producto</strong></th>
                                        <th><strong>Número Comprobante</strong></th>
                                        <th><strong>SubTotal</strong></th>
                                        <th><strong>Descuento</strong></th>
                                        <th><strong>Total Venta</strong></th>
                                        <th><strong>Estado</strong></th>
                                        <th><strong>Imprimir</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($venta as $item): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($item['nombre_cliente']) ?></td>
                                            <td><?= htmlspecialchars($item['nombre_producto']) ?></td>
                                            <td><?= htmlspecialchars($item['numComprobante']) ?></td>
                                            <td><?= htmlspecialchars($item['subTotalVenta']) ?></td>
                                            <td><?= htmlspecialchars($item['descuento']) ?></td>
                                            <td><?= htmlspecialchars($item['totalVenta']) ?></td>
                                            <td>
                                                <?= ($item['estado'] == 1) ? 'Activo' : 'Inactivo' ?>
                                            </td>
                                            <td>
                                            <a href="<?= site_url('ventas/imprimir/'.$item['idVenta']) ?>" class="btn btn-primary btn-sm" target="_blank">
                                                <i class="fa fa-print"></i> Imprimir
                                            </a>
                                            <!--<a href="<?= site_url('ventas/editar/'.$item['idVenta']) ?>" class="btn btn-info btn-sm">Editar</a>
                                                <a href="<?= site_url('ventas/eliminar/'.$item['idVenta']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta Venta?');">Eliminar</a>-->
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
