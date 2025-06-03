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
                    <h4>Editar Usuario</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= site_url('usuarios') ?>">Usuarios</a></li>
                    <li class="breadcrumb-item active">Editar Usuario</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <?php if ($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger">
                                <?= $this->session->flashdata('error') ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($this->session->flashdata('mensaje')): ?>
                            <div class="alert alert-success">
                                <?= $this->session->flashdata('mensaje') ?>
                            </div>
                        <?php endif; ?>

                        <form action="<?= site_url('usuarios/editar/' . $usuario['idUsuario']) ?>" method="post">
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <label for="nombre" class="form-label">Nombre:</label>
                                        <input type="text" name="nombre" class="form-control" id="nombre"
                                            value="<?= set_value('nombre', $usuario['nombre']) ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="primerApellido" class="form-label">Apellido Paterno:</label>
                                        <input type="text" name="primerApellido" class="form-control" id="primerApellido"
                                            value="<?= set_value('primerApellido', $usuario['primerApellido']) ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="segundoApellido" class="form-label">Apellido Materno:</label>
                                        <input type="text" name="segundoApellido" class="form-control" id="segundoApellido"
                                            value="<?= set_value('segundoApellido', $usuario['segundoApellido']) ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="email" class="form-label">Correo Electrónico:</label>
                                        <input type="email" name="email" class="form-control" id="email"
                                            value="<?= set_value('email', $usuario['email']) ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="tipoDocumento" class="form-label">Tipo de Documento:</label>
                                        <select name="tipoDocumento" class="form-control" id="tipoDocumento">
                                            <?php foreach ($tipos_documento as $tipo): ?>
                                                <option value="<?= $tipo ?>" <?= ($tipo == $usuario['tipoDocumento']) ? 'selected' : '' ?>>
                                                    <?= $tipo ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <label for="numDocumento" class="form-label">Nro. Documento:</label>
                                        <input type="text" name="numDocumento" class="form-control" id="numDocumento"
                                            value="<?= set_value('numDocumento', $usuario['numDocumento']) ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="direccion" class="form-label">Dirección:</label>
                                        <input type="text" name="direccion" class="form-control" id="direccion"
                                            value="<?= set_value('direccion', $usuario['direccion']) ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="telefono" class="form-label">Telefono:</label>
                                        <input type="text" name="telefono" class="form-control" id="telefono"
                                            value="<?= set_value('telefono', $usuario['telefono']) ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="rol" class="form-label">Rol:</label>
                                        <select name="rol" class="form-control" id="rol">
                                            <?php foreach ($roles as $rol): ?>
                                                <option value="<?= $rol ?>" <?= ($rol == $usuario['rol']) ? 'selected' : '' ?>>
                                                    <?= $rol ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="password" class="form-label">Contraseña (opcional):</label>
                                        <input type="password" name="password" class="form-control" id="password">
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
                        </form>
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