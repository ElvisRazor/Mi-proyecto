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
        // Obtener el rol del usuario desde la sesión
        $rol = $this->session->userdata('rol');
    
        // Verificar si el rol es "Administrador"
        if ($rol != 'administrador') {
            // Si no es administrador, redirigir a otra página o mostrar mensaje de error
            $this->session->set_flashdata('mensaje', 'No tienes acceso a esta vista');
            redirect('categorias'); // Cambia 'dashboard' por la ruta que desees
        }
    
        // Si es administrador, cargar la vista de categorías inactivas
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
        $pdf = new TCPDF(); // L: Horizontal, mm: milímetros, A4: Tamaño
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
        $pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);
        $pdf->SetMargins(10, 25, 10); // Márgenes más amplios para espacio en el encabezado
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false); // Sin pie de página
        $pdf->AddPage();
    
        // Obtener datos del usuario
        $nombreUsuario = $this->session->userdata('nombre');
        $primerApellido = $this->session->userdata('primerApellido');
        $segundoApellido = $this->session->userdata('segundoApellido');
        $nombreCompleto = $nombreUsuario . ' ' . $primerApellido . ' ' . $segundoApellido;
    
        // Obtener fecha y hora de impresión
        $fechaHora = date('d/m/Y H:i:s');
    
        // Agregar el logo en la esquina superior izquierda
        $imagePath = FCPATH . 'assets/img/logoTrans.PNG';
        if (file_exists($imagePath)) {
            $pdf->Image($imagePath, 10, -6, 65, 45, 'PNG'); // Ancho=50mm, Alto=30mm
        }
    
        // Agregar información del usuario y fecha en la esquina superior derecha
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetXY(200, 5); // Posición para el texto (X=200, Y=5 en formato horizontal)
        $pdf->Cell(0, 5, "Fecha y Hora: $fechaHora", 0, 1, 'R');
        $pdf->SetXY(200, 12);
        $pdf->Cell(0, 5, "Usuario: $nombreCompleto", 0, 1, 'R');
    
        // Agregar imagen de marca de agua
        $watermarkPath = FCPATH . 'assets/img/logoTrans.PNG';
        if (file_exists($watermarkPath)) {
            list($width, $height) = getimagesize($watermarkPath);
            $scaleFactor = 0.1;
            $x = ($pdf->getPageWidth() - ($width * $scaleFactor)) / 2;
            $y = ($pdf->getPageHeight() - ($height * $scaleFactor)) / 2;
            $pdf->SetAlpha(0.3);
            $pdf->Image($watermarkPath, $x, $y, $width * $scaleFactor, $height * $scaleFactor, 'PNG');
            $pdf->SetAlpha(1);
        }
    
        // Establecer posición para comenzar la tabla después de la imagen
        $pdf->Ln(6); // Agregar espacio vertical (40 mm) después de la imagen
    
        // Contenido del PDF
        $html = '
        <h1 style="text-align:center; color:#0c4b93;">Lista de Categorías</h1>';
        $html .= '<table border="1" cellpadding="5" style="border-collapse: collapse; width:100%;">
                      <thead>
                          <tr style="background-color: #0c4b93; color: white;">
                              <th style="text-align:center; width: 8%;">N°</th>
                              <th style="text-align:center; width: 25%;">Nombre</th>
                              <th style="text-align:center; width: 45%;">Descripción</th>
                              <th style="text-align:center; width: 20%;">Estado</th>
                          </tr>
                      </thead>
                      <tbody>';
    
        foreach ($categorias as $categoria) {
            $html .= '<tr>
                        <td style="text-align:center; width: 8%;">' . htmlspecialchars($categoria['idCategoria']) . '</td>
                        <td style="text-align:center; width: 25%;">' . htmlspecialchars($categoria['nombre']) . '</td>
                        <td style="text-align:center; width: 45%;">' . htmlspecialchars($categoria['descripcion']) . '</td>
                        <td style="text-align:center; width: 20%;">' . ($categoria['estado'] == '1' ? 'ACTIVA' : 'INACTIVA') . '</td>
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
