<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('Usuario_model');  // Cargar el modelo de usuario
        $this->load->model('Cliente_model');  // Cargar el modelo de cliente
        $this->load->model('Producto_model'); // Cargar el modelo de producto
        $this->load->model('Proveedor_model'); // Cargar el modelo de proveedor
        $this->load->model('Venta_model'); // Cargar el modelo de ventas

        if (!$this->session->userdata('login')) {
            redirect(base_url() . 'login');
        }
    }

    public function index() {
        // Obtener estadísticas de usuarios
        $total_usuarios = $this->Usuario_model->contar_usuarios();
        $incremento_usuarios = $this->Usuario_model->calcular_incremento_usuarios();

        // Pasar los datos a la vista
        $data['total_usuarios'] = $total_usuarios;
        $data['incremento_usuarios'] = $incremento_usuarios;

        // Obtener estadísticas de clientes
        $total_clientes = $this->Cliente_model->contar_clientes();
        $incremento_clientes = $this->Cliente_model->calcular_incremento_clientes();

        // Pasar los datos a la vista
        $data['total_clientes'] = $total_clientes;
        $data['incremento_clientes'] = $incremento_clientes;

        // Obtener estadísticas de productos
        $total_productos = $this->Producto_model->contar_productos();
        $incremento_productos = $this->Producto_model->calcular_incremento_productos();

        // Pasar los datos a la vista
        $data['total_productos'] = $total_productos;
        $data['incremento_productos'] = $incremento_productos;

        // Obtener estadísticas de proveedores
        $total_proveedores = $this->Proveedor_model->contar_proveedores();
        $incremento_proveedores = $this->Proveedor_model->calcular_incremento_proveedores();

        // Pasar los datos a la vista
        $data['total_proveedores'] = $total_proveedores;
        $data['incremento_proveedores'] = $incremento_proveedores;
        //####################
        // Obtener ventas semanales y mensuales
        $ventas_semanales = $this->Venta_model->obtener_ventas_semanales();
        $ventas_mensuales = $this->Venta_model->obtener_ventas_mensuales();
        $ventas_hoy = $this->Venta_model->obtener_ventas_hoy();

        // Comparar ventas semanales con la semana anterior
        $ventas_semana_anterior = $this->Venta_model->obtener_ventas_semana_anterior();
        $incremento_semanal = $this->calcular_incremento($ventas_semanales, $ventas_semana_anterior);

        // Comparar ventas mensuales con el mes anterior
        $ventas_mes_anterior = $this->Venta_model->obtener_ventas_mes_anterior();
        $incremento_mensual = $this->calcular_incremento($ventas_mensuales, $ventas_mes_anterior);

        // Pasar los datos a la vista
        $data['ventas_semanales'] = $ventas_semanales;
        $data['ventas_mensuales'] = $ventas_mensuales;
        $data['incremento_semanal'] = $incremento_semanal;
        $data['incremento_mensual'] = $incremento_mensual;
        $data['ventas_hoy'] = $ventas_hoy;

        // Cargar las vistas
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('dashboard/index', $data);
        $this->load->view('templates/footer');
    }
    private function calcular_incremento($actual, $anterior) {
        if ($anterior > 0) {
            return (($actual - $anterior) / $anterior) * 100;
        } else {
            return 0;
        }
    }
}
?>
