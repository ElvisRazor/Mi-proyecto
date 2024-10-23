<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Agregar Categoría</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= site_url('categorias') ?>">Categorías</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Agregar</a></li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Formulario de Categoría</h4>
                    </div>
                    <div class="card-body">
                        <?php echo form_open(); ?>
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="<?= set_value('nombre') ?>">
                                <?php echo form_error('nombre'); ?>
                            </div>
                            <div class="form-group">
                                <label for="descripcion">Descripción</label>
                                <textarea class="form-control" id="descripcion" name="descripcion"><?= set_value('descripcion') ?></textarea>
                                <?php echo form_error('descripcion'); ?>
                            </div>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
