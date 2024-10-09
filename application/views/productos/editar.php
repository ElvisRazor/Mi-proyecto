<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Editar Producto</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Formulario de Producto</h4>
                    </div>
                    <div class="card-body">
                        <?= form_open_multipart('productos/editar/' . $producto['idProducto']) ?>
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="<?= set_value('nombre', $producto['nombre']) ?>" required>
                                <?= form_error('nombre') ?>
                            </div>
                            <div class="form-group">
                                <label for="codigo">Código</label>
                                <input type="text" class="form-control" id="codigo" name="codigo" value="<?= set_value('codigo', $producto['codigo']) ?>" required>
                                <?= form_error('codigo') ?>
                            </div>
                            <div class="form-group">
                                <label for="stock">Stock</label>
                                <input type="number" class="form-control" id="stock" name="stock" value="<?= set_value('stock', $producto['stock']) ?>" required>
                                <?= form_error('stock') ?>
                            </div>
                            <div class="form-group">
                                <label for="descripcion">Descripción</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" required><?= set_value('descripcion', $producto['descripcion']) ?></textarea>
                                <?= form_error('descripcion') ?>
                            </div>
                            <div class="form-group">
                                <label for="idCategoria">Categoría</label>
                                <select class="form-control" id="idCategoria" name="idCategoria" required>
                                    <?php foreach ($categoria as $categoria): ?>
                                        <option value="<?= $categoria['idCategoria'] ?>" <?= $categoria['idCategoria'] == $producto['idCategoria'] ? 'selected' : '' ?>><?= $categoria['nombre'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?= form_error('idCategoria') ?>
                            </div>
                            <div class="form-group">
                                <label for="imagen">Imagen</label>
                                <input type="file" class="form-control" id="imagen" name="imagen">
                                <input type="hidden" name="imagen_actual" value="<?= $producto['imagen'] ?>">
                                <?php if ($producto['imagen']): ?>
                                    <img src="<?= base_url('uploads/productos/' . $producto['imagen']) ?>" alt="<?= $producto['nombre'] ?>" width="100">
                                <?php endif; ?>
                                <?= form_error('imagen') ?>
                            </div>
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
