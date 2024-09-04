<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Productos extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Producto_model');
        $this->load->helper(['url', 'form']);
        $this->load->library(['session', 'form_validation', 'upload']);
        $this->load->config('upload');
    }

    public function index() {
        $data['productos'] = $this->Producto_model->obtener_productos_activos();
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('productos/index', $data);
        $this->load->view('templates/footer');
    }

    public function agregar() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('nombre', 'Nombre', 'required');
            $this->form_validation->set_rules('codigo', 'Código', 'required');
            $this->form_validation->set_rules('stock', 'Stock', 'required|numeric');
            $this->form_validation->set_rules('descripcion', 'Descripción', 'required');
            $this->form_validation->set_rules('idCategoria', 'Categoría', 'required');
    
            if ($this->form_validation->run() === TRUE) {
                $config['upload_path'] = './uploads/productos/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = 2048; // 2 MB
                $this->upload->initialize($config);
    
                $imagen = '';
                if ($this->upload->do_upload('imagen')) {
                    $imagen_data = $this->upload->data();
                    $imagen = $imagen_data['file_name'];
                } else {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect('productos/agregar');
                    return;
                }
    
                $data = [
                    'nombre' => $this->input->post('nombre'),
                    'codigo' => $this->input->post('codigo'),
                    'stock' => $this->input->post('stock'),
                    'descripcion' => $this->input->post('descripcion'),
                    'idCategoria' => $this->input->post('idCategoria'),
                    'imagen' => $imagen
                ];
    
                if ($this->Producto_model->agregar_producto($data)) {
                    $this->session->set_flashdata('mensaje', 'Producto agregado correctamente.');
                    redirect('productos');
                } else {
                    $this->session->set_flashdata('error', 'Error al agregar el producto.');
                }
            } else {
                $this->session->set_flashdata('error', validation_errors());
            }
        }
    
        $data['categorias'] = $this->Producto_model->obtener_categorias();
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('productos/agregar', $data);
        $this->load->view('templates/footer');
    }    

    public function editar($idProducto) {
        if ($this->input->post()) {
            $this->form_validation->set_rules('nombre', 'Nombre', 'required');
            $this->form_validation->set_rules('codigo', 'Código', 'required');
            $this->form_validation->set_rules('stock', 'Stock', 'required|numeric');
            $this->form_validation->set_rules('descripcion', 'Descripción', 'required');
            $this->form_validation->set_rules('idCategoria', 'Categoría', 'required');
    
            if ($this->form_validation->run() === TRUE) {
                $config['upload_path'] = './uploads/productos/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = 2048; // 2 MB
                $this->upload->initialize($config);
    
                $imagen = $this->input->post('imagen_actual');
                if ($this->upload->do_upload('imagen')) {
                    $imagen_data = $this->upload->data();
                    $imagen = $imagen_data['file_name'];
                }
    
                $data = [
                    'nombre' => $this->input->post('nombre'),
                    'codigo' => $this->input->post('codigo'),
                    'stock' => $this->input->post('stock'),
                    'descripcion' => $this->input->post('descripcion'),
                    'idCategoria' => $this->input->post('idCategoria'),
                    'imagen' => $imagen
                ];
    
                $this->Producto_model->editar_producto($idProducto, $data);
                $this->session->set_flashdata('mensaje', 'Producto actualizado correctamente.');
                redirect('productos');
            } else {
                $this->session->set_flashdata('error', validation_errors());
            }
        }
    
        $data['producto'] = $this->Producto_model->obtener_producto_por_id($idProducto);
        if (!$data['producto']) {
            show_404();
        }
    
        $data['categorias'] = $this->Producto_model->obtener_categorias();
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('productos/editar', $data);
        $this->load->view('templates/footer');
    }    

    public function eliminar($idProducto) {
        $this->Producto_model->eliminar_producto($idProducto);
        $this->session->set_flashdata('mensaje', 'Producto eliminado correctamente.');
        redirect('productos');
    }
}
?>
