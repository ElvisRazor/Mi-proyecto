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
                                <div class="form-group col-md-12">
                                    <label for="clienteSeleccionado">Cliente Seleccionado</label>
                                    <label id="clienteSeleccionado" class="form-control" style="background-color: #f8f9fa;">Ninguno</label>
                                    <button type="button" class="btn btn-primary mt-2" data-toggle="modal" data-target="#modalClientes">
                                        Buscar Cliente
                                    </button>
                                    <input type="hidden" id="idCliente" name="idCliente" required>
                                    <?= form_error('idCliente') ?>
                                </div>
                            </div>
                            <!-- Modal de clientes -->
                            <div class="modal fade" id="modalClientes" tabindex="-1" role="dialog" aria-labelledby="modalClientesLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalClientesLabel">Seleccionar Cliente</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="text" id="buscarClienteModal" placeholder="Buscar Cliente" onkeyup="filtrarClientes()" class="form-control mb-2">
                                            <table class="table table-bordered" id="tablaClientes">
                                                <thead>
                                                    <tr>
                                                        <th>Cliente</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($cliente as $item): ?>
                                                        <tr data-id="<?= $item['idCliente'] ?>">
                                                            <td><?= htmlspecialchars($item['nombre']) ?></td>
                                                            <td>
                                                                <button class="btn btn-primary btn-sm" onclick="seleccionarCliente(<?= $item['idCliente'] ?>, '<?= htmlspecialchars($item['nombre']) ?>')">Seleccionar</button>
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
                                    // Verificar si el producto ya fue agregado
                                    if (productosAgregados.has(id)) {
                                        alert("El producto ya ha sido agregado.");
                                        return;
                                    }
                                    // Agregar fila de producto seleccionado a la tabla de venta
                                    let detallesVenta = document.getElementById('detallesVenta').getElementsByTagName('tbody')[0];
                                    let nuevaFila = `<tr data-id="${id}">
                                        <td><input type="hidden" name="producto[]" value="${id}">${nombre}</td>
                                        <td>${stock}</td>
                                        <td><input type="number" name="cantidad[]" value="0" min="1" max="${stock}" class="form-control" onchange="calcularSubtotal(this)"></td>
                                        <td><label name="precio[]">${precio}</label></td>
                                        <td><input type="number" name="descuento[]" value="0" min="0" step="0.01" class="form-control" onchange="calcularSubtotal(this)" placeholder="Descuento por unidad"></td>
                                        <td><label name="subtotal[]">0</label></td>
                                        <td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarProducto(this)">Eliminar</button></td>
                                    </tr>`;
                                    detallesVenta.insertAdjacentHTML('beforeend', nuevaFila);
                                    productosAgregados.add(id); // Agregar el producto al conjunto
                                    $('#modalProductos').modal('hide'); // Cerrar el modal
                                    calcularTotalVenta(); // Recalcular el total de la venta
                                }

                                function seleccionarCliente(id, nombre) {
                                    document.getElementById('idCliente').value = id;
                                    document.getElementById('clienteSeleccionado').innerText = nombre; // Actualizar el label con el nombre del cliente
                                    $('#modalClientes').modal('hide');
                                }

                                function filtrarProductos() {
                                    const input = document.getElementById('buscarProducto');
                                    const filter = input.value.toLowerCase();
                                    const rows = document.querySelectorAll('#tablaProductos tbody tr');
                                    rows.forEach(row => {
                                        const productName = row.cells[0].innerText.toLowerCase();
                                        if (productName.includes(filter)) {
                                            row.style.display = ''; // Mostrar fila
                                        } else {
                                            row.style.display = 'none'; // Ocultar fila
                                        }
                                    });
                                }

                                function filtrarClientes() {
                                    const input = document.getElementById('buscarClienteModal');
                                    const filter = input.value.toLowerCase();
                                    const rows = document.querySelectorAll('#tablaClientes tbody tr');
                                    rows.forEach(row => {
                                        const clienteName = row.cells[0].innerText.toLowerCase();
                                        if (clienteName.includes(filter)) {
                                            row.style.display = ''; // Mostrar fila
                                        } else {
                                            row.style.display = 'none'; // Ocultar fila
                                        }
                                    });
                                }

                                function calcularSubtotal(element) {
                                    const row = element.closest('tr');
                                    const cantidad = parseFloat(row.querySelector('input[name="cantidad[]"]').value);
                                    const precio = parseFloat(row.querySelector('label[name="precio[]"]').innerText);
                                    const descuento = parseFloat(row.querySelector('input[name="descuento[]"]').value) || 0;

                                    const subtotal = cantidad * precio; // Cálculo del subtotal sin descuento
                                    row.querySelector('label[name="subtotal[]"]').innerText = subtotal > 0 ? subtotal.toFixed(2) : 0; // Asegurarse de que el subtotal no sea negativo

                                    calcularTotalVenta(); // Actualiza el total
                                }

                                function calcularTotalVenta() {
                                    let total = 0;
                                    let totalDescuento = 0;
                                    const subtotales = document.querySelectorAll('label[name="subtotal[]"]');
                                    const cantidades = document.querySelectorAll('input[name="cantidad[]"]');
                                    const descuentos = document.querySelectorAll('input[name="descuento[]"]');

                                    subtotales.forEach((subtotalLabel, index) => {
                                        const subtotal = parseFloat(subtotalLabel.innerText) || 0;
                                        const cantidad = parseFloat(cantidades[index].value) || 0;
                                        const descuento = parseFloat(descuentos[index].value) || 0;
                                        total += subtotal;
                                        totalDescuento += descuento * cantidad; // Sumar el descuento total
                                    });

                                    document.getElementById('totalVenta').innerText = (total - totalDescuento).toFixed(2); // Total final menos descuentos
                                }

                                function eliminarProducto(btn) {
                                    const row = btn.closest('tr');
                                    const id = row.getAttribute('data-id');
                                    productosAgregados.delete(id); // Eliminar el producto del conjunto
                                    row.remove();
                                    calcularTotalVenta(); // Recalcular el total de la venta
                                }
                            </script>
                            <table class="table table-bordered" id="detallesVenta">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Stock</th>
                                        <th>Cantidad</th>
                                        <th>Precio</th>
                                        <th>Descuento Unitario</th>
                                        <th>Subtotal</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Las filas de productos se agregarán aquí dinámicamente -->
                                </tbody>
                            </table>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label>Total Venta</label>
                                    <label id="totalVenta">0.00</label>
                                </div>
                            </div>
                            <button type="button" class="btn btn-danger" onclick="cancelarVenta()">Cancelar Venta</button>
                            <button type="submit" class="btn btn-success">Guardar Venta</button>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function cancelarVenta() {
        // Limpiar el formulario de venta
        document.getElementById('idCliente').value = '';
        document.getElementById('clienteSeleccionado').innerText = 'Ninguno';
        document.getElementById('detallesVenta').getElementsByTagName('tbody')[0].innerHTML = ''; // Limpiar productos
        calcularTotalVenta(); // Recalcular total
    }
</script>