<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Usuarios extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Usuario_model');
        $this->load->helper('url');
        $this->load->library(['session', 'form_validation', 'email']);
        $this->load->config('email');
        require_once(APPPATH . 'libraries/tcpdf/tcpdf.php'); // Cargar TCPDF manualmente
    }

    public function index() {
        // Obtener el rol del usuario desde la sesión
        $rol = $this->session->userdata('rol');

        // Verificar si el rol es "administrador"
        if ($rol != 'administrador') {
            // Si no es administrador, redirigir a otra página o mostrar mensaje de error
            $this->session->set_flashdata('error', 'No tienes permisos para acceder a la gestión de usuarios.');
            redirect('dashboard'); // Redirige al dashboard o a una página de acceso denegado
        }
        $data['usuario'] = $this->Usuario_model->obtener_usuarios_activos();
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('usuarios/index', $data);
        $this->load->view('templates/footer');
    }

    public function agregar() {
        // Obtener el rol del usuario desde la sesión
        $rol = $this->session->userdata('rol');

        // Verificar si el rol es "administrador"
        if ($rol != 'administrador') {
            $this->session->set_flashdata('error', 'No tienes permisos para agregar usuarios.');
            redirect('dashboard'); // Redirige al dashboard o a una página de acceso denegado
        }

        if ($this->input->post()) {
            $this->form_validation->set_rules('nombre', 'Nombre', 'required|regex_match[/^[a-zA-Z\s]+$/]', [
                'regex_match' => 'El nombre solo puede contener letras y espacios.'
            ]);
            $this->form_validation->set_rules('primerApellido', 'Apellido Paterno', 'required|regex_match[/^[a-zA-Z\s]+$/]', [
                'regex_match' => 'El Apeliido Paterno solo puede contener letras.'
            ]);
            $this->form_validation->set_rules('segundoApellido', 'Apellido Materno', 'regex_match[/^[a-zA-Z\s]+$/]', [
                'regex_match' => 'El Apellido Materno solo puede contener letras.'
            ]);
            $this->form_validation->set_rules('email', 'Correo Electrónico', 'required|valid_email|callback_check_email');
            $this->form_validation->set_rules('rol', 'Rol', 'required');
            $this->form_validation->set_rules('tipoDocumento', 'Tipo de Documento', 'required');
            $this->form_validation->set_rules('numDocumento', 'Número de Documento', 'required|regex_match[/^[a-zA-Z0-9-]+$/]', [
                'regex_match' => 'El número de documento solo puede contener letras, números y guiones.'
            ]);
            $this->form_validation->set_rules('direccion', 'Dirección', 'required');
            $this->form_validation->set_rules('telefono', 'Teléfono', 'required|numeric', [
                'numeric' => 'El teléfono solo puede contener números.'
            ]);

            if ($this->form_validation->run() === TRUE) {
                $password = $this->generar_contraseña();
                $data = [
                    'nombre' => $this->input->post('nombre'),
                    'primerApellido' => $this->input->post('primerApellido'),
                    'segundoApellido' => $this->input->post('segundoApellido'),
                    'email' => $this->input->post('email'),
                    'rol' => $this->input->post('rol'),
                    'tipoDocumento' => $this->input->post('tipoDocumento'),
                    'numDocumento' => $this->input->post('numDocumento'),
                    'direccion' => $this->input->post('direccion'),
                    'telefono' => $this->input->post('telefono'),
                    'password' => md5($password),
                    'estado' => 1
                ];

                if ($this->Usuario_model->agregar_usuario($data)) {
                    $this->_enviar_correo($this->input->post('email'), $this->input->post('nombre'), $password);
                    $this->session->set_flashdata('mensaje', 'Usuario agregado correctamente. Se ha enviado la contraseña al correo electrónico.');
                    $this->session->set_flashdata("error", "Contraseña o email incorrecto");
                    redirect('usuarios');
                } else {
                    $this->session->set_flashdata('error', 'Error al agregar el usuario.');
                }
            } else {
                $this->session->set_flashdata('error', validation_errors());
            }
        }

        $data['tipos_documento'] = ['Ci/Nit', 'Pasaporte'];
        $data['roles'] = ['vendedor', 'administrador', 'cliente', 'proveedor'];

        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('usuarios/agregar', $data);
        $this->load->view('templates/footer');
    }

    public function editar($idUsuario) {
        // Obtener el rol del usuario desde la sesión
        $rol = $this->session->userdata('rol');

        // Verificar si el rol es "administrador"
        if ($rol != 'administrador') {
            $this->session->set_flashdata('error', 'No tienes permisos para editar usuarios.');
            redirect('dashboard'); // Redirige al dashboard o a una página de acceso denegado
        }

        if ($this->input->post()) {
            $this->form_validation->set_rules('nombre', 'Nombre Completo', 'required|regex_match[/^[a-zA-Z\s]+$/]', [
                'regex_match' => 'El nombre completo solo puede contener letras y espacios.'
            ]);
            $this->form_validation->set_rules('primerApellido', 'Apellido Paterno', 'required|regex_match[/^[a-zA-Z\s]+$/]', [
                'regex_match' => 'El Apeliido Paterno solo puede contener letras.'
            ]);
            $this->form_validation->set_rules('segundoApellido', 'Apellido Materno', 'regex_match[/^[a-zA-Z\s]+$/]', [
                'regex_match' => 'El Apellido Materno solo puede contener letras.'
            ]);
            $this->form_validation->set_rules('email', 'Correo Electrónico', 'required|valid_email');
            $this->form_validation->set_rules('rol', 'Rol', 'required');
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
                    'primerApellido' => $this->input->post('primerApellido'),
                    'segundoApellido' => $this->input->post('segundoApellido'),
                    'email' => $this->input->post('email'),
                    'rol' => $this->input->post('rol'),
                    'tipoDocumento' => $this->input->post('tipoDocumento'),
                    'numDocumento' => $this->input->post('numDocumento'),
                    'direccion' => $this->input->post('direccion'),
                    'telefono' => $this->input->post('telefono')
                ];

                if ($this->input->post('password')) {
                    $data['password'] = md5($this->input->post('password'));
                }

                $this->Usuario_model->editar_usuario($idUsuario, $data);
                $this->session->set_flashdata('mensaje', 'Usuario actualizado correctamente.');
                redirect('usuarios');
            } else {
                $this->session->set_flashdata('error', validation_errors());
            }
        }

        $data['usuario'] = $this->Usuario_model->obtener_usuario_por_id($idUsuario);
        if (!$data['usuario']) {
            show_404();
        }

        $data['tipos_documento'] = ['Ci/Nit', 'Pasaporte'];
        $data['roles'] = ['vendedor', 'administrador'];

        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('usuarios/editar', $data);
        $this->load->view('templates/footer');
    }

    public function eliminar($idUsuario) {
        // Obtener el rol del usuario desde la sesión
        $rol = $this->session->userdata('rol');

        // Verificar si el rol es "administrador"
        if ($rol != 'administrador') {
            $this->session->set_flashdata('error', 'No tienes permisos para editar usuarios.');
            redirect('dashboard'); // Redirige al dashboard o a una página de acceso denegado
        }

        $this->Usuario_model->eliminar_usuario($idUsuario);
        $this->session->set_flashdata('mensaje', 'Usuario eliminado correctamente.');
        redirect('usuarios');
    }

    public function eliminados() {
        // Obtener el rol del usuario desde la sesión
        $rol = $this->session->userdata('rol');

        // Verificar si el rol es "administrador"
        if ($rol != 'administrador') {
            $this->session->set_flashdata('error', 'No tienes permisos para editar usuarios.');
            redirect('dashboard'); // Redirige al dashboard o a una página de acceso denegado
        }
        
        $data['usuarios'] = $this->Usuario_model->obtener_usuarios_eliminados();
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('usuarios/eliminados', $data);
        $this->load->view('templates/footer');
    }

    public function habilitar($idUsuario) {
        // Obtener el rol del usuario desde la sesión
        $rol = $this->session->userdata('rol');

        // Verificar si el rol es "administrador"
        if ($rol != 'administrador') {
            $this->session->set_flashdata('error', 'No tienes permisos para editar usuarios.');
            redirect('dashboard'); // Redirige al dashboard o a una página de acceso denegado
        }

        $this->Usuario_model->habilitar_usuario($idUsuario);
        $this->session->set_flashdata('mensaje', 'Usuario habilitado correctamente.');
        redirect('usuarios/eliminados');
    }

    public function check_email($email) {
        if ($this->Usuario_model->email_exists($email)) {
            $this->form_validation->set_message('check_email', 'El correo electrónico ya está en uso.');
            return FALSE;
        }
        return TRUE;
    }

    private function generar_contraseña() {
        return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);
    }

    private function _enviar_correo($email, $nombre, $password) {
        $this->email->from($this->config->item('smtp_user'), 'Administrador');
        $this->email->to($email);
        $this->email->subject('Creación de cuenta');
        $this->email->message("Hola $nombre,\n\nTu cuenta ha sido creada exitosamente. Aquí tienes tu contraseña: $password\n\nPor favor, cambia tu contraseña después de iniciar sesión.");

        if (!$this->email->send()) {
            $this->session->set_flashdata('error', 'No se pudo enviar el correo de confirmación.');
        }
    }
    
    public function imprimir() {
        // Obtener todos los usuarios
        $usuarios = $this->Usuario_model->obtener_usuarios_activos();
    
        if (empty($usuarios)) {
            $this->session->set_flashdata('error', 'No se encontraron usuarios para imprimir.');
            redirect('usuarios');
        }
    
        // Crear el PDF
        $pdf = new TCPDF('L', 'mm', 'A4');
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->SetPrintHeader(false);
        $pdf->AddPage();
        // Agregar el logo en la esquina superior izquierda
    $logoPath = FCPATH . 'assets/img/pisosbol(1).PNG';
    if (file_exists($logoPath)) {
        $pdf->Image($logoPath, 10, 6, 50, 30, 'PNG'); // Ancho=50mm, Alto=30mm
    }

    // Obtener datos del usuario
    $nombreUsuario = $this->session->userdata('nombre');
    $primerApellido = $this->session->userdata('primerApellido');
    $segundoApellido = $this->session->userdata('segundoApellido');
    $nombreCompleto = $nombreUsuario . ' ' . $primerApellido . ' ' . $segundoApellido;

    // Obtener fecha y hora de impresión
    $fechaHora = date('d/m/Y H:i:s');

    // Agregar información del usuario y fecha en la esquina superior derecha
    $pdf->SetFont('helvetica', '', 10);
    $pdf->SetXY(200, 5);
    $pdf->Cell(0, 5, "Fecha y Hora: $fechaHora", 0, 1, 'R');
    $pdf->SetXY(200, 12);
    $pdf->Cell(0, 5, "Usuario: $nombreCompleto", 0, 1, 'R');
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
        $pdf->Ln(20);
        // Contenido del PDF
        $html = '
        <h1 style="text-align:center; color:#0c4b93;">Lista de Usuarios</h1>';
    
        $html .= '<table border="1" cellpadding="10" style="border-collapse: collapse; width:100%;">
                      <thead>
                          <tr style="background-color: #0c4b93; color: white;">
                              <th style="text-align:center; width:  5%;">N°</th>
                              <th style="text-align:center; width:  8%;">Nombre</th>
                              <th style="text-align:center; width:  9%;">Apellido Paterno</th>
                              <th style="text-align:center; width:  10%;">Apellido Materno</th>
                              <th style="text-align:center; width:  18%;">Correo Electrónico</th>
                              <th style="text-align:center; width:  15%;">Tipo Documento</th>
                              <th style="text-align:center; width:  15%;">Dirección</th>
                              <th style="text-align:center; width:  11%;">Rol</th>
                              <th style="text-align:center; width:  8%;">Estado</th>
                          </tr>
                      </thead>
                      <tbody>';
    
        foreach ($usuarios as $usuario) {
            $html .= '<tr>
                        <td style="text-align:center; width:  5%;">' . htmlspecialchars($usuario['idUsuario']) . '</td>
                        <td style="text-align:center; width:  8%;">' . htmlspecialchars($usuario['nombre']) . '</td>
                        <td style="text-align:center; width:  9%;">' . htmlspecialchars($usuario['primerApellido']) . '</td>
                        <td style="text-align:center; width:  10%;">' . htmlspecialchars($usuario['segundoApellido']) . '</td>
                        <td style="text-align:center; width:  18%;">' . htmlspecialchars($usuario['email']) . '</td>
                        <td style="text-align:center; width:  15%;">' . htmlspecialchars($usuario['tipoDocumento']) . '</td>
                        <td style="text-align:center; width:  15%;">' . htmlspecialchars($usuario['direccion']) . '</td>
                        <td style="text-align:center; width:  11%;">' . htmlspecialchars($usuario['rol']) . '</td>
                        <td style="text-align:center; width:  8%;">' . ($usuario['estado'] == '1' ? 'ACTIVO' : 'INACTIVO') . '</td>
                    </tr>';
        }
    
        $html .= '</tbody>
                  </table>';
    
        // Agregar el contenido al PDF
        $pdf->writeHTML($html, true, false, true, false, '');
    
        // Salida del PDF
        $pdf->Output('resumen_usuarios.pdf', 'I');
    }    
}
?>
