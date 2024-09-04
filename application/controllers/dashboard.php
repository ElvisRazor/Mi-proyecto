<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('Usuario_model'); // Cargar el modelo de usuario
        $this->load->model('Cliente_model'); // Cargar el modelo de cliente

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
        // Obtener estadísticas de usuarios
        $total_clientes = $this->Cliente_model->contar_clientes();
        $incremento_clientes = $this->Cliente_model->calcular_incremento_clientes();

        // Pasar los datos a la vista
        $data['total_clientes'] = $total_clientes;
        $data['incremento_clientes'] = $incremento_clientes;

        // Cargar las vistas
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('dashboard/index', $data);
        $this->load->view('templates/footer');
    }
}
?>
