<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Editar Compra</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Formulario de Edición de Compra</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger">
                                <?= $this->session->flashdata('error') ?>
                            </div>
                        <?php endif; ?>
                        <?= form_open('compras/editar/'.$compra['idCompra']) ?>
                            <div class="form-group">
                                <label for="idProveedor">Proveedor</label>
                                <?= form_input(['name' => 'idProveedor', 'id' => 'idProveedor', 'class' => 'form-control', 'value' => set_value('idProveedor', $compra['idProveedor'])]) ?>
                            </div>
                            <div class="form-group">
                                <label for="idUsuario">Usuario</label>
                                <?= form_input(['name' => 'idUsuario', 'id' => 'idUsuario', 'class' => 'form-control', 'value' => set_value('idUsuario', $compra['idUsuario'])]) ?>
                            </div>
                            <div class="form-group">
                                <label for="tipoComprobante">Tipo de Comprobante</label>
                                <?= form_input(['name' => 'tipoComprobante', 'id' => 'tipoComprobante', 'class' => 'form-control', 'value' => set_value('tipoComprobante', $compra['tipoComprobante']), 'readonly' => 'readonly']) ?>
                            </div>
                            <div class="form-group">
                                <label for="serieComprobante">Serie de Comprobante</label>
                                <?= form_input(['name' => 'serieComprobante', 'id' => 'serieComprobante', 'class' => 'form-control', 'value' => set_value('serieComprobante', $compra['serieComprobante'])]) ?>
                            </div>
                            <div class="form-group">
                                <label for="numComprobante">Número de Comprobante</label>
                                <?= form_input(['name' => 'numComprobante', 'id' => 'numComprobante', 'class' => 'form-control', 'value' => set_value('numComprobante', $compra['numComprobante'])]) ?>
                            </div>
                            <div class="form-group">
                                <label for="impuesto">Impuesto</label>
                                <?= form_input(['name' => 'impuesto', 'id' => 'impuesto', 'class' => 'form-control', 'value' => set_value('impuesto', $compra['impuesto'])]) ?>
                            </div>
                            <div class="form-group">
                                <label for="totalCompra">Total Compra</label>
                                <?= form_input(['name' => 'totalCompra', 'id' => 'totalCompra', 'class' => 'form-control', 'value' => set_value('totalCompra', $compra['totalCompra'])]) ?>
                            </div>
                            <button type="submit" class="btn btn-primary">Actualizar Compra</button>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
