<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('Usuario_model'); // Asegúrate de tener un modelo para manejar usuarios
    }

    public function index() {
        // Si ya está logueado, redirigir al dashboard o página principal
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }
        $this->load->view('login');
    }

    public function autenticar() {
        $email = $this->input->post('email');
        $password = md5($this->input->post('password')); // Usa un método más seguro en producción
    
        $user = $this->Usuario_model->get_user($email, $password);
    
        if ($user) {
            log_message('info', 'Usuario autenticado: ' . $email); // Agrega un registro para depuración
            $this->session->set_userdata([
                'logged_in' => TRUE,
                'user_id' => $user->idUsuario,
                'user_role' => $user->rol
            ]);
            redirect('dashboard');
        } else {
            log_message('error', 'Fallo de autenticación para el usuario: ' . $email); // Agrega un registro para depuración
            $this->session->set_flashdata('error', 'Email o contraseña incorrectos');
            redirect('login');
        }
    }    

    public function logout() {
        $this->session->sess_destroy();
        redirect('login');
    }
}
