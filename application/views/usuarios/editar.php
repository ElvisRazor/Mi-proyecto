<div class="content-body">

    <div class="container-fluid">
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
                        <form action="<?= site_url('usuarios/editar/' . $usuario->idUsuario) ?>" method="post">
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <label for="nombre" class="form-label">Nombre Completo:</label>
                                        <input type="text" name="nombre" class="form-control" id="nombre"
                                            value="<?= $usuarios->nombre ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email" class="form-label">Correo Electrónico:</label>
                                        <input type="email" name="email" class="form-control" id="email"
                                            value="<?= $usuario->email ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="nombre" class="form-label">Nombre de Usuario:</label>
                                        <input type="text" name="nombre" class="form-control" id="nombre"
                                            value="<?= $usuario->nombre ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="rol" class="form-label">Rol:</label>
                                        <select name="rol" class="form-control" id="rol">
                                            <?php foreach ($roles as $rol): ?>
                                                <option value="<?= $rol ?>" <?= ($rol == $usuario->rol) ? 'selected' : '' ?>>
                                                    <?= $rol ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6">
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
