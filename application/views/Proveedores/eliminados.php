<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Proveedores Inactivos</h4>
                    <p class="mb-0">Lista de Proveedores Inactivos</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= site_url('proveedores') ?>">Proveedores Activos</a></li>
                    <li class="breadcrumb-item active">Proveedores Inactivos</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Lista de Proveedores Inactivos</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example3" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th><strong>Nombre</strong></th>
                                        <th><strong>Tipo Documento</strong></th>
                                        <th><strong>Correo Electrónico</strong></th>
                                        <th><strong>Acciones</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($proveedor as $proveedor): ?>
                                        <tr>
                                            <td><?= $proveedor['nombre'] ?></td>
                                            <td><?= $proveedor['tipoDocumento'] ?></td>
                                            <td><?= $proveedor['email'] ?></td>
                                            <td>
                                                <a href="<?= site_url('proveedores/habilitar/'.$proveedor['idProveedor']) ?>" class="btn btn-success btn-sm">Habilitar</a>
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
