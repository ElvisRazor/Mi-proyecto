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
                        <?= form_open('compras/editar/' . $compra['idCompra']) ?>

                        <div class="form-group">
                            <label for="idProveedor">Proveedor</label>
                            <?= form_dropdown(
                                'idProveedor',
                                array_column($proveedores, 'nombre', 'idProveedor'),
                                set_value('idProveedor', $compra['idProveedor']),
                                ['class' => 'form-control', 'id' => 'idProveedor', 'required' => 'required']
                            ) ?>
                        </div>

                        <div class="form-group">
                            <label for="numComprobante">Número de Comprobante</label>
                            <?= form_input([
                                'name' => 'numComprobante',
                                'id' => 'numComprobante',
                                'class' => 'form-control',
                                'value' => set_value('numComprobante', $compra['numComprobante']),
                                'readonly' => 'readonly',
                            ]) ?>
                        </div>

                        <div class="form-group">
                            <label for="productos">Productos</label>
                            <table class="table" id="productosTable">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio Compra</th>
                                        <th>Total</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($productos as $producto): ?>
                                        <tr>
                                            <td>
                                                <label class="form-control"><?= $producto['nombre_producto'] ?></label>
                                                <input type="hidden" name="producto[]" value="<?= $producto['idProducto'] ?>">
                                            </td>
                                            <td>
                                                <input type="number" name="cantidad[]" value="<?= $producto['cantidad'] ?>" class="form-control" required>
                                            </td>
                                            <td>
                                                <input type="number" name="precioCompra[]" value="<?= $producto['precioCompra'] ?>" class="form-control" readonly>
                                            </td>
                                            <td>
                                                <span class="total"><?= number_format($producto['cantidad'] * $producto['precioCompra'], 2) ?></span>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm" onclick="eliminarFila(this)">Eliminar</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="form-group">
                            <label for="totalCompra">Total Compra</label>
                            <?= form_input([
                                'name' => 'totalCompra',
                                'id' => 'totalCompra',
                                'class' => 'form-control',
                                'value' => set_value('totalCompra', $compra['totalCompra']),
                                'readonly' => 'readonly',
                            ]) ?>
                        </div>

                        <button type="submit" class="btn btn-primary">Actualizar Compra</button>
                        <a href="<?= site_url('compras') ?>" class="btn btn-secondary">Cancelar</a>

                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function eliminarFila(button) {
        let row = button.closest('tr');
        row.remove();
        calcularTotal();
    }

    function calcularTotal() {
        let total = 0;
        let rows = document.getElementById('productosTable').getElementsByTagName('tbody')[0].rows;

        for (let row of rows) {
            let cantidad = row.querySelector('input[name="cantidad[]"]').value;
            let precioCompra = row.querySelector('input[name="precioCompra[]"]').value;
            let totalCelda = row.querySelector('.total');

            totalCelda.textContent = (cantidad * precio).toFixed(2);
            total += cantidad * precio;
        }

        document.getElementById('totalCompra').value = total.toFixed(2);
    }

    // Actualizar total al cambiar la cantidad o el precio
    document.querySelectorAll('input[name="cantidad[]"], input[name="precioCompra[]"]').forEach(input => {
        input.addEventListener('input', calcularTotal);
    });

    calcularTotal();
</script>
