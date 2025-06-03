<div class="deznav">
    <div class="deznav-scroll">
        <div class="main-profile" style="text-align: center;"> <div class="image-bx" style="margin: 0 auto; display: block; overflow: hidden; width: fit-content;"> <img src="<?= base_url('assets/img/logoTrans.PNG') ?>" alt="0" style="width: 200px; height: auto; display: block; object-fit: contain;"> <a href=""><i class="" aria-hidden=""></i></a>
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
                    <i class="flaticon-144-layout"></i> <span class="nav-text">Dashboard</span>
                </a>
            </li>
            <li class="nav-label">Componentes</li>

            <?php if ($rol === 'administrador' || $rol === 'vendedor'): ?>
            <li>
                <a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="fas fa-boxes"></i> <span class="nav-text">Almacén</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="<?php echo base_url(); ?>index.php/productos">Productos</a></li>
                    <li><a href="<?php echo base_url(); ?>index.php/categorias">Categorías</a></li>
                </ul>
            </li>
            <?php endif; ?>

            <?php if ($rol === 'administrador' || $rol === 'vendedor'): ?>
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="fa fa-shopping-cart"></i> <span class="nav-text">Compras</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="<?php echo base_url(); ?>index.php/compras">Compras</a></li>
                    <li><a href="<?php echo base_url(); ?>index.php/proveedores">Proveedores</a></li>
                </ul>
            </li>
            <?php endif; ?>

            <?php if ($rol === 'administrador' || $rol === 'vendedor'): ?>
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="fa fa-shopping-bag"></i> <span class="nav-text">Ventas</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="<?php echo base_url(); ?>index.php/ventas">Ventas</a></li>
                    <li><a href="<?php echo base_url(); ?>index.php/clientes">Clientes</a></li>
                </ul>
            </li>
            <?php endif; ?>

            <?php if ($rol === 'administrador'): ?>
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-381-user-9"></i> <span class="nav-text">Accesos</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="<?php echo base_url(); ?>index.php/usuarios">Usuarios</a></li>
                </ul>
            </li>
            <?php endif; ?>

            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-381-file"></i> <span class="nav-text">Reportes</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="<?= base_url('reporte/ventasPorFechas'); ?>">Ventas por Fechas</a></li>
                    <li><a href="<?= base_url('reporte/clientesFieles'); ?>">Clientes Más Fieles</a></li>
                    <li><a href="<?= base_url('reporte/comprasPorFechas'); ?>">Compras por Fechas</a></li>
                </ul>
            </li>

            </ul>
    </div>
</div>