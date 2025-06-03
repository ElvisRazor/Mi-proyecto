<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Proveedores extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Proveedor_model');
        $this->load->helper('url');
        $this->load->library(['session', 'form_validation']);
        require_once(APPPATH . 'libraries/tcpdf/tcpdf.php'); // Cargar TCPDF manualmente
    }

    public function index() {
        $data['proveedor'] = $this->Proveedor_model->obtener_proveedores_activos();
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('proveedores/index', $data);
        $this->load->view('templates/footer');
    }

    public function agregar() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('nombre', 'Nombre Completo', 'required|regex_match[/^[a-zA-Z\s]+$/]', [
                'regex_match' => 'El nombre completo solo puede contener letras y espacios.'
            ]);
            // CAMBIO AQUÍ: 'permit_empty' permite que el campo esté vacío
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
                    // Asegúrate de que, si el email está vacío, se guarde como NULL o una cadena vacía en la base de datos
                    'email' => $this->input->post('email') ? $this->input->post('email') : null, 
                    'tipoDocumento' => $this->input->post('tipoDocumento'),
                    'numDocumento' => $this->input->post('numDocumento'),
                    'direccion' => $this->input->post('direccion'),
                    'telefono' => $this->input->post('telefono')
                ];

                if ($this->Proveedor_model->agregar_proveedor($data)) {
                    $this->session->set_flashdata('mensaje', 'Proveedor agregado correctamente.');
                    redirect('proveedores');
                } else {
                    $this->session->set_flashdata('error', 'Error al agregar el proveedor.');
                }
            } else {
                $this->session->set_flashdata('error', validation_errors());
            }
        }

        $data['tipos_documento'] = ['Ci/Nit', 'Pasaporte'];

        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('proveedores/agregar', $data);
        $this->load->view('templates/footer');
    }

    public function editar($idProveedor) {
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
                    'email' => $this->input->post('email') ? $this->input->post('email') : null, 
                    'tipoDocumento' => $this->input->post('tipoDocumento'),
                    'numDocumento' => $this->input->post('numDocumento'),
                    'direccion' => $this->input->post('direccion'),
                    'telefono' => $this->input->post('telefono')
                ];

                $this->Proveedor_model->editar_proveedor($idProveedor, $data);
                $this->session->set_flashdata('mensaje', 'Proveedor actualizado correctamente.');
                redirect('proveedores');
            } else {
                $this->session->set_flashdata('error', validation_errors());
            }
        }

        $data['proveedor'] = $this->Proveedor_model->obtener_proveedor_por_id($idProveedor);
        if (!$data['proveedor']) {
            show_404();
        }

        $data['tipos_documento'] = ['Ci/Nit', 'Pasaporte'];

        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('proveedores/editar', $data);
        $this->load->view('templates/footer');
    }

    public function eliminar($idProveedor) {
        $this->Proveedor_model->eliminar_proveedor($idProveedor);
        $this->session->set_flashdata('mensaje', 'Proveedor eliminado correctamente.');
        redirect('proveedores');
    }

    public function eliminados() {
        // Obtener el rol del usuario desde la sesión
        $rol = $this->session->userdata('rol');
    
        // Verificar si el rol es "Administrador"
        if ($rol != 'administrador') {
            // Si no es administrador, redirigir a otra página o mostrar mensaje de error
            $this->session->set_flashdata('mensaje', 'No tienes acceso a esta vista');
            redirect('proveedores'); // Cambia 'dashboard' por la ruta que desees
        }
    
        // Si es administrador, cargar la vista de proveedores eliminados
        $data['proveedor'] = $this->Proveedor_model->obtener_proveedores_eliminados();
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('proveedores/eliminados', $data);
        $this->load->view('templates/footer');
    }
    

    public function habilitar($idProveedor) {
        $this->Proveedor_model->habilitar_proveedor($idProveedor);
        $this->session->set_flashdata('mensaje', 'Proveedor habilitado correctamente.');
        redirect('proveedores/eliminados');
    }

    public function check_email($email) {
        // --- CAMBIO AQUÍ ---
        // Si el email está vacío o contiene solo espacios en blanco,
        // significa que el usuario no ingresó un correo y es válido porque es opcional.
        if (empty(trim($email))) {
            return TRUE; // El campo está vacío y es válido para la validación 'check_email'.
        }
        // --- FIN DEL CAMBIO ---
    
        // Si el email no está vacío, entonces sí procedemos a verificar si ya está en uso.
        if ($this->Proveedor_model->email_exists($email)) {
            $this->form_validation->set_message('check_email', 'El correo electrónico ya está en uso.');
            return FALSE; // El correo electrónico ya existe, por lo tanto, no es válido.
        }
        return TRUE; // El correo electrónico es único y válido.
    }

    public function imprimir()
{
    // Obtener todos los proveedores
    $proveedores = $this->Proveedor_model->obtener_proveedores_activos();

    if (empty($proveedores)) {
        $this->session->set_flashdata('error', 'No se encontraron proveedores para imprimir.');
        redirect('proveedores');
    }

    // Crear el PDF
    $pdf = new TCPDF('L', 'mm', 'A4'); // L: Horizontal, mm: Milímetros, A4: Tamaño
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
    $pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);
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
    $pdf->Ln(6); // Agregar espacio vertical (20 mm) después de la imagen

    // Contenido del PDF
    $html = '
    <div style="text-align:center;">
        <h1 style="color:#0c4b93;">Lista de Proveedores</h1>
        <table border="1" cellpadding="5" style="border-collapse: collapse; width: 100%; margin-left: auto; margin-right: auto; text-align:center;">
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

    foreach ($proveedores as $proveedor) {
        $html .= '<tr>
                    <td style="text-align:center; width: 5%;">' . htmlspecialchars($proveedor['idProveedor']) . '</td>
                    <td style="text-align:center; width: 18%;">' . htmlspecialchars($proveedor['nombre']) . '</td>
                    <td style="text-align:center; width: 10%;">' . htmlspecialchars($proveedor['tipoDocumento']) . '</td>
                    <td style="text-align:center; width: 10%;">' . htmlspecialchars($proveedor['numDocumento']) . '</td>
                    <td style="text-align:center; width: 12%;">' . htmlspecialchars($proveedor['telefono']) . '</td>
                    <td style="text-align:center; width: 18%;">' . htmlspecialchars($proveedor['direccion']) . '</td>
                    <td style="text-align:center; width: 18%;">' . htmlspecialchars($proveedor['email']) . '</td>
                    <td style="text-align:center; width: 8%;">' . ($proveedor['estado'] == '1' ? 'ACTIVO' : 'INACTIVO') . '</td>
                </tr>';
    }

    $html .= '</tbody>
              </table>
    </div>';

    // Agregar el contenido al PDF
    $pdf->writeHTML($html, true, false, true, false, '');

    // Salida del PDF
    $pdf->Output('resumen_proveedores.pdf', 'I');
}
}
?>
