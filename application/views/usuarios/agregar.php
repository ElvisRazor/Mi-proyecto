<!-- Content Wrapper. Contains page content -->
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Agregar Usuario</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= site_url('usuarios') ?>">Usuarios</a></li>
                    <li class="breadcrumb-item active">Agregar Usuario</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <?php if ($this->session->flashdata('mensaje')): ?>
                            <div class="alert alert-success">
                                <?php echo $this->session->flashdata('mensaje'); ?>
                            </div>
                        <?php elseif ($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger">
                                <?php echo $this->session->flashdata('error'); ?>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="<?= site_url('usuarios/agregar') ?>">
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <label for="nombre" class="form-label">Nombre Completo</label>
                                        <input type="text" pattern="[A-Za-z\s]+" class="form-control" id="nombre" name="nombre" value="<?= set_value('nombre') ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email" class="form-label">Correo Electrónico</label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?= set_value('email') ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="tipoDocumento" class="form-label">Tipo Documento</label>
                                        <select class="form-control" id="tipoDocumento" name="tipoDocumento" required>
                                            <option value="">Seleccione tipo de documento</option>
                                            <option value="Ci/Nit" <?= set_select('tipoDocumento', 'Ci/Nit') ?>>CI/NIT</option>
                                            <option value="pasaporte" <?= set_select('tipoDocumento', 'pasaporte') ?>>Pasaporte</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="numDocumento" class="form-label">Nro. Documento</label>
                                        <input type="text" class="form-control" id="numDocumento" name="numDocumento" value="<?= set_value('numDocumento') ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="direccion" class="form-label">Dirección</label>
                                        <input type="text" class="form-control" id="direccion" name="direccion" value="<?= set_value('direccion') ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="telefono" class="form-label">Telefono</label>
                                        <input type="text" pattern="\d+" class="form-control" id="telefono" name="telefono" value="<?= set_value('telefono') ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="password" class="form-label">Contraseña</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="rol" class="form-label">Rol</label>
                                        <select class="form-control" id="rol" name="rol" required>
                                            <option value="">Seleccione rol</option>
                                            <option value="administrador" <?= set_select('rol', 'administrador') ?>>Administrador</option>
                                            <option value="vendedor" <?= set_select('rol', 'vendedor') ?>>Vendedor</option>
                                            <option value="cliente" <?= set_select('rol', 'cliente') ?>>Cliente</option>
                                            <option value="proveedor" <?= set_select('rol', 'proveedor') ?>>Proveedor</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Agregar Usuario</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


