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
                    <h4>Usuarios</h4>
                    <p class="mb-0">Lista de Usuarios Activos</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= site_url('usuarios/agregar') ?>">Agregar Nuevo</a></li>
                    <li class="breadcrumb-item active"><a href="<?= site_url('usuarios/eliminados') ?>">Inactivos</a>
                    </li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Lista De Usuarios</h4>
                        <a href="<?= site_url('usuarios/imprimir') ?>" target="_blank" class="btn btn-primary float-right" style="margin-left: 15px;">
                            <i class="fa fa-print"></i> Imprimir Lista de Usuarios
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example3" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th><strong>Nombre</strong></th>
                                        <th><strong>Apellido Paterno</strong></th>
                                        <th><strong>Apellido Materno</strong></th>
                                        <th><strong>Correo Electrónico</strong></th>
                                        <th><strong>Tipo Documento</strong></th>
                                        <th><strong>Dirección</strong></th>
                                        <th><strong>Rol</strong></th>
                                        <th><strong>Estado</strong></th>
                                        <th><strong>Acciones</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($usuario as $usuario): ?>
                                        <tr>
                                            <td><?= $usuario['nombre'] ?></td>
                                            <td><?= $usuario['primerApellido'] ?></td>
                                            <td><?= $usuario['segundoApellido'] ?></td>
                                            <td><?= $usuario['email'] ?></td>
                                            <td>
                                                <?php if ($usuario['tipoDocumento'] == 'Ci/Nit'): ?>
                                                    <span class="badge light badge-success">CI/NIT</span>
                                                <?php elseif ($usuario['tipoDocumento'] == 'Pasaporte'): ?>
                                                    <span class="badge light badge-warning">Pasaporte</span>
                                                <?php else: ?>
                                                    <span class="badge light badge-secondary">Desconocido</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= $usuario['direccion'] ?></td>
                                            <td>
                                                <?php if ($usuario['rol'] == 'administrador'): ?>
                                                    <span class="badge light badge-success">Administrador</span>
                                                <?php elseif ($usuario['rol'] == 'vendedor'): ?>
                                                    <span class="badge light badge-warning">Vendedor</span>
                                                <?php else: ?>
                                                    <span class="badge light badge-secondary">Desconocido</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($usuario['estado'] == '1'): ?>
                                                    <span class="badge light badge-success">ACTIVO</span>
                                                <?php elseif ($usuario['estado'] == '0'): ?>
                                                    <span class="badge light badge-primary">INACTIVO</span>
                                                <?php else: ?>
                                                    <span class="badge light badge-secondary">Desconocido</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn btn-success light sharp"
                                                        data-toggle="dropdown">
                                                        <svg width="20px" height="20px" viewbox="0 0 24 24" version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none"
                                                                fill-rule="evenodd">
                                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                                <circle fill="#000000" cx="5" cy="12" r="2"></circle>
                                                                <circle fill="#000000" cx="12" cy="12" r="2"></circle>
                                                                <circle fill="#000000" cx="19" cy="12" r="2"></circle>
                                                            </g>
                                                        </svg>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item"
                                                            href="<?= site_url('usuarios/editar/' . $usuario['idUsuario']) ?>">Editar</a>
                                                        <a class="dropdown-item"
                                                            href="<?= site_url('usuarios/eliminar/' . $usuario['idUsuario']) ?>"
                                                            onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">Eliminar</a>
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