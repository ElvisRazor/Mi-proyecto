<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('Usuario_model');
        $this->load->model('Cliente_model');
        $this->load->model('Producto_model');
        $this->load->model('Proveedor_model');
        $this->load->model('Venta_model');
        $this->load->model('Compra_model');
        
        if (!$this->session->userdata('login')) {
            redirect(base_url() . 'login');
        }
    }

    public function index() {
        // Obtener estadísticas de usuarios
        $total_usuarios = $this->Usuario_model->contar_usuarios();
        $incremento_usuarios = $this->Usuario_model->calcular_incremento_usuarios();

        // Obtener estadísticas de clientes
        $total_clientes = $this->Cliente_model->contar_clientes();
        $incremento_clientes = $this->Cliente_model->calcular_incremento_clientes();

        // Obtener estadísticas de productos
        $total_productos = $this->Producto_model->contar_productos();
        $incremento_productos = $this->Producto_model->calcular_incremento_productos();

        // Obtener estadísticas de proveedores
        $total_proveedores = $this->Proveedor_model->contar_proveedores();
        $incremento_proveedores = $this->Proveedor_model->calcular_incremento_proveedores();

        // Obtener estadísticas de ventas
        $ventas_por_mes = $this->Venta_model->ventas_por_mes(date('Y'));
        $ventas_totales = $this->Venta_model->ventas_totales();
        $ventas_por_categoria = $this->Venta_model->ventas_por_categoria();

        // Pasar los datos a la vista
        $data = [
            'total_usuarios' => $total_usuarios,
            'incremento_usuarios' => $incremento_usuarios,
            'total_clientes' => $total_clientes,
            'incremento_clientes' => $incremento_clientes,
            'total_productos' => $total_productos,
            'incremento_productos' => $incremento_productos,
            'total_proveedores' => $total_proveedores,
            'incremento_proveedores' => $incremento_proveedores,
            'ventas_por_mes' => $ventas_por_mes,
            'ventas_totales' => $ventas_totales,
            'ventas_por_categoria' => $ventas_por_categoria
        ];

        // Cargar las vistas
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('dashboard/index', $data);
        $this->load->view('templates/footer');
    }
}
?>