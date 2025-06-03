<div class="content-body">
    <div class="container-fluid">
        <!-- Mostrar mensaje de éxito si existe -->
        <?php if ($this->session->flashdata('error')): ?>
            <script type="text/javascript">
                // Mostrar el mensaje flotante con Toastr
                toastr.success('<?php echo $this->session->flashdata('error'); ?>', '¡Éxito!', {
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
                    <h4>Editar Cliente</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= site_url('clientes') ?>">Clientes</a></li>
                    <li class="breadcrumb-item active">Editar Cliente</li>
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
                        <form action="<?= site_url('clientes/editar/' . $cliente['idCliente']) ?>" method="post">
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
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
                                        <input type="text" name="numDocumento" class="form-control" id="numDocumento" value="<?= $cliente['numDocumento'] ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="nombre" class="form-label">Nombre</label>
                                        <input type="text" name="nombre" class="form-control" id="nombre" value="<?= $cliente['nombre'] ?>" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <label for="direccion" class="form-label">Dirección</label>
                                        <input type="text" name="direccion" class="form-control" id="direccion" value="<?= $cliente['direccion'] ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="telefono" class="form-label">Teléfono</label>
                                        <input type="text" name="telefono" class="form-control" id="telefono" value="<?= $cliente['telefono'] ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email" class="form-label">Correo Electrónico</label>
                                        <input type="email" name="email" class="form-control" id="email" value="<?= $cliente['email'] ?>">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
