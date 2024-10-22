<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Compras</h4>
                    <p class="mb-0">Agregar Nueva Compra</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= site_url('compras') ?>">Lista de Compras</a></li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Formulario de Compra</h4>
                    </div>
                    <div class="card-body">
                        <?= form_open_multipart('compras/agregar') ?>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="idProveedor">Proveedor</label>
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalProveedores">
                                        Buscar Proveedor
                                    </button>
                                    <select class="form-control mt-2" id="idProveedor" name="idProveedor" required>
                                        <?php foreach ($proveedor as $item): ?>
                                            <option value="<?= htmlspecialchars($item['idProveedor']) ?>"><?= htmlspecialchars($item['nombre']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?= form_error('idProveedor') ?>
                                </div>
                            </div>
                            <!-- Modal de proveedores -->
                            <div class="modal fade" id="modalProveedores" tabindex="-1" role="dialog" aria-labelledby="modalProveedoresLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalProveedoresLabel">Seleccionar Proveedor</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="text" id="buscarProveedorModal" placeholder="Buscar Proveedor" onkeyup="filtrarProveedores()" class="form-control mb-2">
                                            <table class="table table-bordered" id="tablaProveedores">
                                                <thead>
                                                    <tr>
                                                        <th>Proveedor</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($proveedor as $item): ?>
                                                        <tr data-id="<?= $item['idProveedor'] ?>">
                                                            <td><?= htmlspecialchars($item['nombre']) ?></td>
                                                            <td>
                                                                <button class="btn btn-primary btn-sm" onclick="seleccionarProveedor(<?= $item['idProveedor'] ?>, '<?= htmlspecialchars($item['nombre']) ?>')">Seleccionar</button>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
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
                                            <input type="text" id="buscarProducto" placeholder="Buscar Producto" onkeyup="filtrarProductos()" class="form-control mb-2">
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
                                                        <tr data-id="<?= $item['idProducto'] ?>">
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
                                const productosAgregados = new Set(); // Para almacenar los productos agregados

                                function seleccionarProducto(id, nombre, stock, precio) {
                                    if (productosAgregados.has(id)) {
                                        alert("El producto ya ha sido agregado.");
                                        return;
                                    }
                                    let detallesCompra = document.getElementById('detallesCompra').getElementsByTagName('tbody')[0];
                                    let nuevaFila = `<tr data-id="${id}">
                                        <td><input type="hidden" name="producto[]" value="${id}">${nombre}</td>
                                        <td>${stock}</td>
                                        <td><input type="number" name="cantidad[]" value="0" min="1" class="form-control" onchange="calcularTotalCompra(this)"></td>
                                        <td><label name="precio[]">${precio}</label></td>
                                        <td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarProducto(this)">Eliminar</button></td>
                                    </tr>`;
                                    detallesCompra.insertAdjacentHTML('beforeend', nuevaFila);
                                    productosAgregados.add(id); // Agregar el producto al conjunto
                                    $('#modalProductos').modal('hide'); // Cerrar el modal
                                }

                                function seleccionarProveedor(id, nombre) {
                                    document.getElementById('idProveedor').value = id;
                                    document.getElementById('idProveedor').style.display = 'block';
                                    $('#modalProveedores').modal('hide');
                                }

                                function calcularTotalCompra() {
                                    let total = 0;
                                    const cantidades = document.querySelectorAll('input[name="cantidad[]"]');
                                    const precios = document.querySelectorAll('label[name="precio[]"]');

                                    cantidades.forEach((cantidad, index) => {
                                        const cantidadValue = parseFloat(cantidad.value);
                                        const precioValue = parseFloat(precios[index].innerText);

                                        total += cantidadValue * precioValue;
                                    });

                                    document.getElementById('totalCompra').innerText = total.toFixed(2);
                                    document.getElementById('totalCompraInput').value = total.toFixed(2); // Valor total
                                }

                                function eliminarProducto(button) {
                                    const row = button.closest('tr');
                                    const idProducto = row.dataset.id;
                                    productosAgregados.delete(parseInt(idProducto)); // Eliminar el producto del conjunto
                                    row.remove();
                                    calcularTotalCompra(); // Recalcular el total de la compra
                                }
                            </script>

                            <br>
                            <table id="detallesCompra" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Stock</th>
                                        <th>Cantidad</th>
                                        <th>Precio</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Aquí se agregarán las filas de productos seleccionados -->
                                </tbody>
                            </table>
                            <div class="form-group">
                                <label for="totalCompra">Total Compra</label>
                                <input type="hidden" class="form-control" id="totalCompraInput" name="totalCompra" value="0.00" readonly>
                                <label id="totalCompra" class="form-control" readonly>0.00</label>
                                <?= form_error('totalCompra') ?>
                            </div>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>