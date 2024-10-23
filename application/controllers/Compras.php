<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Compras extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Compra_model');
        $this->load->model('Proveedor_model');
        $this->load->model('Producto_model');
        $this->load->helper('url');
        $this->load->library(['session', 'form_validation']);
        require_once(APPPATH . 'libraries/tcpdf/tcpdf.php'); // Cargar TCPDF manualmente
    }

    public function index() {
        $data['compra'] = $this->Compra_model->obtener_compras();
        // Cargar las vistas
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('compras/index', $data);
        $this->load->view('templates/footer');
    }

    public function agregar() {
        if ($this->input->method() == 'post') {
            $idProveedor = $this->input->post('idProveedor');
            $productos = $this->input->post('producto');
            $cantidades = $this->input->post('cantidad');

            // Validación de los datos
            if (empty($idProveedor) || empty($productos)) {
                $this->session->set_flashdata('error', 'Proveedor o productos no pueden estar vacíos.');
                redirect('compras/agregar');
            }

            // Iniciar la transacción
            $this->db->trans_begin();

            try {
                $totalCompra = 0;

                // Obtener el último número de comprobante
                $ultimoComprobante = $this->Compra_model->obtenerUltimoComprobante();
                $numComprobante = isset($ultimoComprobante['numComprobante']) ? intval($ultimoComprobante['numComprobante']) + 1 : 1;
                $numComprobante = str_pad($numComprobante, 5, '0', STR_PAD_LEFT); 

                // Proceso de compra
                foreach ($productos as $index => $idProducto) {
                    $cantidad = $cantidades[$index];

                    $producto = $this->Producto_model->obtenerProductoPorId($idProducto);
                    if ($producto) {
                        $precio = $producto['precio'];
                        $totalCompra += $cantidad * $precio;

                        // Incrementar el stock del producto
                        $nuevoStock = $producto['stock'] + $cantidad;
                        $this->Producto_model->actualizarStock($idProducto, $nuevoStock);
                    } else {
                        throw new Exception("Producto no encontrado en la base de datos.");
                    }
                }

                // Inserta la compra
                $compraData = [
                    'idProveedor' => $idProveedor,
                    'numComprobante' => $numComprobante,
                    'totalCompra' => $totalCompra
                ];

                $idCompra = $this->Compra_model->insertarCompra($compraData);

                // Inserta los detalles de la compra
                foreach ($productos as $index => $idProducto) {
                    $cantidad = $cantidades[$index];
                    $producto = $this->Producto_model->obtenerProductoPorId($idProducto);
                    $precio = $producto['precio'];

                    $detalleCompraData = [
                        'idCompra' => $idCompra,
                        'idProducto' => $idProducto,
                        'cantidad' => $cantidad,
                        'precio' => $precio
                    ];
                    $this->Compra_model->insertarDetalleCompra($detalleCompraData);
                }

                if ($this->db->trans_status() === FALSE) {
                    throw new Exception('Error en la transacción de la compra');
                }
                $this->db->trans_commit();
                $this->session->set_flashdata('success', 'Compra registrada con éxito');
                redirect('compras');
            } catch (Exception $e) {
                $this->db->trans_rollback();
                $this->session->set_flashdata('error', 'Error: ' . $e->getMessage());
                redirect('compras/agregar');
            }
        }

        $data['proveedor'] = $this->Proveedor_model->obtener_proveedores_activos();
        $data['producto'] = $this->Producto_model->obtener_productos_activos(); 

        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('compras/agregar', $data);
        $this->load->view('templates/footer');
    }

    public function imprimir($idCompra) {
        // Obtener la compra y sus detalles
        $compra = $this->Compra_model->obtener_compra_por_id($idCompra);
        $detalleCompra = $this->Compra_model->obtener_detalles_compra($idCompra);
    
        if (!$compra || !$detalleCompra) {
            $this->session->set_flashdata('error', 'Compra no encontrada.');
            redirect('compras');
        }
    
        // Crear el PDF
        $pdf = new TCPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('Recibo de Compra');
        $pdf->SetPrintHeader(false); // Desactivar el encabezado
        $pdf->SetPrintFooter(false); // Desactivar el pie de página
        $pdf->AddPage();
    
        // Encabezado con logotipo y datos del negocio
        $imagePath = FCPATH . 'assets/img/pisosbol2.PNG'; // Ruta de la imagen del logotipo
        if (file_exists($imagePath)) {
            $pdf->Image($imagePath, 120, 20, 100, '', 'PNG'); // Logotipo a la izquierda
        }
    
        $html = '
        <table cellpadding="5" style="width:100%;">
            <tr>
                <td style="width:60%;">
                    <span style="font-size: 12px; color: #0c4b93;">IMPORTADORA PISOSBOL</span><br>
                    <span style="font-size: 11px;">C. Ladislao Cabrera #54 COCHABAMBA</span><br>
                    <span style="font-size: 11px;">Teléfono: 44578996</span>
                </td>
                <td style="text-align:right; width:40%;">
                    <span style="font-size: 12px; color:#0c4b93;">Recibo N°: ' . htmlspecialchars($compra['numComprobante']) . '</span><br>
                    <span style="font-size: 11px;">Fecha: ' . htmlspecialchars($compra['fechaRegistro']) . '</span><br>
                </td>
            </tr>
        </table>
        <hr style="color: #0c4b93; border-top: 2px solid #0c4b93;">';
    
        // Información del proveedor y la compra
        $html .= '
        <h1 style="color:#0c4b93; font-size:20px; text-align:center;">RECIBO</h1>
        <h3 style="color:#0c4b93; font-size:14px;">Datos del Proveedor</h3>
        <table cellpadding="5" style="width:100%;">
            <tr>
                <td style="width:50%;"><strong>Nombre:</strong> ' . htmlspecialchars($compra['nombre_proveedor']) . '</td>
            </tr>
        </table>
        <hr style="color: #ccc; border-top: 1px solid #ccc;">';
    
        // Detalles de la compra en una tabla profesional
        $html .= '
        <h3 style="color:#0c4b93; font-size:14px;">Detalles de la Compra</h3>
        <table border="1" cellpadding="5" style="border-collapse: collapse; width:100%;">
            <thead>
                <tr style="background-color: #f3f3f3; color: #333;">
                    <th style="width:25%; text-align:left;">Producto</th>
                    <th style="width:25%; text-align:right;">Cantidad</th>
                    <th style="width:25%; text-align:right;">Precio Unitario</th>
                    <th style="width:25%; text-align:right;">Total</th>
                </tr>
            </thead>
            <tbody>';
    
        foreach ($detalleCompra as $detalle ) {
            $html .= '<tr>
                <td>' . htmlspecialchars($detalle['nombre_producto']) . '</td>
                <td style="text-align:right;">' . htmlspecialchars($detalle['cantidad']) . '</td>
                <td style="text-align:right;">' . number_format($detalle['precio'], 2) . '</td>
                <td style="text-align:right;">' . number_format($compra['totalCompra'], 2) . '</td>
            </tr>';
        }
    
        $html .= '</tbody>
        </table>';
    
        // Resumen de la compra con total resaltado
        $html .= '
        <h3 style="color:#0c4b93; font-size:14px;">Resumen de la Compra</h3>
        <table cellpadding="5" style="width:100%;">
            <tr>
                <td style="text-align:right; width:80%;"><strong>Total Compra:</strong></td>
                <td style="text-align:right; width:20%;">' . number_format($compra['totalCompra'], 2) . '</td>
            </tr>
        </table>';
    
        // Pie de página
        $html .= '
        <hr style="color: #ccc; border-top: 1px solid #ccc;">
        <p style="text-align:center; font-size: 10px; color: #333;">
            "Este recibo contribuye al registro y control de las transacciones realizadas." <br>
            Ley N° 453: El proveedor debe exhibir y entregar el recibo correspondiente a las transacciones realizadas con los clientes.
        </p>
        <p style="text-align:center; font-size: 10px; font-style: italic;">C. Esteban Arze entre Ladislao Cabrera, Cochabamba, Bolivia | Contacto: +591 77950114</p>';
    
        // Agregar el contenido al PDF
        $pdf->writeHTML($html, true, false, true, false, '');
    
        // Salida del PDF
        $pdf->Output('recibo_compra_' . $compra['numComprobante'] . '.pdf', 'I');
    }    

    public function imprimir_todas() {
        // Obtener todas las compras
        $compras = $this->Compra_model->obtener_compras();
    
        if (empty($compras)) {
            $this->session->set_flashdata('error', 'No se encontraron compras para imprimir.');
            redirect('compras');
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
        <h1 style="text-align:center; color:#0c4b93;">Resumen de Compras</h1>';
    
        $html .= '<table border="1" cellpadding="5" style="border-collapse: collapse; width:100%;">
                      <thead>
                          <tr style="background-color: #0c4b93; color: white;">
                              <th style="text-align:left;">N°</th>
                              <th style="text-align:left;">Número de Comprobante</th>
                              <th style="text-align:left;">Proveedor</th>
                              <th style="text-align:left;">Total Compra</th>
                              <th style="text-align:left;">Fecha</th>
                          </tr>
                      </thead>
                      <tbody>';
    
        foreach ($compras as $compra) {
            $html .= '<tr>
                        <td>' . htmlspecialchars($compra['idCompra']) . '</td>
                        <td>' . htmlspecialchars($compra['numComprobante']) . '</td>
                        <td>' . htmlspecialchars($compra['nombre_proveedor']) . '</td>
                        <td>' . number_format($compra['totalCompra'], 2) . '</td>
                        <td>' . htmlspecialchars($compra['fechaRegistro']) . '</td>
                    </tr>';
        }
    
        $html .= '</tbody>
                  </table>';
    
        // Agregar el contenido al PDF
        $pdf->writeHTML($html, true, false, true, false, '');
    
        // Salida del PDF
        $pdf->Output('resumen_compras.pdf', 'I');
    }   
    
    // Método para cargar la vista de consulta de compras
    public function consulta() {
        $data['compra'] = [];
        $data['error'] = '';
    
        if ($this->input->post('submit')) {
            $fecha_inicio = $this->input->post('fecha_inicio');
            $fecha_fin = $this->input->post('fecha_fin');
            
            // Verifica las fechas ingresadas
            log_message('debug', 'Fecha inicio: ' . $fecha_inicio);
            log_message('debug', 'Fecha fin: ' . $fecha_fin);
    
            // Realiza la consulta a la base de datos
            $data['compra'] = $this->Compra_model->obtener_compras_por_fechas($fecha_inicio, $fecha_fin);
    
            // Verifica si no se encontraron resultados
            if (empty($data['compra'])) {
                $data['error'] = 'No se encontraron compras en este rango de fechas.';
            }
        }
    
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('compras/consulta', $data);
        $this->load->view('templates/footer');
    }         
}
