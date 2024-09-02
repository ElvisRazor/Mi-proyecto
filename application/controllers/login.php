<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Usuario_model');
        $this->load->library('session');
    }

    public function index() {
        $this->load->view('login_view');
    }

    public function autenticar() {
        $nombre_usuario = $this->input->post('nombre_usuario');
        $password = $this->input->post('password');

        $usuario = $this->Usuario_model->autenticar($nombre_usuario, $password);

        if ($usuario) {
            $this->session->set_userdata('usuario_id', $usuario['usuario_id']);
            $this->session->set_userdata('nombre_usuario', $usuario['nombre_usuario']);
            redirect('dashboard');
        } else {
            $this->session->set_flashdata('error', 'Nombre de usuario o contraseña incorrectos.');
            redirect('login');
        }
    }

    public function cerrar_sesion() {
        $this->session->sess_destroy();
        redirect('login');
    }
}
?>
