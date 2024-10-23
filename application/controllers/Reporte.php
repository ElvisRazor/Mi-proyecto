<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reporte extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Compra_model');
        $this->load->helper('url');
        $this->load->library(['session', 'form_validation']);
        require_once(APPPATH . 'libraries/tcpdf/tcpdf.php'); // Cargar TCPDF manualmente
    }

    public function consultaCompra() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('fechaInicio', 'Fecha Inicio', 'required');
            $this->form_validation->set_rules('fechaFin', 'Fecha Fin', 'required');

            if ($this->form_validation->run() === TRUE) {
                $fechaInicio = $this->input->post('fechaInicio');
                $fechaFin = $this->input->post('fechaFin');
                $data['compras'] = $this->Compra_model->obtenerComprasPorFechas($fechaInicio, $fechaFin);
            } else {
                $this->session->set_flashdata('error', validation_errors());
            }
        }

        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('reporte/consultaCompra', isset($data) ? $data : []);
        $this->load->view('templates/footer');
    }
}
?>
