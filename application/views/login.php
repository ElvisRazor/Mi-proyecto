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
        background: url('assets/img/dukel.PNG') no-repeat center center fixed;
        background-size: cover;
    }

    .login-box { 
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.3); /* Fondo semitransparente */
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        padding: 30px;
        width: 450px;
    }

    .login-logo img {
        width: 100px;
        margin-bottom: 0px;
    }
    .card {
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.0); /* Fondo semitransparente */
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .card-body {
        padding: 20px;
    }

    .btn-primary {
        background-color:rgb(56, 73, 170);
        border-color:rgb(57, 124, 141);
        transition: background-color 0.3s ease-in-out;
        border-radius: 4px;
        padding: 10px;
    }

    .btn-primary:hover {
        background-color:rgb(60, 135, 185);
        border-color:rgb(56, 154, 179);
    }

    .input-group-text {
        background-color:rgb(65, 120, 172);
        color: #ffffff;
    }

    .form-control:focus {
        border-color: #66bb6a;
        box-shadow: none;
    }

    a {
        color: #1b5e20;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    a:hover {
        color: #1b5e20;
    }

    /* Extra styles for smoothness */
    input[type="text"], input[type="password"] {
        border-radius: 4px;
        border: 5px solid #ccc;
        padding: 15px;
        transition: border-color 0.3s;
    }

    input[type="text"]:focus, input[type="password"]:focus {
        border-color:rgb(59, 153, 148);
    }
</style>

</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo text-center">
        <img src="<?= base_url('assets/img/logoTrans.PNG') ?>" alt="Logo" class="img-fluid" style="width: 100%; max-width: 500px;">
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
