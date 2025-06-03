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
            // CAMBIO 1: Añade 'permit_empty' a la regla del email
            $this->form_validation->set_rules('email', 'Correo Electrónico', 'permit_empty|valid_email|callback_check_email'); 
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
                    // CAMBIO 2: Asegúrate de que, si el email está vacío, se guarde como NULL o una cadena vacía
                    'email' => $this->input->post('email') ? $this->input->post('email') : null, 
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
                // Mostrar todos los errores de validación
                $validation_errors = validation_errors();
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
            $this->form_validation->set_rules('email', 'Correo Electrónico', 'valid_email|callback_check_email');
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
    // Obtener el rol del usuario desde la sesión
    $rol = $this->session->userdata('rol');

    // Verificar si el rol es "Administrador"
    if ($rol != 'administrador') {
        // Si no es administrador, redirigir a otra página o mostrar mensaje de error
        $this->session->set_flashdata('mensaje', 'No tienes acceso a esta vista');
        redirect('clientes'); // Cambia 'dashboard' por la ruta que desees
    }

    // Si es administrador, cargar la vista de clientes eliminados
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
        // Si el email está vacío o contiene solo espacios en blanco,
        // significa que el usuario no ingresó un correo y es válido porque es opcional.
        if (empty(trim($email))) {
            return TRUE; // El campo está vacío y es válido para la validación 'check_email'.
        }

        // Si el email no está vacío, entonces sí procedemos a verificar si ya está en uso.
        // Asegúrate de que Cliente_model esté cargado y que el método email_exists()
        // en Cliente_model verifique la tabla 'cliente'.
        if ($this->Cliente_model->email_exists($email)) {
            $this->form_validation->set_message('check_email', 'El correo electrónico ya está en uso.');
            return FALSE; // El correo electrónico ya existe, por lo tanto, no es válido.
        }
        return TRUE; // El correo electrónico es único y válido.
    }

    public function imprimir() {
        // Obtener todos los clientes
        $clientes = $this->Cliente_model->obtener_clientes_activos();
    
        if (empty($clientes)) {
            $this->session->set_flashdata('error', 'No se encontraron clientes para imprimir.');
            redirect('clientes');
        }
    
        // Crear el PDF
        $pdf = new TCPDF('L', 'mm', 'A4');
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetMargins(10, 25, 10); // Márgenes ajustados
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false); // Sin pie de página
        $pdf->AddPage();
    
        // Agregar el logo en la esquina superior izquierda
    $logoPath = FCPATH . 'assets/img/logoTrans.PNG';
    if (file_exists($logoPath)) {
        $pdf->Image($logoPath, 6, -6, 80, 52, 'PNG'); // Ancho=50mm, Alto=30mm
    }

    // Obtener datos del usuario
    $nombreUsuario = $this->session->userdata('nombre');
    $primerApellido = $this->session->userdata('primerApellido');
    $segundoApellido = $this->session->userdata('segundoApellido');
    $nombreCompleto = $nombreUsuario . ' ' . $primerApellido . ' ' . $segundoApellido;

    // Obtener fecha y hora de impresión
    $fechaHora = date('d/m/Y H:i:s');

    // Agregar información del usuario y fecha en la esquina superior derecha
    $pdf->SetFont('helvetica', '', 8);
    $pdf->SetXY(200, 5);
    $pdf->Cell(0, 5, "Fecha y Hora: $fechaHora", 0, 1, 'R');
    $pdf->SetXY(200, 12);
    $pdf->Cell(0, 5, "Usuario: $nombreCompleto", 0, 1, 'R');
        // Agregar imagen de marca de agua
        $imagePath = FCPATH . 'assets/img/logoTrans.PNG';
        if (file_exists($imagePath)) {
            list($width, $height) = getimagesize($imagePath);
            $scaleFactor = 0.1;
            $x = ($pdf->getPageWidth() - ($width * $scaleFactor)) / 2;
            $y = ($pdf->getPageHeight() - ($height * $scaleFactor)) / 2;
            $pdf->SetAlpha(0.3);
            $pdf->Image($imagePath, $x, $y, $width * $scaleFactor, $height * $scaleFactor, 'PNG');
            $pdf->SetAlpha(1);
        }
        $pdf->Ln(6);
        // Contenido del PDF
        $html = '
        <div style="text-align:center;">
        <h1 style="text-align:center; color:#0c4b93;">Lista de Clientes</h1>
        <table border="1" cellpadding="5" style="border-collapse: collapse; width:100%;">
                      <thead>
                          <tr style="background-color: #0c4b93; color: white;">
                              <th style="text-align:center; width:  5%;">N°</th>
                              <th style="text-align:center; width: 18%;">Nombre</th>
                              <th style="text-align:center; width: 10%;">Tipo Documento</th>
                              <th style="text-align:center; width: 10%;">Número Documento</th>
                              <th style="text-align:center; width: 12%;">Teléfono</th>
                              <th style="text-align:center; width: 18%;">Dirección</th>
                              <th style="text-align:center; width: 18%;">Correo Electrónico</th>
                              <th style="text-align:center; width:  8%;">Estado</th>
                          </tr>
                      </thead>
                      <tbody>';
        foreach ($clientes as $cliente) {
            $html .= '<tr>
                        <td style="text-align:center; width:  5%;">' . htmlspecialchars($cliente['idCliente']) . '</td>
                        <td style="text-align:center; width: 18%;">' . htmlspecialchars($cliente['nombre']) . '</td>
                        <td style="text-align:center; width: 10%;">' . htmlspecialchars($cliente['tipoDocumento']) . '</td>
                        <td style="text-align:center; width: 10%;">' . htmlspecialchars($cliente['numDocumento']) . '</td>
                        <td style="text-align:center; width: 12%;">' . htmlspecialchars($cliente['telefono']) . '</td>
                        <td style="text-align:center; width: 18%;">' . htmlspecialchars($cliente['direccion']) . '</td>
                        <td style="text-align:center; width: 18%;">' . htmlspecialchars($cliente['email']) . '</td>
                        <td style="text-align:center; width:  8%;">' . ($cliente['estado'] == '1' ? 'ACTIVO' : 'INACTIVO') . '</td>
                    </tr>';
        }
        $html .= '</tbody>
                  </table>
        </div>';
    
        // Agregar el contenido al PDF
        $pdf->writeHTML($html, true, false, true, false, '');
    
        // Salida del PDF
        $pdf->Output('resumen_clientes.pdf', 'I');
    }    
}
?>