<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>COMPRAS</h4>
                    <p class="mb-0">Consulta de Compras</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= site_url('compras/agregar') ?>">Realizar Nueva Compra</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('compras') ?>">Lista de Compras</a></li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Consulta de Compras por Fechas</h4>
                    </div>
                    <div class="card-body">
                        <?= form_open('compras/consulta', ['id' => 'form-consulta']); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="fecha_inicio">Fecha Inicio:</label>
                                <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="fecha_fin">Fecha Fin:</label>
                                <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" required>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" name="submit" class="btn btn-primary">Consultar</button>
                        </div>
                        <?= form_close(); ?>

                        <div id="resultado" class="mt-4">
                            <?php var_dump($compra); ?>
                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger"><?= $error; ?></div>
                            <?php endif; ?>

                            <?php if (!empty($compras)): ?>
                                <table class="table table-striped mt-3">
                                    <thead>
                                        <tr>
                                            <th>ID Compra</th>
                                            <th>Fecha Registro</th>
                                            <th>Proveedor</th>
                                            <th>Cantidad Productos</th>
                                            <th>Total Compra</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($compra as $compra): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($compra['idCompra']) ?></td>
                                                <td><?= htmlspecialchars($compra['fechaRegistro']) ?></td>
                                                <td><?= htmlspecialchars($compra['proveedor']) ?></td>
                                                <td><?= htmlspecialchars($compra['cantidad_productos']) ?></td>
                                                <td><?= number_format($compra['total_compra'], 2) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
