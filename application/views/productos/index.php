<div class="content-body">
    <div class="container-fluid">
    <?php if ($this->session->flashdata('mensaje')): ?>
    <script>
        toastr.success('<?= $this->session->flashdata('mensaje'); ?>', 'Éxito', {
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
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Productos</h4>
                    <p class="mb-0">Lista de Productos Activos</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= site_url('productos/agregar') ?>">Agregar Nuevo Producto</a></li>
                    <li class="breadcrumb-item active"><a href="<?= site_url('productos/eliminados') ?>">Inactivos</a></li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Lista de Productos</h4>
                        <a href="<?= site_url('productos/imprimir') ?>" target="_blank" class="btn btn-primary float-right" style="margin-left: 15px;">
                            <i class="fa fa-print"></i> Imprimir Lista de Productos
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example3" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th><strong>Nombre</strong></th>
                                        <th><strong>Código</strong></th>
                                        <th><strong>Precio Compra</strong></th>
                                        <th><strong>Precio Venta</strong></th>
                                        <th><strong>Stock</strong></th>
                                        <th><strong>Descripción</strong></th>
                                        <th><strong>Imagen</strong></th>
                                        <th><strong>Estado</strong></th>
                                        <th><strong>Acciones</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($producto as $producto): ?>
                                        <tr>
                                            <td><?= $producto['nombre'] ?></td>
                                            <td><?= $producto['codigo'] ?></td>
                                            <td>Bs. <?= $producto['precioCompra'] ?></td>
                                            <td>Bs. <?= $producto['precioVenta'] ?></td>
                                            <td><?= $producto['stock'] ?></td>
                                            <td><?= $producto['descripcion'] ?></td>
                                            <td>
                                                <?php if ($producto['imagen']): ?>
                                                    <img src="<?= base_url('uploads/productos/' . $producto['imagen']) ?>" alt="<?= $producto['nombre'] ?>" width="100">
                                                <?php else: ?>
                                                    No disponible
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($producto['estado'] == '1'): ?>
                                                    <span class="badge light badge-success">ACTIVO</span>
                                                <?php elseif ($producto['estado'] == '0'): ?>
                                                    <span class="badge light badge-primary">INACTIVO</span>
                                                <?php else: ?>
                                                    <span class="badge light badge-secondary">Desconocido</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn btn-success light sharp" data-toggle="dropdown">
                                                        <svg width="20px" height="20px" viewbox="0 0 24 24" version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                                <circle fill="#000000" cx="5" cy="12" r="2"></circle>
                                                                <circle fill="#000000" cx="12" cy="12" r="2"></circle>
                                                                <circle fill="#000000" cx="19" cy="12" r="2"></circle>
                                                            </g>
                                                        </svg>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="<?= site_url('productos/editar/' . $producto['idProducto']) ?>">Editar</a>
                                                        <a class="dropdown-item" href="javascript:void(0);" onclick="showConfirmationMessage('<?php echo site_url('productos/eliminar/'.$producto['idProducto']); ?>')">Eliminar</a>
<script type="text/javascript"> 
    function showConfirmationMessage(deleteUrl) {
        toastr.warning('¿Estás seguro de que deseas eliminar este producto?', 'Confirmar eliminación', {
            "positionClass": "toast-top-center",
            "closeButton": true,
            "timeOut": 6000,
            "progressBar": true,
            "onclick": function() {
                var confirmAction = confirm('¿Estás seguro de que deseas eliminar este producto?');
                if (confirmAction) {
                    window.location.href = deleteUrl;
                } else {
                    toastr.clear();
                }
            }
        });
    }
</script>
                                                    </div>
                                                </div>
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
<style>
.toast {
    font-size: 20px !important;
    padding: 35px !important;
    width: 350px !important;
    max-width: 70% !important;
}

.toast-title {
    font-size: 20px !important;
}

.toast-message {
    font-size: 20px !important;
}
</style>
