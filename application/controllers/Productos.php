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
            $this->form_validation->set_rules('precioCompra', 'Precio Compra', 'required|numeric');
            $this->form_validation->set_rules('precioVenta', 'Precio venta', 'required|numeric');
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
                    'precioCompra' => $this->input->post('precioCompra'),
                    'precioVenta' => $this->input->post('precioVenta'),
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
    
        $data['categoria'] = $this->Producto_model->obtener_categorias_activos();
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
            $this->form_validation->set_rules('precioCompra', 'Precio Compra', 'required|numeric');
            $this->form_validation->set_rules('precioVenta', 'Precio Venta', 'required|numeric');
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
                    'precioCompra' => $this->input->post('precioCompra'),
                    'precioVenta' => $this->input->post('precioVenta'),
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
    
        $data['categoria'] = $this->Producto_model->obtener_categorias_activos();
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

    public function eliminados() {
        // Obtener el rol del usuario desde la sesión
        $rol = $this->session->userdata('rol');
    
        // Verificar si el rol es "Administrador"
        if ($rol != 'administrador') {
            // Si no es administrador, redirigir a otra página o mostrar mensaje de error
            $this->session->set_flashdata('mensaje', 'No tienes acceso a esta vista');
            redirect('productos'); // Cambia 'dashboard' por la ruta que desees
        }
    
        // Si es administrador, cargar la vista de productos eliminados
        $data['producto'] = $this->Producto_model->obtener_productos_eliminados();
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('productos/eliminados', $data);
        $this->load->view('templates/footer');
    }
    

    public function habilitar($idProducto) {
        $this->Producto_model->habilitar_producto($idProducto);
        $this->session->set_flashdata('mensaje', 'Producto habilitado correctamente.');
        redirect('productos/eliminados');
    }

    public function imprimir()
{
    // Obtener todos los productos
    $productos = $this->Producto_model->obtener_productos_activos();

    if (empty($productos)) {
        $this->session->set_flashdata('error', 'No se encontraron productos para imprimir.');
        redirect('productos');
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

    // Agregar el logo en la esquina superior izquierda, más grande
    $logoPath = FCPATH . 'assets/img/logoTrans.PNG';
    if (file_exists($logoPath)) {
        $pdf->Image($logoPath, 6, -6, 80, 52, 'PNG'); // Ancho=80mm, Alto=52mm (más grande)
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

    // Agregar imagen de marca de agua, centrada
    $watermarkPath = FCPATH . 'assets/img/logo.PNG';
    if (file_exists($watermarkPath)) {
        list($width, $height) = getimagesize($watermarkPath);
        $scaleFactor = 0.1; // Factor de escala para el tamaño de la marca de agua
        $x = ($pdf->getPageWidth() - ($width * $scaleFactor)) / 2;  // Centrar horizontalmente
        $y = ($pdf->getPageHeight() - ($height * $scaleFactor)) / 2; // Centrar verticalmente
        $pdf->SetAlpha(0.2); // Establecer transparencia
        $pdf->Image($watermarkPath, $x, $y, $width * $scaleFactor, $height * $scaleFactor, 'PNG');
        $pdf->SetAlpha(1); // Restablecer transparencia
    }
    // Establecer posición para comenzar la tabla después de la imagen
    $pdf->Ln(6); // Agregar espacio vertical (6 mm) después de la imagen

    // Contenido del PDF
    $html = '
<div style="text-align:center;">
    <h1 style="color:#0c4b93;">Lista de Productos</h1>
    <table border="1" cellpadding="4" style="border-collapse: collapse; width: 100%; margin-left: auto; margin-right: auto; text-align:center;">
        <thead>
            <tr style="background-color: #0c4b93; color: white;">
                <th style="text-align:center; width: 4%;">N°</th>
                <th style="text-align:center; width: 18%;">Nombre</th>
                <th style="text-align:center; width: 9%;">Código</th>
                <th style="text-align:center; width: 9%;">Precio Compra</th>
                <th style="text-align:center; width: 8%;">Precio Venta</th>
                <th style="text-align:center; width: 7%;">Stock</th>
                <th style="text-align:center; width: 23%;">Descripción</th>
                <th style="text-align:center; width: 15%;">Imagen</th>
                <th style="text-align:center; width: 8%;">Estado</th>
            </tr>
        </thead>
        <tbody>';

    foreach ($productos as $producto) {
        // Construir la ruta completa de la imagen del producto
        // Asegúrate de que $producto['imagen'] contenga el nombre del archivo de la imagen (ej: 'producto1.jpg')
        // y que las imágenes estén en FCPATH . 'uploads/productos/'
        $imageFileName = isset($producto['imagen']) ? htmlspecialchars($producto['imagen']) : ''; // Verifica si la clave 'imagen' existe
        $productImagePath = FCPATH . 'uploads/productos/' . $imageFileName; 
        
        // Verificar si la imagen existe para evitar errores en el PDF
        $productImageHtml = '';
        if (!empty($imageFileName) && file_exists($productImagePath)) {
            // Se usa width y height en px para controlar el tamaño dentro de la celda.
            // max-width y max-height son buenas prácticas para asegurar que no se desborde.
            // TCPDF puede renderizar etiquetas <img> con rutas de archivo locales.
            $productImageHtml = '<img src="' . $productImagePath . '" width="50" height="50" style="max-width: 50px; max-height: 50px;"/>';
        } else {
            // Opcional: Mostrar un texto o un icono si la imagen no se encuentra
            $productImageHtml = 'Sin imagen'; 
        }

        $html .= '<tr>
                    <td style="text-align:center; width: 4%;">' . htmlspecialchars($producto['idProducto']) . '</td>
                    <td style="text-align:center; width: 18%;">' . htmlspecialchars($producto['nombre']) . '</td>
                    <td style="text-align:center; width: 9%;">' . htmlspecialchars($producto['codigo']) . '</td>
                    <td style="text-align:center; width: 9%;">Bs. ' . number_format($producto['precioCompra'], 2) . '</td>
                    <td style="text-align:center; width: 8%;">Bs. ' . number_format($producto['precioVenta'], 2) . '</td>
                    <td style="text-align:center; width: 7%;">' . htmlspecialchars($producto['stock']) . '</td>
                    <td style="text-align:center; width: 23%;">' . htmlspecialchars($producto['descripcion']) . '</td>
                    <td style="text-align:center; width: 15%;">' . $productImageHtml . '</td>
                    <td style="text-align:center; width: 8%;">' . ($producto['estado'] == '1' ? 'ACTIVO' : 'INACTIVO') . '</td>
                </tr>';
    }

    $html .= '</tbody>
            </table>
</div>';

    // Agregar el contenido al PDF
    $pdf->writeHTML($html, true, false, true, false, '');

    // Salida del PDF
    $pdf->Output('resumen_productos.pdf', 'I');
}
}
?>