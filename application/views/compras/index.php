<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Compras</h4>
                    <p class="mb-0">Lista de Compras Activas</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= site_url('compras/agregar') ?>">Agregar Nueva</a></li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Lista de Compras</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example3" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th><strong>Proveedor</strong></th>
                                        <th><strong>Usuario</strong></th>
                                        <th><strong>Tipo Comprobante</strong></th>
                                        <th><strong>Serie Comprobante</strong></th>
                                        <th><strong>Número Comprobante</strong></th>
                                        <th><strong>Impuesto</strong></th>
                                        <th><strong>Total Compra</strong></th>
                                        <th><strong>Estado</strong></th>
                                        <th><strong>Acciones</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($compra as $compra): ?>
                                        <tr>
                                            <td><?= $compra['idProveedor'] ?></td>
                                            <td><?= $compra['idUsuario'] ?></td>
                                            <td><?= $compra['tipoComprobante'] ?></td>
                                            <td><?= $compra['serieComprobante'] ?></td>
                                            <td><?= $compra['numComprobante'] ?></td>
                                            <td><?= $compra['impuesto'] ?></td>
                                            <td><?= $compra['totalCompra'] ?></td>
                                            <td>
                                                <?php if ($compra['estado'] == 1): ?>
                                                    Activo
                                                <?php else: ?>
                                                    Inactivo
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="<?= site_url('compras/editar/'.$compra['idCompra']) ?>" class="btn btn-info btn-sm">Editar</a>
                                                <a href="<?= site_url('compras/eliminar/'.$compra['idCompra']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta compra?');">Eliminar</a>
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
