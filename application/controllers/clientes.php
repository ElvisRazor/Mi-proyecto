<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Clientes extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Cliente_model');
        $this->load->helper('url');
        $this->load->library(['session', 'form_validation', 'email']);
        $this->load->config('email');
        require_once(APPPATH . 'libraries/tcpdf/tcpdf.php'); // Cargar TCPDF manualmente
    }

    public function index() {
        $data['cliente'] = $this->Cliente_model->obtener_clientes_activos();
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('clientes/index', $data);
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

                if ($this->Cliente_model->agregar_cliente($data)) {
                    $this->session->set_flashdata('mensaje', 'Cliente agregado correctamente.');
                    redirect('clientes');
                } else {
                    $this->session->set_flashdata('error', 'Error al agregar el cliente.');
                }
            } else {
                $this->session->set_flashdata('error', validation_errors());
            }
        }

        $data['tipos_documento'] = ['Ci/Nit', 'Pasaporte'];

        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('clientes/agregar', $data);
        $this->load->view('templates/footer');
    }

    public function editar($idCliente) {
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

                $this->Cliente_model->editar_cliente($idCliente, $data);
                $this->session->set_flashdata('mensaje', 'Cliente actualizado correctamente.');
                redirect('clientes');
            } else {
                $this->session->set_flashdata('error', validation_errors());
            }
        }

        $data['cliente'] = $this->Cliente_model->obtener_cliente_por_id($idCliente);
        if (!$data['cliente']) {
            show_404();
        }

        $data['tipos_documento'] = ['Ci/Nit', 'Pasaporte'];

        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('clientes/editar', $data);
        $this->load->view('templates/footer');
    }

    public function eliminar($idCliente) {
        $this->Cliente_model->eliminar_cliente($idCliente);
        $this->session->set_flashdata('mensaje', 'Cliente eliminado correctamente.');
        redirect('clientes');
    }

    public function eliminados() {
        $data['cliente'] = $this->Cliente_model->obtener_clientes_eliminados();
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('clientes/eliminados', $data);
        $this->load->view('templates/footer');
    }

    public function habilitar($idCliente) {
        $this->Cliente_model->habilitar_cliente($idCliente);
        $this->session->set_flashdata('mensaje', 'Cliente habilitado correctamente.');
        redirect('clientes/eliminados');
    }

    public function check_email($email) {
        if ($this->Cliente_model->email_exists($email)) {
            $this->form_validation->set_message('check_email', 'El correo electrónico ya está en uso.');
            return FALSE;
        }
        return TRUE;
    }

    public function imprimir() {
        // Obtener todos los clientes
        $clientes = $this->Cliente_model->obtener_clientes_activos();
    
        if (empty($clientes)) {
            $this->session->set_flashdata('error', 'No se encontraron clientes para imprimir.');
            redirect('clientes');
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
        <h1 style="text-align:center; color:#0c4b93;">Lista de Clientes</h1>';
    
        $html .= '<table border="1" cellpadding="10" style="border-collapse: collapse; width:100%;">
                      <thead>
                          <tr style="background-color: #0c4b93; color: white;">
                              <th style="text-align:left;">N°</th>
                              <th style="text-align:left;">Nombre</th>
                              <th style="text-align:left;">Tipo Documento</th>
                              <th style="text-align:left;">Dirección</th>
                              <th style="text-align:left;">Correo Electrónico</th>
                              <th style="text-align:left;">Estado</th>
                          </tr>
                      </thead>
                      <tbody>';
    
        foreach ($clientes as $cliente) {
            $html .= '<tr>
                        <td>' . htmlspecialchars($cliente['idCliente']) . '</td>
                        <td>' . htmlspecialchars($cliente['nombre']) . '</td>
                        <td>' . htmlspecialchars($cliente['tipoDocumento']) . '</td>
                        <td>' . htmlspecialchars($cliente['direccion']) . '</td>
                        <td>' . htmlspecialchars($cliente['email']) . '</td>
                        <td>' . ($cliente['estado'] == '1' ? 'ACTIVO' : 'INACTIVO') . '</td>
                    </tr>';
        }
    
        $html .= '</tbody>
                  </table>';
    
        // Agregar el contenido al PDF
        $pdf->writeHTML($html, true, false, true, false, '');
    
        // Salida del PDF
        $pdf->Output('resumen_clientes.pdf', 'I');
    }    
}
?>
