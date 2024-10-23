<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Agregar Producto</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= site_url('productos') ?>">Productos</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Agregar</a></li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Formulario de Producto</h4>
                    </div>
                    <div class="card-body">
                        <?= form_open_multipart('productos/agregar') ?>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="<?= set_value('nombre') ?>" required>
                                <?= form_error('nombre') ?>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="codigo">Código</label>
                                <input type="text" class="form-control" id="codigo" name="codigo" value="<?= set_value('codigo') ?>" required>
                                <?= form_error('codigo') ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="codigo">Precio</label>
                                <input type="text" class="form-control" id="precio" name="precio" value="<?= set_value('precio') ?>" required>
                                <?= form_error('precio') ?>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="stock">Stock</label>
                                <input type="number" class="form-control" id="stock" name="stock" value="<?= set_value('stock') ?>" required>
                                <?= form_error('stock') ?>
                            </div>
                        </div>
                            <div class="form-group">
                                <label for="descripcion">Descripción</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" ><?= set_value('descripcion') ?></textarea>
                                <?= form_error('descripcion') ?>
                            </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="idCategoria">Categoría</label>
                                <select class="form-control" id="idCategoria" name="idCategoria" required>
                                    <?php foreach ($categoria as $categoria): ?>
                                        <option value="<?= $categoria['idCategoria'] ?>"><?= $categoria['nombre'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?= form_error('idCategoria') ?>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="imagen">Imagen</label>
                                <input type="file" class="form-control" id="imagen" name="imagen">
                                <?= form_error('imagen') ?>
                            </div>
                        </div>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
