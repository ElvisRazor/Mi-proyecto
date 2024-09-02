<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        // Verifica que el usuario esté logueado
        if (!$this->session->userdata('usuario_id')) {
            redirect('login');
        }
    }

    public function index() {
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('dashboard/index'); // Vista del dashboard
        $this->load->view('templates/footer');
    }
}
