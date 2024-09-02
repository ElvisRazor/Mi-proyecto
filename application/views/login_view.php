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
    <!-- Custom CSS -->
    <style>
        body {
            background: linear-gradient(135deg, #F0F6E6 0%, #C3DD9B 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-box {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .login-logo {
            margin-bottom: 20px;
        }
        .login-logo img {
            width: 300px; /* Ajusta el tamaño del logo según sea necesario */
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .btn-primary {
            background-color: #68A906;
            border: none;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .btn-primary:hover {
            background-color: #497604;
        }
        .input-group-text {
            background-color: #68A906;
            color: #ffffff;
            border: none;
        }
        .input-group .form-control {
            border: 1px solid #68A906;
            border-radius: 5px;
        }
        .login-box-msg {
            color: #68A906;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .alert {
            border-radius: 5px;
        }
        .text-sm {
            color: #68A906;
        }
        .text-sm:hover {
            color: #497604;
            text-decoration: underline;
        }
        /* Estilo adicional para centrado vertical y horizontal */
        .d-flex {
            display: flex;
        }
        .justify-content-center {
            justify-content: center;
        }
        .align-items-center {
            align-items: center;
        }
        .min-vh-100 {
            min-height: 100vh;
        }
    </style>
</head>
<body class="hold-transition login-page">
    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="login-box">
            <div class="login-logo">
                <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo" class="img-fluid">
            </div>
            <div class="card">
                <div class="card-body login-card-body">
                    <p class="login-box-msg text-center">Inicia sesión para comenzar tu sesión</p>
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger text-center">
                            <?= $this->session->flashdata('error') ?>
                        </div>
                    <?php endif; ?>
                    <?= form_open('login/autenticar') ?>
                        <div class="input-group mb-3">
                            <input type="text" name="nombre_usuario" class="form-control" placeholder="Nombre de Usuario" required>
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
                                <a href="#" class="text-sm">Olvidé mi contraseña</a>
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
</body>
</html>
