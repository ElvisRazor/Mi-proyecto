<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categorias extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Categoria_model');
        $this->load->helper('url');
        $this->load->library(['session', 'form_validation']);
        require_once(APPPATH . 'libraries/tcpdf/tcpdf.php'); // Cargar TCPDF manualmente
    }

    public function index() {
        $data['categoria'] = $this->Categoria_model->obtener_categorias_activos();
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
        $data['categoria'] = $this->Categoria_model->obtener_categorias_inactivos();
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
    public function imprimir_todas() {
        // Obtener todas las categorías
        $categorias = $this->Categoria_model->obtener_categorias_activos();
    
        if (empty($categorias)) {
            $this->session->set_flashdata('error', 'No se encontraron categorías para imprimir.');
            redirect('categorias');
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
        <h1 style="text-align:center; color:#0c4b93;">Lista de Categorías</h1>';
    
        $html .= '<table border="1" cellpadding="5" style="border-collapse: collapse; width:100%;">
                      <thead>
                          <tr style="background-color: #0c4b93; color: white;">
                              <th style="text-align:left;">N°</th>
                              <th style="text-align:left;">Nombre</th>
                              <th style="text-align:left;">Descripción</th>
                              <th style="text-align:left;">Estado</th>
                          </tr>
                      </thead>
                      <tbody>';
    
        foreach ($categorias as $categoria) {
            $html .= '<tr>
                        <td>' . htmlspecialchars($categoria['idCategoria']) . '</td>
                        <td>' . htmlspecialchars($categoria['nombre']) . '</td>
                        <td>' . htmlspecialchars($categoria['descripcion']) . '</td>
                        <td>' . ($categoria['estado'] == '1' ? 'ACTIVA' : 'INACTIVA') . '</td>
                    </tr>';
        }
    
        $html .= '</tbody>
                  </table>';
    
        // Agregar el contenido al PDF
        $pdf->writeHTML($html, true, false, true, false, '');
    
        // Salida del PDF
        $pdf->Output('resumen_categorias.pdf', 'I');
    }    
}
?>
