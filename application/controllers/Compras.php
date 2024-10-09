<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Compras extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Compra_model');
        $this->load->helper('url');
        $this->load->library(['session', 'form_validation']);
    }

    public function index() {
        $data['compra'] = $this->Compra_model->obtener_compras();
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('compras/index', $data);
        $this->load->view('templates/footer');
    }

    public function agregar() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('idProveedor', 'Proveedor', 'required');
            $this->form_validation->set_rules('idUsuario', 'Usuario', 'required');
            $this->form_validation->set_rules('tipoComprobante', 'Tipo de Comprobante', 'required');
            $this->form_validation->set_rules('serieComprobante', 'Serie de Comprobante', 'required');
            $this->form_validation->set_rules('numComprobante', 'Número de Comprobante', 'required');
            $this->form_validation->set_rules('impuesto', 'Impuesto', 'required|numeric');
            $this->form_validation->set_rules('totalCompra', 'Total Compra', 'required|numeric');

            if ($this->form_validation->run() === TRUE) {
                $data = [
                    'idProveedor' => $this->input->post('idProveedor'),
                    'idUsuario' => $this->input->post('idUsuario'),
                    'tipoComprobante' => $this->input->post('tipoComprobante'),
                    'serieComprobante' => $this->input->post('serieComprobante'),
                    'numComprobante' => $this->input->post('numComprobante'),
                    'impuesto' => $this->input->post('impuesto'),
                    'totalCompra' => $this->input->post('totalCompra')
                ];

                if ($this->Compra_model->agregar_compra($data)) {
                    $this->session->set_flashdata('mensaje', 'Compra agregada correctamente.');
                    redirect('compras');
                } else {
                    $this->session->set_flashdata('error', 'Error al agregar la compra.');
                }
            } else {
                $this->session->set_flashdata('error', validation_errors());
            }
        }

        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('compras/agregar');
        $this->load->view('templates/footer');
    }

    public function editar($idCompra) {
        if ($this->input->post()) {
            $this->form_validation->set_rules('idProveedor', 'Proveedor', 'required');
            $this->form_validation->set_rules('idUsuario', 'Usuario', 'required');
            $this->form_validation->set_rules('tipoComprobante', 'Tipo de Comprobante', 'required');
            $this->form_validation->set_rules('serieComprobante', 'Serie de Comprobante', 'required');
            $this->form_validation->set_rules('numComprobante', 'Número de Comprobante', 'required');
            $this->form_validation->set_rules('impuesto', 'Impuesto', 'required|numeric');
            $this->form_validation->set_rules('totalCompra', 'Total Compra', 'required|numeric');

            if ($this->form_validation->run() === TRUE) {
                $data = [
                    'idProveedor' => $this->input->post('idProveedor'),
                    'idUsuario' => $this->input->post('idUsuario'),
                    'tipoComprobante' => $this->input->post('tipoComprobante'),
                    'serieComprobante' => $this->input->post('serieComprobante'),
                    'numComprobante' => $this->input->post('numComprobante'),
                    'impuesto' => $this->input->post('impuesto'),
                    'totalCompra' => $this->input->post('totalCompra')
                ];

                $this->Compra_model->editar_compra($idCompra, $data);
                $this->session->set_flashdata('mensaje', 'Compra actualizada correctamente.');
                redirect('compras');
            } else {
                $this->session->set_flashdata('error', validation_errors());
            }
        }

        $data['compra'] = $this->Compra_model->obtener_compra_por_id($idCompra);
        if (!$data['compra']) {
            show_404();
        }

        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('compras/editar', $data);
        $this->load->view('templates/footer');
    }

    public function eliminar($idCompra) {
        $this->Compra_model->eliminar_compra($idCompra);
        $this->session->set_flashdata('mensaje', 'Compra eliminada correctamente.');
        redirect('compras');
    }
}
?>
