<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <!--<link rel="stylesheet" href="https://adminlte.io/themes/v3/dist/css/adminlte.min.css"> -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, 
                red, orange, yellow, green, blue, indigo, violet);
            background-size: 400% 400%;
            animation: rainbowAnimation 10s ease infinite;
        }

        @keyframes rainbowAnimation {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .login-box {
            position: relative;
            z-index: 1;
            border: 2px solid #ffab40; /* Color resaltante */
            border-radius: 10px;
            background: #ffffff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            padding: 20px;
        }

        .login-logo img {
            width: 150px;
            margin-bottom: 20px;
        }

        .card-body {
            padding: 30px;
        }

        .btn-primary {
            background-color: #ffab40;
            border-color: #ffab40;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #ff8c00;
            border-color: #ff8c00;
        }

        .input-group-text {
            background-color: #ffab40;
            color: #ffffff;
        }

        .form-control:focus {
            border-color: #ff8c00;
            box-shadow: none;
        }

        a {
            color: #ffab40;
        }

        a:hover {
            color: #ff8c00;
        }
    </style>
</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo text-center">
            <img src="<?= base_url('assets/img/pisosbol(1).PNG') ?>" alt="Logo" class="img-fluid">
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
