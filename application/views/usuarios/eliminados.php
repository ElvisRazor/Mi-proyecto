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
                    <h4>Usuarios Inactivos</h4>
                    <p class="mb-0">Lista de Usuarios Inactivos</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= site_url('usuarios') ?>">Usuarios Activos</a></li>
                    <li class="breadcrumb-item active">Usuarios Inactivos</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Usuarios Eliminados</h3>
                </div>
                <div class="card-body">
                <div class="table-responsive">
                    <table id="example3" class="display" style="min-width: 845px">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Apellido Materno</th>
                                <th>Apellido Paterno</th>
                                <th>Correo Electrónico</th>
                                <th>Tipo Documento</th>
                                <th>Rol</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td><?= $usuario['nombre'] ?></td>
                                    <td><?= $usuario['primerApellido'] ?></td>
                                    <td><?= $usuario['segundoApellido'] ?></td>
                                    <td><?= $usuario['email'] ?></td>
                                    <td><?= $usuario['tipoDocumento'] ?></td>
                                    <td><?= $usuario['rol'] ?></td>
                                    <td>
                                        <a href="<?= site_url('usuarios/habilitar/'.$usuario['idUsuario']) ?>" class="btn btn-success btn-sm">Habilitar</a>
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