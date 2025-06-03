<div class="content-body">
    <div class="container-fluid">
    <?php if ($this->session->flashdata('success')): ?>
    <script>
        toastr.success('<?= $this->session->flashdata('success'); ?>', 'Éxito', {
            "positionClass": "toast-top-center",  // Mostrar en el centro
            "closeButton": true,
            "timeOut": 5000,  // Desaparece después de 5 segundos
            "progressBar": true
        });
    </script>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <script>
        toastr.error('<?= $this->session->flashdata('error'); ?>', 'Error', {
            "positionClass": "toast-top-center",  // Mostrar en el centro
            "closeButton": true,
            "timeOut": 5000,  // Desaparece después de 5 segundos
            "progressBar": true
        });
    </script>
<?php endif; ?>
        <!-- Título de la página y breadcrumb -->
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
        <!-- Formulario para la venta -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                <?php if ($this->session->flashdata('mensaje')): ?>
                            <div class="alert alert-success">
                                <?php echo $this->session->flashdata('mensaje'); ?>
                            </div>
                        <?php elseif ($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger">
                                <?php echo $this->session->flashdata('error'); ?>
                            </div>
                        <?php endif; ?>
                    <div class="card-header">
                        <h4 class="card-title">Formulario de Venta</h4>
                        <button type="button" class="btn btn-success mt-2" data-toggle="modal" data-target="#modalAgregarCliente">Agregar Nuevo Cliente</button>
                    </div>
                    <!-- Modal para agregar un nuevo cliente -->
                    <div class="modal fade" id="modalAgregarCliente" tabindex="-1" role="dialog" aria-labelledby="modalAgregarClienteLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalAgregarClienteLabel">Agregar Cliente</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="<?= site_url('clientes/agregar') ?>" method="post">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12">
                                                    <div class="form-group">
                                                        <label for="tipoDocumento" class="form-label">Tipo Documento</label>
                                                        <select class="form-control" id="tipoDocumento" name="tipoDocumento" required>
                                                            <option value="">Seleccione tipo de documento</option>
                                                            <option value="Ci/Nit" <?= set_select('tipoDocumento', 'Ci/Nit') ?>>CI/NIT</option>
                                                            <option value="pasaporte" <?= set_select('tipoDocumento', 'pasaporte') ?>>Pasaporte</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="numDocumento" class="form-label">Número de Documento</label>
                                                        <input type="text" name="numDocumento" class="form-control" id="numDocumento" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="nombre" class="form-label">Nombre</label>
                                                        <input type="text" name="nombre" class="form-control" id="nombre" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="direccion" class="form-label">Dirección</label>
                                                        <input type="text" name="direccion" class="form-control" id="direccion" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="telefono" class="form-label">Teléfono</label>
                                                        <input type="text" name="telefono" class="form-control" id="telefono" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="email" class="form-label">Correo Electrónico</label>
                                                        <input type="email" name="email" class="form-control" id="email" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Agregar Cliente</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <div class="card-body">
                        <!-- Formulario principal de la venta -->
                        <?= form_open_multipart('ventas/agregar', ['id' => 'formVenta', 'onsubmit' => 'return guardarVenta(event);']) ?>
                        <div class="form-row">
                            <!-- Campo para el cliente seleccionado -->
                            <div class="form-group col-md-6">
                                <label for="clienteSeleccionado">Cliente Seleccionado</label>
                                <label id="clienteSeleccionado" class="form-control" style="background-color:">Ninguno</label>
                                <button type="button" class="btn btn-primary mt-2" data-toggle="modal" data-target="#modalClientes">Buscar Cliente</button>
                                <input type="hidden" id="idCliente" name="idCliente">
                                <?= form_error('idCliente') ?>
                            </div>
                        </div>
                        <!-- Modal de selección de clientes -->
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
                                                    <th>Número de Documento</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($cliente as $item): ?>
                                                    <tr data-id="<?= $item['idCliente'] ?>">
                                                        <td><?= htmlspecialchars($item['nombre']) ?></td>
                                                        <td><?= htmlspecialchars($item['numDocumento']) ?></td>
                                                        <td>
                                                            <button type="button" class="btn btn-primary btn-sm" onclick="seleccionarCliente(<?= $item['idCliente'] ?>, '<?= htmlspecialchars($item['nombre']) ?>')">Seleccionar</button>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Botón para agregar productos a la venta -->
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalProductos">
                            + Agregar Producto
                        </button>

                        <!-- Modal para selección de productos -->
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
                                                    <th>Código</th>
                                                    <th>Stock</th>
                                                    <th>Precio Venta</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($producto as $item): ?>
                                                    <tr data-id="<?= $item['idProducto'] ?>">
                                                        <td><?= htmlspecialchars($item['nombre']) ?></td>
                                                        <td><?= htmlspecialchars($item['codigo']) ?></td>
                                                        <td><?= htmlspecialchars($item['stock']) ?></td>
                                                        <td>Bs.<?= htmlspecialchars($item['precioVenta']) ?></td>
                                                        <td>
                                                            <button type="button" class="btn btn-primary btn-sm" onclick="seleccionarProducto(<?= $item['idProducto'] ?>, '<?= htmlspecialchars($item['nombre']) ?>', <?= $item['stock'] ?>, <?= $item['precioVenta'] ?>)">Seleccionar</button>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Tabla de detalles de productos agregados a la venta -->
                        <table class="table table-bordered" id="detallesVenta">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Stock</th>
                                    <th>Cantidad</th>
                                    <th>Precio Venta</th>
                                    <th>Descuento Unitario</th>
                                    <th>Subtotal</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Aquí se agregarán dinámicamente los productos seleccionados -->
                            </tbody>
                        </table>
                        <!-- Total de la venta -->
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Total Venta</label>
                                <label id="totalVenta">Bs. 0.00</label>
                            </div>
                        </div>
                        <!-- Botones para cancelar y guardar venta -->
                        <button type="button" class="btn btn-danger" onclick="cancelarVenta()">Cancelar Venta</button>
                        <button type="submit" class="btn btn-success">Guardar Venta</button>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Scripts JavaScript para manejar la lógica del formulario de venta -->
<style>
/* Aumentar el tamaño de los mensajes de Toastr */
.toast {
    font-size: 20px !important;  /* Aumentar el tamaño de la fuente */
    padding: 35px !important;    /* Aumentar el espacio alrededor del texto */
    width: 350px !important;     /* Aumentar el ancho horizontal del mensaje */
    max-width: 70% !important;  /* Asegurar que el ancho no supere el contenedor */
}

/* Aumentar el tamaño del título */
.toast-title {
    font-size: 20px !important;  /* Título más grande */
}

/* Aumentar el tamaño del mensaje */
.toast-message {
    font-size: 20px !important;  /* Mensaje más grande */
}
</style>
<script>
    function cancelarVenta() {
        document.getElementById('idCliente').value = '';
        document.getElementById('clienteSeleccionado').innerText = 'Ninguno';
        document.getElementById('detallesVenta').getElementsByTagName('tbody')[0].innerHTML = '';
        calcularTotalVenta();
    }

    const productosAgregados = new Map();

    function seleccionarProducto(id, nombre, stock, precioVenta) {
        let detallesVenta = document.getElementById('detallesVenta').getElementsByTagName('tbody')[0];
        
        if (productosAgregados.has(id)) {
            let fila = productosAgregados.get(id);
            let cantidadInput = fila.querySelector('[name="cantidad[]"]');
            let cantidadActual = parseInt(cantidadInput.value) || 0;
            cantidadInput.value = cantidadActual + 1;
            calcularSubtotal(cantidadInput);
        } else {
            let nuevaFila = document.createElement('tr');
            nuevaFila.setAttribute('data-id', id);
            nuevaFila.innerHTML = `
                <td><input type="hidden" name="producto[]" value="${id}">${nombre}</td>
                <td>${stock}</td>
                <td><input type="number" name="cantidad[]" value="1" min="1" max="${stock}" class="form-control" onchange="calcularSubtotal(this)"></td>
                <td>Bs.<label name="precioVenta[]">${precioVenta}</label></td>
                <td><input type="number" name="descuento[]" value="0" min="0" step="0.01" class="form-control" onchange="calcularSubtotal(this)" placeholder="Descuento por unidad"></td>
                <td>Bs.<label name="subtotal[]">0</label></td>
                <td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarProducto(this)">Eliminar</button></td>
            `;
            detallesVenta.appendChild(nuevaFila);
            productosAgregados.set(id, nuevaFila);
            calcularSubtotal(nuevaFila.querySelector('[name="cantidad[]"]'));
        }
        $('#modalProductos').modal('hide');
    }
    function seleccionarCliente(id, nombre) {
        document.getElementById('clienteSeleccionado').innerText = nombre;
        document.getElementById('idCliente').value = id;
        $('#modalClientes').modal('hide');
    }

    function eliminarProducto(button) {
        let fila = button.parentElement.parentElement;
        let id = fila.getAttribute('data-id');
        fila.remove();
        productosAgregados.delete(id);
        calcularTotalVenta();
    }

    function calcularSubtotal(input) {
        let fila = input.parentElement.parentElement;
        let cantidad = parseInt(fila.querySelector('[name="cantidad[]"]').value) || 0;
        let precioVenta = parseFloat(fila.querySelector('[name="precioVenta[]"]').innerText);
        let descuento = parseFloat(fila.querySelector('[name="descuento[]"]').value) || 0;
        let subtotal = (cantidad * precioVenta) - (cantidad * descuento);
        fila.querySelector('[name="subtotal[]"]').innerText = subtotal.toFixed(2);
        calcularTotalVenta();
    }

    function calcularTotalVenta() {
        let total = 0;
        document.querySelectorAll('#detallesVenta [name="subtotal[]"]').forEach((subtotal) => {
            total += parseFloat(subtotal.innerText) || 0;
        });
        document.getElementById('totalVenta').innerText = total.toFixed(2);
    }

    function guardarVenta(event) {
        if (document.getElementById('idCliente').value === '' || productosAgregados.size === 0) {
            alert("Debe seleccionar un cliente y al menos un producto.");
            event.preventDefault();
            return false;
        }
        return true;
    }
    function filtrarClientes() {
        let input = document.getElementById('buscarClienteModal');
        let filter = input.value.toLowerCase();
        let table = document.getElementById('tablaClientes');
        let tr = table.getElementsByTagName('tr');

        for (let i = 1; i < tr.length; i++) {
            let nombreTd = tr[i].getElementsByTagName('td')[0];
            let documentoTd = tr[i].getElementsByTagName('td')[1];
            if (nombreTd || documentoTd) {
                let nombre = nombreTd.textContent || nombreTd.innerText;
                let documento = documentoTd.textContent || documentoTd.innerText;
                if (nombre.toLowerCase().indexOf(filter) > -1 || documento.toLowerCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }

    function filtrarProductos() {
        let input = document.getElementById('buscarProducto');
        let filter = input.value.toLowerCase();
        let table = document.getElementById('tablaProductos');
        let tr = table.getElementsByTagName('tr');

        for (let i = 1; i < tr.length; i++) {
            let nombreTd = tr[i].getElementsByTagName('td')[0];
            let codigoTd = tr[i].getElementsByTagName('td')[1];
            if (nombreTd || codigoTd) {
                let nombre = nombreTd.textContent || nombreTd.innerText;
                let codigo = codigoTd.textContent || codigoTd.innerText;
                if (nombre.toLowerCase().indexOf(filter) > -1 || codigo.toLowerCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>