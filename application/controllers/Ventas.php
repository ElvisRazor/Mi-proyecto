<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ventas extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Venta_model');
        $this->load->helper('url');
        $this->load->library(['session', 'form_validation']);
    }

    public function index() {
        $data['ventas'] = $this->Venta_model->obtener_ventas();
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('ventas/index', $data);
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
            $this->form_validation->set_rules('totalVenta', 'Total Venta', 'required|numeric');

            if ($this->form_validation->run() === TRUE) {
                $data = [
                    'idProveedor' => $this->input->post('idProveedor'),
                    'idUsuario' => $this->input->post('idUsuario'),
                    'tipoComprobante' => $this->input->post('tipoComprobante'),
                    'serieComprobante' => $this->input->post('serieComprobante'),
                    'numComprobante' => $this->input->post('numComprobante'),
                    'impuesto' => $this->input->post('impuesto'),
                    'totalVenta' => $this->input->post('totalVenta')
                ];

                if ($this->Venta_model->agregar_venta($data)) {
                    $this->session->set_flashdata('mensaje', 'Venta agregada correctamente.');
                    redirect('compras');
                } else {
                    $this->session->set_flashdata('error', 'Error al agregar la venta.');
                }
            } else {
                $this->session->set_flashdata('error', validation_errors());
            }
        }

        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('ventas/agregar');
        $this->load->view('templates/footer');
    }

    public function editar($idventa) {
        if ($this->input->post()) {
            $this->form_validation->set_rules('idProveedor', 'Proveedor', 'required');
            $this->form_validation->set_rules('idUsuario', 'Usuario', 'required');
            $this->form_validation->set_rules('tipoComprobante', 'Tipo de Comprobante', 'required');
            $this->form_validation->set_rules('serieComprobante', 'Serie de Comprobante', 'required');
            $this->form_validation->set_rules('numComprobante', 'Número de Comprobante', 'required');
            $this->form_validation->set_rules('impuesto', 'Impuesto', 'required|numeric');
            $this->form_validation->set_rules('totalVenta', 'Total Venta', 'required|numeric');

            if ($this->form_validation->run() === TRUE) {
                $data = [
                    'idProveedor' => $this->input->post('idProveedor'),
                    'idUsuario' => $this->input->post('idUsuario'),
                    'tipoComprobante' => $this->input->post('tipoComprobante'),
                    'serieComprobante' => $this->input->post('serieComprobante'),
                    'numComprobante' => $this->input->post('numComprobante'),
                    'impuesto' => $this->input->post('impuesto'),
                    'totalVenta' => $this->input->post('totalVenta')
                ];

                $this->Compra_model->editar_venta($idVenta, $data);
                $this->session->set_flashdata('mensaje', 'Venta actualizada correctamente.');
                redirect('ventas');
            } else {
                $this->session->set_flashdata('error', validation_errors());
            }
        }

        $data['venta'] = $this->Compra_model->obtener_venta_por_id($idVenta);
        if (!$data['venta']) {
            show_404();
        }

        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('ventas/editar', $data);
        $this->load->view('templates/footer');
    }

    public function eliminar($idVenta) {
        $this->Venta_model->eliminar_venta($idVenta);
        $this->session->set_flashdata('mensaje', 'Venta eliminada correctamente.');
        redirect('ventas');
    }
}
?>
