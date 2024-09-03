<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://adminlte.io/themes/v3/dist/css/adminlte.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
</head>
<body class="hold-transition login-page">
    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="login-box">
            <div class="login-logo">
                <img src="<?= base_url('assets/img/pisosbol1.PNG') ?>" alt="Logo" class="img-fluid">
            </div>
            <div class="card">
                <div class="card-body login-card-body">
                    <p class="login-box-msg text-center"><b>Inicia sesión</b></p>
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger text-center">
                            <?= $this->session->flashdata('error') ?>
                        </div>
                    <?php endif; ?>
                    <?= form_open('login/login') ?>
                        <div class="input-group mb-3">
                            <input type="text" name="email" class="form-control" placeholder="Email" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-block">Iniciar Sesión</button>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                <a href="<?= base_url('recuperar_contrasena') ?>" class="text-sm">Olvidé mi contraseña</a>
                            </div>
                        </div>
                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="https://adminlte.io/themes/v3/dist/js/adminlte.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        $(document).ready(function () {
            <?php if($this->session->flashdata("error")): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '<?= $this->session->flashdata("error"); ?>',
                });
            <?php endif; ?>

            <?php $this->session->unset_userdata('success'); ?>
            <?php $this->session->unset_userdata('error'); ?>
        });
    </script>
</body>
</html>
