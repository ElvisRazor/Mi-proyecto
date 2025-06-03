<div class="content-body">
    <div class="container-fluid">
        <!-- Mostrar mensaje de éxito si existe -->
        <?php if ($this->session->flashdata('mensaje')): ?>
            <script type="text/javascript">
                // Mostrar el mensaje flotante con Toastr
                toastr.success('<?php echo $this->session->flashdata('mensaje'); ?>', '¡Éxito!', {
                    "positionClass": "toast-top-center", // Posición en la parte superior derecha
                    "closeButton": true,               // Botón de cerrar
                    "timeOut": 2000,                   // Tiempo en milisegundos (2 segundos)
                    "progressBar": true,               // Barra de progreso
                });
            </script>
        <?php endif; ?>
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Categorías</h4>
                    <p class="mb-0">Lista de Categorías Activas</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= site_url('categorias/agregar') ?>">Agregar Nueva</a></li>
                    <li class="breadcrumb-item active"><a href="<?= site_url('categorias/inactivos') ?>">Inactivas</a></li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Lista de Categorías</h4>
                        <a href="<?= site_url('categorias/imprimir_todas') ?>" class="btn btn-primary" target="_blank">
                            <i class="fa fa-print"></i> Imprimir Lista de Categorías
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example3" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th><strong>Nombre</strong></th>
                                        <th><strong>Descripción</strong></th>
                                        <th><strong>Estado</strong></th>
                                        <th><strong>Acciones</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($categoria as $categoria): ?>
                                        <tr>
                                            <td><?= $categoria['nombre'] ?></td>
                                            <td><?= $categoria['descripcion'] ?></td>
                                            <td>
                                                <?php if ($categoria['estado'] == '1'): ?>
                                                    <span class="badge light badge-success">ACTIVA</span>
                                                <?php elseif ($categoria['estado'] == '0'): ?>
                                                    <span class="badge light badge-primary">INACTIVA</span>
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
                                                        <a class="dropdown-item" href="<?= site_url('categorias/editar/'.$categoria['idCategoria']) ?>">Editar</a>
                                                        <a class="dropdown-item" href="javascript:void(0);" onclick="showConfirmationMessage('<?php echo site_url('categorias/eliminar/'.$categoria['idCategoria']); ?>')">Eliminar</a>
<script type="text/javascript"> 
    // Función para mostrar el mensaje de confirmación y ejecutar la eliminación si el usuario acepta
    function showConfirmationMessage(deleteUrl) {
        // Mostrar el mensaje flotante usando Toastr
        toastr.warning('¿Estás seguro de que deseas eliminar esta categoría?', 'Confirmar eliminación', {
            "positionClass": "toast-top-center", // Posición en el centro de la pantalla
            "closeButton": true,                    // Botón de cerrar
            "timeOut": 6000,                        // Tiempo en milisegundos (6 segundos)
            "progressBar": true,                    // Barra de progreso
            "onclick": function() {
                // Crear un cuadro de confirmación directamente con botones de "Sí" y "No"
                var confirmAction = confirm('¿Estás seguro de que deseas eliminar esta categoría?');
                
                // Si el usuario confirma la acción
                if (confirmAction) {
                    // Si el usuario confirma, redirigir a la URL para eliminar
                    window.location.href = deleteUrl;
                } else {
                    // Si el usuario cancela, solo cerrar el mensaje
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