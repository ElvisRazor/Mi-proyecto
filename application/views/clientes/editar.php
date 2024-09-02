<div class="content-body">

    <div class="container-fluid">
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
                        <form action="<?= site_url('clientes/editar/' . $cliente['cliente_id']) ?>" method="post">
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <label for="ci" class="form-label">CI</label>
                                        <input type="text" name="ci" class="form-control" id="ci"
                                            value="<?= $cliente['ci'] ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="nombre" class="form-label">Nombre</label>
                                        <input type="text" name="nombre" class="form-control" id="nombre"
                                            value="<?= $cliente['nombre'] ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="primer_apellido" class="form-label">Primer Apellido</label>
                                        <input type="text" name="primer_apellido" class="form-control"
                                            id="primer_apellido" value="<?= $cliente['primer_apellido'] ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="segundo_apellido" class="form-label">Segundo Apellido</label>
                                        <input type="text" name="segundo_apellido" class="form-control"
                                            id="segundo_apellido" value="<?= $cliente['segundo_apellido'] ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                                        <input type="date" name="fecha_nacimiento" class="form-control"
                                            id="fecha_nacimiento" value="<?= $cliente['fecha_nacimiento'] ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="direccion" class="form-label">Dirección</label>
                                        <input type="text" name="direccion" class="form-control" id="direccion"
                                            value="<?= $cliente['direccion'] ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="telefono" class="form-label">Teléfono</label>
                                        <input type="text" name="telefono" class="form-control" id="telefono"
                                            value="<?= $cliente['telefono'] ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email" class="form-label">Correo Electrónico</label>
                                        <input type="email" name="email" class="form-control" id="email"
                                            value="<?= $cliente['email'] ?>" required>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <label for="nombre_garante" class="form-label">Nombre del Garante</label>
                                        <input type="text" name="nombre_garante" class="form-control"
                                            id="nombre_garante" value="<?= $cliente['nombre_garante'] ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="apellidos_garante" class="form-label">Apellidos del Garante</label>
                                        <input type="text" name="apellidos_garante" class="form-control"
                                            id="apellidos_garante" value="<?= $cliente['apellidos_garante'] ?>"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="telefono_garante" class="form-label">Teléfono del Garante</label>
                                        <input type="text" name="telefono_garante" class="form-control"
                                            id="telefono_garante" value="<?= $cliente['telefono_garante'] ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email_garante" class="form-label">Correo Electrónico del
                                            Garante</label>
                                        <input type="email" name="email_garante" class="form-control" id="email_garante"
                                            value="<?= $cliente['email_garante'] ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="direccion_garante" class="form-label">Dirección del Garante</label>
                                        <input type="text" name="direccion_garante" class="form-control"
                                            id="direccion_garante" value="<?= $cliente['direccion_garante'] ?>"
                                            required>
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