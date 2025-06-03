<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model("Login_model");
    }

    public function index() {
        if ($this->session->userdata("login")) {
            redirect(base_url()."dashboard");
        } else {
            $this->load->view("login");
        }
    }

    public function login() {
        $email = $this->input->post("email");
        $password = $this->input->post("password");
        $res = $this->Login_model->login($email, md5($password));

        if (!$res) {
            $this->session->set_flashdata("error", "Contraseña o email incorrecto");
            redirect(base_url()."login");
        } else {
            $data = array(
                'id' => $res->id,
                'nombre' => $res->nombre,
                'primerApellido' => $res->primerApellido, // Añadir primer apellido
                'segundoApellido' => $res->segundoApellido, // Añadir segundo apellido
                'email' => $res->email,
                'telefono' => $res->telefono,
                'rol' => $res->rol,
                'imagen' => $res->imagen,
                'login' => TRUE
            );
            $this->session->set_userdata($data);
             // Mensaje de bienvenida
            $this->session->set_flashdata("success", "Bienvenido, " . $res->nombre . "!");
            redirect(base_url()."dashboard");
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect(base_url()."login");
    }
}