<div class="deznav">
    <div class="deznav-scroll">
        <div class="main-profile">
            <div class="image-bx">
                <img src="<?= base_url('assets/img/pisosbol1.PNG') ?>" alt="">
                <a href="javascript:void();"><i class="fa fa-cog" aria-hidden="true"></i></a>
            </div>
            <h4>Bienvenido</h4>
            <h5 class="name"><span class="font-w400"></span> 
                <?php 
                $rol = $this->session->userdata('rol'); 
                echo isset($rol) ? $rol : 'Invitado';
                ?>
            </h5>
            <p class="email"><a></a></p>
        </div>
        <ul class="metismenu" id="menu">
            <li class="nav-label first">DASHBOARD</li>
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-144-layout"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
                <ul>
                    <li><a href="<?php echo base_url(); ?>index.php/dashboard">Dashboard</a></li>
                </ul>
            </li>
            <li class="nav-label">Menú</li>
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-077-menu-1"></i>
                    <span class="nav-text">Almacen</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="<?php echo base_url(); ?>index.php/productos">Productos</a></li>
                    <li><a href="<?php echo base_url(); ?>index.php/categorias">Categorias</a></li>
                </ul>
            </li>
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-061-puzzle"></i>
                    <span class="nav-text">Compras</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="<?php echo base_url(); ?>index.php/compras">Compras</a></li>
                    <li><a href="<?php echo base_url(); ?>index.php/proveedores">Proveedores</a></li>
                </ul>
            </li>
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-061-puzzle"></i>
                    <span class="nav-text">Ventas</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="<?php echo base_url(); ?>index.php/ventas">Ventas</a></li>
                    <li><a href="<?php echo base_url(); ?>index.php/clientes">Clientes</a></li>
                </ul>
            </li>
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-061-puzzle"></i>
                    <span class="nav-text">Accesos</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="<?php echo base_url(); ?>index.php/usuarios">Usuarios</a></li>
                    
                </ul>
            </li>
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-061-puzzle"></i>
                    <span class="nav-text">Detalles</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="chart-flot.html">Consulta compra</a></li>
                    <li><a href="chart-morris.html">Consulta Ventas</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>
<!--**********************************
            Sidebar end
        ***********************************-->