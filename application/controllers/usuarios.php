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
        $data['usuario'] = $this->Usuario_model->obtener_usuarios_activos();
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('usuarios/index', $data);
        $this->load->view('templates/footer');
    }

    public function agregar() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('nombre', 'Nombre Completo', 'required|regex_match[/^[a-zA-Z\s]+$/]', [
                'regex_match' => 'El nombre completo solo puede contener letras y espacios.'
            ]);
            $this->form_validation->set_rules('email', 'Correo Electrónico', 'required|valid_email|callback_check_email');
            $this->form_validation->set_rules('rol', 'Rol', 'required');
            $this->form_validation->set_rules('tipoDocumento', 'Tipo de Documento', 'required');
            $this->form_validation->set_rules('numDocumento', 'Número de Documento', 'required|regex_match[/^[a-zA-Z0-9-]+$/]', [
                'regex_match' => 'El número de documento solo puede contener letras, números y guiones.'
            ]);
            $this->form_validation->set_rules('direccion', 'Dirección', 'required');
            $this->form_validation->set_rules('telefono', 'Teléfono', 'required|numeric', [
                'numeric' => 'El teléfono solo puede contener números.'
            ]);

            if ($this->form_validation->run() === TRUE) {
                $password = $this->generar_contraseña();
                $data = [
                    'nombre' => $this->input->post('nombre'),
                    'email' => $this->input->post('email'),
                    'rol' => $this->input->post('rol'),
                    'tipoDocumento' => $this->input->post('tipoDocumento'),
                    'numDocumento' => $this->input->post('numDocumento'),
                    'direccion' => $this->input->post('direccion'),
                    'telefono' => $this->input->post('telefono'),
                    'password' => md5($password),
                    'estado' => 1
                ];

                if ($this->Usuario_model->agregar_usuario($data)) {
                    $this->_enviar_correo($this->input->post('email'), $this->input->post('nombre'), $password);
                    $this->session->set_flashdata('mensaje', 'Usuario agregado correctamente. Se ha enviado la contraseña al correo electrónico.');
                    redirect('usuarios');
                } else {
                    $this->session->set_flashdata('error', 'Error al agregar el usuario.');
                }
            } else {
                $this->session->set_flashdata('error', validation_errors());
            }
        }

        $data['tipos_documento'] = ['Ci/Nit', 'Pasaporte'];
        $data['roles'] = ['vendedor', 'administrador', 'cliente', 'proveedor'];

        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('usuarios/agregar', $data);
        $this->load->view('templates/footer');
    }

    public function editar($idUsuario) {
        if ($this->input->post()) {
            $this->form_validation->set_rules('nombre', 'Nombre Completo', 'required|regex_match[/^[a-zA-Z\s]+$/]', [
                'regex_match' => 'El nombre completo solo puede contener letras y espacios.'
            ]);
            $this->form_validation->set_rules('email', 'Correo Electrónico', 'required|valid_email');
            $this->form_validation->set_rules('rol', 'Rol', 'required');
            $this->form_validation->set_rules('tipoDocumento', 'Tipo de Documento', 'required');
            $this->form_validation->set_rules('numDocumento', 'Número de Documento', 'required|regex_match[/^[a-zA-Z0-9-]+$/]', [
                'regex_match' => 'El número de documento solo puede contener letras, números y guiones.'
            ]);
            $this->form_validation->set_rules('direccion', 'Dirección', 'required');
            $this->form_validation->set_rules('telefono', 'Teléfono', 'required|numeric', [
                'numeric' => 'El teléfono solo puede contener números.'
            ]);

            if ($this->form_validation->run() === TRUE) {
                $data = [
                    'nombre' => $this->input->post('nombre'),
                    'email' => $this->input->post('email'),
                    'rol' => $this->input->post('rol'),
                    'tipoDocumento' => $this->input->post('tipoDocumento'),
                    'numDocumento' => $this->input->post('numDocumento'),
                    'direccion' => $this->input->post('direccion'),
                    'telefono' => $this->input->post('telefono')
                ];

                if ($this->input->post('password')) {
                    $data['password'] = md5($this->input->post('password'));
                }

                $this->Usuario_model->editar_usuario($idUsuario, $data);
                $this->session->set_flashdata('mensaje', 'Usuario actualizado correctamente.');
                redirect('usuarios');
            } else {
                $this->session->set_flashdata('error', validation_errors());
            }
        }

        $data['usuario'] = $this->Usuario_model->obtener_usuario_por_id($idUsuario);
        if (!$data['usuario']) {
            show_404();
        }

        $data['tipos_documento'] = ['Ci/Nit', 'Pasaporte'];
        $data['roles'] = ['vendedor', 'administrador'];

        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('usuarios/editar', $data);
        $this->load->view('templates/footer');
    }

    public function eliminar($idUsuario) {
        $this->Usuario_model->eliminar_usuario($idUsuario);
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

    public function habilitar($idUsuario) {
        $this->Usuario_model->habilitar_usuario($idUsuario);
        $this->session->set_flashdata('mensaje', 'Usuario habilitado correctamente.');
        redirect('usuarios/eliminados');
    }

    public function check_email($email) {
        if ($this->Usuario_model->email_exists($email)) {
            $this->form_validation->set_message('check_email', 'El correo electrónico ya está en uso.');
            return FALSE;
        }
        return TRUE;
    }

    private function generar_contraseña() {
        return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);
    }

    private function _enviar_correo($email, $nombre, $password) {
        $this->email->from($this->config->item('smtp_user'), 'Administrador');
        $this->email->to($email);
        $this->email->subject('Creación de cuenta');
        $this->email->message("Hola $nombre,\n\nTu cuenta ha sido creada exitosamente. Aquí tienes tu contraseña: $password\n\nPor favor, cambia tu contraseña después de iniciar sesión.");

        if (!$this->email->send()) {
            $this->session->set_flashdata('error', 'No se pudo enviar el correo de confirmación.');
        }
    }
}
?>
