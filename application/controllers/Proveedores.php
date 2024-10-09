<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Proveedores extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Proveedor_model');
        $this->load->helper('url');
        $this->load->library(['session', 'form_validation']);
    }

    public function index() {
        $data['proveedor'] = $this->Proveedor_model->obtener_proveedores_activos();
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('proveedores/index', $data);
        $this->load->view('templates/footer');
    }

    public function agregar() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('nombre', 'Nombre Completo', 'required|regex_match[/^[a-zA-Z\s]+$/]', [
                'regex_match' => 'El nombre completo solo puede contener letras y espacios.'
            ]);
            $this->form_validation->set_rules('email', 'Correo Electrónico', 'required|valid_email|callback_check_email');
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
                    'tipoDocumento' => $this->input->post('tipoDocumento'),
                    'numDocumento' => $this->input->post('numDocumento'),
                    'direccion' => $this->input->post('direccion'),
                    'telefono' => $this->input->post('telefono')
                ];

                if ($this->Proveedor_model->agregar_proveedor($data)) {
                    $this->session->set_flashdata('mensaje', 'Proveedor agregado correctamente.');
                    redirect('proveedores');
                } else {
                    $this->session->set_flashdata('error', 'Error al agregar el proveedor.');
                }
            } else {
                $this->session->set_flashdata('error', validation_errors());
            }
        }

        $data['tipos_documento'] = ['Ci/Nit', 'Pasaporte'];

        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('proveedores/agregar', $data);
        $this->load->view('templates/footer');
    }

    public function editar($idProveedor) {
        if ($this->input->post()) {
            $this->form_validation->set_rules('nombre', 'Nombre Completo', 'required|regex_match[/^[a-zA-Z\s]+$/]', [
                'regex_match' => 'El nombre completo solo puede contener letras y espacios.'
            ]);
            $this->form_validation->set_rules('email', 'Correo Electrónico', 'required|valid_email');
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
                    'tipoDocumento' => $this->input->post('tipoDocumento'),
                    'numDocumento' => $this->input->post('numDocumento'),
                    'direccion' => $this->input->post('direccion'),
                    'telefono' => $this->input->post('telefono')
                ];

                $this->Proveedor_model->editar_proveedor($idProveedor, $data);
                $this->session->set_flashdata('mensaje', 'Proveedor actualizado correctamente.');
                redirect('proveedores');
            } else {
                $this->session->set_flashdata('error', validation_errors());
            }
        }

        $data['proveedor'] = $this->Proveedor_model->obtener_proveedor_por_id($idProveedor);
        if (!$data['proveedor']) {
            show_404();
        }

        $data['tipos_documento'] = ['Ci/Nit', 'Pasaporte'];

        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('proveedores/editar', $data);
        $this->load->view('templates/footer');
    }

    public function eliminar($idProveedor) {
        $this->Proveedor_model->eliminar_proveedor($idProveedor);
        $this->session->set_flashdata('mensaje', 'Proveedor eliminado correctamente.');
        redirect('proveedores');
    }

    public function eliminados() {
        $data['proveedor'] = $this->Proveedor_model->obtener_proveedores_eliminados();
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('proveedores/eliminados', $data);
        $this->load->view('templates/footer');
    }

    public function habilitar($idProveedor) {
        $this->Proveedor_model->habilitar_proveedor($idProveedor);
        $this->session->set_flashdata('mensaje', 'Proveedor habilitado correctamente.');
        redirect('proveedores/eliminados');
    }

    public function check_email($email) {
        if ($this->Proveedor_model->email_exists($email)) {
            $this->form_validation->set_message('check_email', 'El correo electrónico ya está en uso.');
            return FALSE;
        }
        return TRUE;
    }
}
?>
