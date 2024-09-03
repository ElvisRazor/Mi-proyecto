<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categorias extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Categoria_model');
        $this->load->helper('url');
        $this->load->library(['session', 'form_validation']);
    }

    public function index() {
        $data['categorias'] = $this->Categoria_model->obtener_categorias_activos();
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('categorias/index', $data);
        $this->load->view('templates/footer');
    }

    public function agregar() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('nombre', 'Nombre', 'required');
            $this->form_validation->set_rules('descripcion', 'Descripción', 'required');

            if ($this->form_validation->run() === TRUE) {
                $data = [
                    'nombre' => $this->input->post('nombre'),
                    'descripcion' => $this->input->post('descripcion')
                ];

                if ($this->Categoria_model->agregar_categoria($data)) {
                    $this->session->set_flashdata('mensaje', 'Categoría agregada correctamente.');
                    redirect('categorias');
                } else {
                    $this->session->set_flashdata('error', 'Error al agregar la categoría.');
                }
            } else {
                $this->session->set_flashdata('error', validation_errors());
            }
        }

        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('categorias/agregar');
        $this->load->view('templates/footer');
    }

    public function editar($idCategoria) {
        if ($this->input->post()) {
            $this->form_validation->set_rules('nombre', 'Nombre', 'required');
            $this->form_validation->set_rules('descripcion', 'Descripción', 'required');

            if ($this->form_validation->run() === TRUE) {
                $data = [
                    'nombre' => $this->input->post('nombre'),
                    'descripcion' => $this->input->post('descripcion')
                ];

                $this->Categoria_model->editar_categoria($idCategoria, $data);
                $this->session->set_flashdata('mensaje', 'Categoría actualizada correctamente.');
                redirect('categorias');
            } else {
                $this->session->set_flashdata('error', validation_errors());
            }
        }

        $data['categoria'] = $this->Categoria_model->obtener_categoria_por_id($idCategoria);
        if (!$data['categoria']) {
            show_404();
        }

        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('categorias/editar', $data);
        $this->load->view('templates/footer');
    }

    public function eliminar($idCategoria) {
        $this->Categoria_model->eliminar_categoria($idCategoria);
        $this->session->set_flashdata('mensaje', 'Categoría eliminada correctamente.');
        redirect('categorias');
    }

    public function inactivos() {
        $data['categorias'] = $this->Categoria_model->obtener_categorias_inactivos();
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('categorias/inactivos', $data);
        $this->load->view('templates/footer');
    }

    public function habilitar($idCategoria) {
        $this->Categoria_model->habilitar_categoria($idCategoria);
        $this->session->set_flashdata('mensaje', 'Categoría habilitada correctamente.');
        redirect('categorias/inactivos');
    }
}
?>
