<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Productos extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Producto_model');
        $this->load->helper(['url', 'form']);
        $this->load->library(['session', 'form_validation', 'upload']);
        $this->load->config('upload');
        require_once(APPPATH . 'libraries/tcpdf/tcpdf.php'); // Cargar TCPDF manualmente
    }

    public function index() {
        $data['producto'] = $this->Producto_model->obtener_productos_activos();
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
            $this->form_validation->set_rules('precio', 'Precio', 'required|numeric');
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
                    'precio' => $this->input->post('precio'),
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
    
        $data['categoria'] = $this->Producto_model->obtener_categorias();
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
            $this->form_validation->set_rules('precio', 'Precio', 'required|numeric');
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
                    'precio' => $this->input->post('precio'),
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
    
        $data['categoria'] = $this->Producto_model->obtener_categorias();
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
    public function imprimir() {
        // Obtener todos los productos
        $productos = $this->Producto_model->obtener_productos_activos();
    
        if (empty($productos)) {
            $this->session->set_flashdata('error', 'No se encontraron productos para imprimir.');
            redirect('productos');
        }
    
        // Crear el PDF
        $pdf = new TCPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->SetPrintHeader(false);
        $pdf->AddPage();
    
        // Agregar imagen de marca de agua
        $imagePath = FCPATH . 'assets/img/pisosbol2.PNG';
        if (file_exists($imagePath)) {
            list($width, $height) = getimagesize($imagePath);
            $scaleFactor = 0.3;
            $x = ($pdf->getPageWidth() - ($width * $scaleFactor)) / 2;
            $y = ($pdf->getPageHeight() - ($height * $scaleFactor)) / 2;
            $pdf->SetAlpha(0.3);
            $pdf->Image($imagePath, $x, $y, $width * $scaleFactor, $height * $scaleFactor, 'PNG');
            $pdf->SetAlpha(1);
        } else {
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->Cell(0, 10, 'Error: No se pudo cargar la imagen de membretado.', 0, 1, 'C');
        }
    
        // Contenido del PDF
        $html = '
        <h1 style="text-align:center; color:#0c4b93;">Lista de Productos</h1>';
    
        $html .= '<table border="1" cellpadding="10" style="border-collapse: collapse; width:100%;">
                      <thead>
                          <tr style="background-color: #0c4b93; color: white;">
                              <th style="text-align:left;">N°</th>
                              <th style="text-align:left;">Nombre</th>
                              <th style="text-align:left;">Código</th>
                              <th style="text-align:left;">Precio</th>
                              <th style="text-align:left;">Stock</th>
                              <th style="text-align:left;">Descripción</th>
                              <th style="text-align:left;">Estado</th>
                          </tr>
                      </thead>
                      <tbody>';
    
        foreach ($productos as $producto) {
            $html .= '<tr>
                        <td>' . htmlspecialchars($producto['idProducto']) . '</td>
                        <td>' . htmlspecialchars($producto['nombre']) . '</td>
                        <td>' . htmlspecialchars($producto['codigo']) . '</td>
                        <td>' . number_format($producto['precio'], 2) . '</td>
                        <td>' . htmlspecialchars($producto['stock']) . '</td>
                        <td>' . htmlspecialchars($producto['descripcion']) . '</td>
                        <td>' . ($producto['estado'] == '1' ? 'ACTIVO' : 'INACTIVO') . '</td>
                    </tr>';
        }
    
        $html .= '</tbody>
                  </table>';
    
        // Agregar el contenido al PDF
        $pdf->writeHTML($html, true, false, true, false, '');
    
        // Salida del PDF
        $pdf->Output('resumen_productos.pdf', 'I');
    }
}
?>
