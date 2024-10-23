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
            <li class="nav-label">Menú</li>
            <li><a href="<?php echo base_url(); ?>index.php/dashboard">
                    <i class="flaticon-144-layout"></i> <!-- Icono de Dashboard -->
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>
            <li class="nav-label">Componentes</li>
            <li>
                <a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="fas fa-boxes"></i> <!-- Icono para Almacén -->
                    <span class="nav-text">Almacén</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="<?php echo base_url(); ?>index.php/productos">Productos</a></li>
                    <li><a href="<?php echo base_url(); ?>index.php/categorias">Categorías</a></li>
                </ul>
            </li>
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="fa fa-shopping-cart"></i> <!-- Icono de Compras -->
                    <span class="nav-text">Compras</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="<?php echo base_url(); ?>index.php/compras">Compras</a></li>
                    <li><a href="<?php echo base_url(); ?>index.php/proveedores">Proveedores</a></li>
                </ul>
            </li>
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="fa fa-shopping-bag"></i> <!-- Icono para Ventas -->
                    <span class="nav-text">Ventas</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="<?php echo base_url(); ?>index.php/ventas">Ventas</a></li>
                    <li><a href="<?php echo base_url(); ?>index.php/clientes">Clientes</a></li>
                </ul>
            </li>
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-381-user-9"></i> <!-- Icono de Accesos (user) -->
                    <span class="nav-text">Accesos</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="<?php echo base_url(); ?>index.php/usuarios">Usuarios</a></li>
                </ul>
            </li>
            <!-- detalles -->
              
            <li class="nav-label">Configuraciones</li>
            <li><a href="#" class="ai-icon" aria-expanded="false">
                    <i class="flaticon-381-settings-2"></i>
                    <span class="nav-text">Configuraciones</span>
                </a>
            </li>
        </ul>
    </div>
</div>
<!--**********************************
        Sidebar end
***********************************-->
