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
                        <form action="<?= site_url('reporte/consultaCompra') ?>" method="post">
                            <div class="form-group">
                                <label for="fechaInicio">Fecha Inicio:</label>
                                <input type="date" id="fechaInicio" name="fechaInicio" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="fechaFin">Fecha Fin:</label>
                                <input type="date" id="fechaFin" name="fechaFin" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-success">Consultar Compras</button>
                        </form>
                        <?php if (isset($compras)): ?>
                            <h5>Resultados de la Consulta:</h5>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID Compra</th>
                                        <th>Proveedor</th>
                                        <th>Fecha</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($compras as $compra): ?>
                                        <tr>
                                            <td><?= $compra['idCompra'] ?></td>
                                            <td><?= $compra['proveedor'] ?></td>
                                            <td><?= $compra['fechaRegistro'] ?></td>
                                            <td><?= $compra['totalCompra'] ?></td>
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
