<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Ventas</h4>
                    <p class="mb-0">Agregar Nueva Venta</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= site_url('ventas') ?>">Lista de Ventas</a></li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Formulario de Venta</h4>
                    </div>
                    <div class="card-body">
                        <?= form_open_multipart('ventas/agregar') ?>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="idCliente">Cliente</label>
                                    <select class="form-control" id="idCliente" name="idCliente" required>
                                        <?php foreach ($cliente as $item): ?>
                                            <option value="<?= htmlspecialchars($item['idCliente']) ?>"><?= htmlspecialchars($item['nombre']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?= form_error('idCliente') ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="fecha">Fecha(*)</label>
                                    <input type="date" class="form-control" id="fecha" name="fecha" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="tipoComprobante">Tipo Comprobante(*)</label>
                                    <select class="form-control" id="tipoComprobante" name="tipoComprobante" required>
                                        <option value="Factura">Recibo</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="numComprobante">Número Comprobante</label>
                                    <input type="text" class="form-control" id="numComprobante" name="numComprobante" placeholder="Número" required>
                                </div>
                            </div>

                            <!-- Botón para abrir el modal de productos -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalProductos">
                                + Agregar Producto
                            </button>

                            <!-- Modal de productos -->
                            <div class="modal fade" id="modalProductos" tabindex="-1" role="dialog" aria-labelledby="modalProductosLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalProductosLabel">Seleccionar Producto</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <table class="table table-bordered" id="tablaProductos">
                                                <thead>
                                                    <tr>
                                                        <th>Producto</th>
                                                        <th>Stock</th>
                                                        <th>Precio</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($producto as $item): ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($item['nombre']) ?></td>
                                                            <td><?= htmlspecialchars($item['stock']) ?></td>
                                                            <td><?= htmlspecialchars($item['precio']) ?></td>
                                                            <td>
                                                                <button class="btn btn-primary btn-sm" onclick="seleccionarProducto(<?= $item['idProducto'] ?>, '<?= htmlspecialchars($item['nombre']) ?>', <?= $item['stock'] ?>, <?= $item['precio'] ?>)">Seleccionar</button>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <script>
                                function seleccionarProducto(id, nombre, stock, precio) {
                                    // Agregar fila de producto seleccionado a la tabla de venta
                                    let detallesVenta = document.getElementById('detallesVenta');
                                    let nuevaFila = `<tr>
                                        <td><input type="hidden" name="producto[]" value="${id}">${nombre}</td>
                                        <td>${stock}</td>
                                        <td><input type="number" name="cantidad[]" value="0" min="1" max="${stock}" class="form-control" onchange="calcularSubtotal(this)"></td>
                                        <td><input name="precio[]" value="${precio}" class="form-control" onchange="calcularSubtotal(this)"></td>
                                        <td><input type="number" name="subtotal[]" value="${precio}" class="form-control" readonly></td>
                                    </tr>`;
                                    detallesVenta.insertAdjacentHTML('beforeend', nuevaFila);
                                    $('#modalProductos').modal('hide'); // Cerrar el modal
                                }

                                function calcularSubtotal(element) {
                                    const row = element.closest('tr');
                                    const cantidad = row.querySelector('input[name="cantidad[]"]').value;
                                    const precio = row.querySelector('input[name="precio[]"]').value;
                                    const subtotal = cantidad * precio;
                                    row.querySelector('input[name="subtotal[]"]').value = subtotal;

                                    calcularTotalVenta();
                                }

                                function calcularTotalVenta() {
                                    let total = 0;
                                    const subtotales = document.querySelectorAll('input[name="subtotal[]"]');
                                    subtotales.forEach(subtotal => {
                                        total += parseFloat(subtotal.value) || 0;
                                    });
                                    document.getElementById('totalVenta').value = total;
                                }
                            </script>

                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="detallesVenta">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Stock</th>
                                            <th>Cantidad</th>
                                            <th>Precio</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="totalVenta">Total Venta</label>
                                    <input type="text" class="form-control" id="totalVenta" name="totalVenta" readonly>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success">Guardar Venta</button>
                            <a href="<?= site_url('ventas') ?>" class="btn btn-danger">Cancelar</a>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
