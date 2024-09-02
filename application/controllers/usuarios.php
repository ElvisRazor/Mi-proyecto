<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Usuarios extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Usuario_model');
        $this->load->helper('url');
        $this->load->library(['session', 'form_validation', 'email']);
        $this->load->config('email');
    }

    public function index() {
        $data['usuarios'] = $this->Usuario_model->obtener_usuarios_activos();
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('usuarios/index', $data);
        $this->load->view('templates/footer');
    }

    public function agregar() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('nombre_completo', 'Nombre Completo', 'required');
            $this->form_validation->set_rules('email', 'Correo Electrónico', 'required|valid_email|callback_check_email');
            $this->form_validation->set_rules('nombre_usuario', 'Nombre de Usuario', 'required');
            $this->form_validation->set_rules('rol', 'Rol', 'required');

            if ($this->form_validation->run() === TRUE) {
                $password = $this->generar_contraseña();
                $data = [
                    'nombre_completo' => $this->input->post('nombre_completo'),
                    'email' => $this->input->post('email'),
                    'nombre_usuario' => $this->input->post('nombre_usuario'),
                    'password' => md5($password),
                    'rol' => $this->input->post('rol'),
                    'estado' => TRUE
                ];

                if ($this->Usuario_model->agregar_usuario($data)) {
                    $this->_enviar_correo($this->input->post('email'), $this->input->post('nombre_completo'), $this->input->post('nombre_usuario'), $password);
                    $this->session->set_flashdata('mensaje', 'Usuario agregado correctamente. Se ha enviado la contraseña al correo electrónico.');
                    redirect('usuarios');
                } else {
                    $this->session->set_flashdata('error', 'Error al agregar el usuario.');
                }
            } else {
                $this->session->set_flashdata('error', validation_errors());
            }
        }

        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('usuarios/agregar');
        $this->load->view('templates/footer');
    }

    public function check_email($email) {
        if ($this->Usuario_model->email_exists($email)) {
            $this->form_validation->set_message('check_email', 'El correo electrónico ya está en uso.');
            return FALSE;
        }
        return TRUE;
    }

    public function editar($usuario_id) {
        if ($this->input->post()) {
            $this->form_validation->set_rules('nombre_completo', 'Nombre Completo', 'required');
            $this->form_validation->set_rules('email', 'Correo Electrónico', 'required|valid_email');
            $this->form_validation->set_rules('nombre_usuario', 'Nombre de Usuario', 'required');
            $this->form_validation->set_rules('rol', 'Rol', 'required');
    
            if ($this->form_validation->run() === TRUE) {
                $data = [
                    'nombre_completo' => $this->input->post('nombre_completo'),
                    'email' => $this->input->post('email'),
                    'nombre_usuario' => $this->input->post('nombre_usuario'),
                    'rol' => $this->input->post('rol')
                ];
    
                if ($this->input->post('password')) {
                    $data['password'] = md5($this->input->post('password'));
                }
    
                $this->Usuario_model->editar_usuario($usuario_id, $data);
                $this->session->set_flashdata('mensaje', 'Usuario actualizado correctamente.');
                redirect('usuarios');
            } else {
                $this->session->set_flashdata('error', validation_errors());
            }
        }
    
        $data['usuario'] = $this->Usuario_model->obtener_usuario_por_id($usuario_id);
        if (!$data['usuario']) {
            show_404();
        }
    
        $data['roles'] = ['Admin', 'Usuario', 'Gerente'];
    
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('usuarios/editar', $data);
        $this->load->view('templates/footer');
    }
    
    public function eliminar($usuario_id) {
        $this->Usuario_model->eliminar_usuario($usuario_id);
        $this->session->set_flashdata('mensaje', 'Usuario eliminado correctamente.');
        redirect('usuarios');
    }

    public function eliminados() {
        $data['usuarios'] = $this->Usuario_model->obtener_usuarios_eliminados();
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('usuarios/eliminados', $data);
        $this->load->view('templates/footer');
    }

    public function habilitar($usuario_id) {
        $this->Usuario_model->habilitar_usuario($usuario_id);
        $this->session->set_flashdata('mensaje', 'Usuario habilitado correctamente.');
        redirect('usuarios');
    }

    private function generar_contraseña($longitud = 8) {
        $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $contraseña = '';
        for ($i = 0; $i < $longitud; $i++) {
            $contraseña .= $caracteres[rand(0, strlen($caracteres) - 1)];
        }
        return $contraseña;
    }

    private function _enviar_correo($email, $nombre_completo, $nombre_usuario, $password) {
        $this->email->from('crenasasrl2@gmail.com', 'CRENASA SRL');
        $this->email->to($email);
        $this->email->subject('Nueva Cuenta Creada');
        $this->email->message(
            "Hola $nombre_completo,<br><br>" .
            "Se ha creado una cuenta para usted en nuestro sistema. Aquí están los detalles de su cuenta:<br><br>" .
            "Nombre Completo: $nombre_completo<br>" .
            "Nombre de Usuario: $nombre_usuario<br>" .
            "Contraseña: $password<br><br>" .
            "Por favor, inicie sesión utilizando estos detalles. Si necesita ayuda, no dude en contactarnos.<br><br>" .
            "Saludos,<br>" .
            "El equipo de CRENASA SRL"
        );

        if (!$this->email->send()) {
            log_message('error', 'No se pudo enviar el correo electrónico a: ' . $email);
        }
    }
}
?>
