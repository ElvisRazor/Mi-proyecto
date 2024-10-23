<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>COMPRAS</h4>
                    <p class="mb-0">Lista de Compras</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= site_url('compras/agregar') ?>">Realizar Nueva Compra</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('compras/consulta') ?>">Realizar Consulta</a></li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Lista de Compras</h4>
                        <a href="<?= site_url('compras/imprimir_todas') ?>" class="btn btn-primary btn-sm" target="_blank">
                            <i class="fa fa-print"></i> Imprimir Todas las Compras
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example3" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th><strong>Proveedor</strong></th>
                                        <th><strong>Producto</strong></th>
                                        <th><strong>Número Comprobante</strong></th>
                                        <th><strong>Total Compra</strong></th>
                                        <th><strong>Estado</strong></th>
                                        <th><strong>Imprimir</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($compra as $item): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($item['nombre_proveedor']) ?></td>
                                            <td><?= htmlspecialchars($item['nombre_producto']) ?></td>
                                            <td><?= htmlspecialchars($item['numComprobante']) ?></td>
                                            <td><?= htmlspecialchars($item['totalCompra']) ?></td>
                                            <td>
                                                <?= ($item['estado'] == 1) ? 'Activo' : 'Inactivo' ?>
                                            </td>
                                            <td>
                                                <a href="<?= site_url('compras/imprimir/'.$item['idCompra']) ?>" class="btn btn-primary btn-sm" target="_blank">
                                                    <i class="fa fa-print"></i> Imprimir
                                                </a>
                                                <!--<a href="<?= site_url('compras/editar/'.$item['idCompra']) ?>" class="btn btn-info btn-sm">Editar</a>
                                                <a href="<?= site_url('compras/eliminar/'.$item['idCompra']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta Compra?');">Eliminar</a>-->
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
