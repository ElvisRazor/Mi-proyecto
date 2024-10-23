<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ventas extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Venta_model');
        $this->load->model('Cliente_model');
        $this->load->model('Producto_model');
        $this->load->helper('url');
        $this->load->library(['session', 'form_validation']);
        require_once(APPPATH . 'libraries/tcpdf/tcpdf.php'); // Cargar TCPDF manualmente
    }

    public function index() {
        $data['venta'] = $this->Venta_model->obtener_ventas();
        // Cargar las vistas
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('ventas/index', $data);
        $this->load->view('templates/footer');
    }    

    public function agregar() {
        if ($this->input->method() == 'post') {
            $idCliente = $this->input->post('idCliente');
            $productos = $this->input->post('producto');
            $cantidades = $this->input->post('cantidad');
            $descuentos = $this->input->post('descuento');

            // Validación de los datos
            if (empty($idCliente) || empty($productos)) {
                $this->session->set_flashdata('error', 'Cliente o productos no pueden estar vacíos.');
                redirect('ventas/agregar');
            }

            // Iniciar la transacción
            $this->db->trans_begin();

            try {
                $subTotal = 0; // Inicializa el subtotal
                $totalVenta = 0; // Inicializa el total de la venta

                // Obtener el último número de comprobante
                $ultimoComprobante = $this->Venta_model->obtenerUltimoComprobante();
                $numComprobante = isset($ultimoComprobante['numComprobante']) ? intval($ultimoComprobante['numComprobante']) + 1 : 1;
                $numComprobante = str_pad($numComprobante, 5, '0', STR_PAD_LEFT); // Asegura que tenga 5 dígitos

                // Proceso de venta
                foreach ($productos as $index => $idProducto) {
                    $cantidad = $cantidades[$index];
                    $descuento = $descuentos[$index];

                    $producto = $this->Producto_model->obtenerProductoPorId($idProducto);
                    if ($producto) {
                        $precio = $producto['precio'];
                        $subtotal = $cantidad * $precio; // Calcular el subtotal sin aplicar descuento
                        $subTotal += $subtotal; // Acumula el subtotal

                        // Calcular el total de la venta considerando los descuentos
                        $totalVenta += ($subtotal - ($cantidad * $descuento)); // Aplicar descuento a la venta

                        $nuevoStock = $producto['stock'] - $cantidad;
                        if ($nuevoStock < 0) {
                            throw new Exception("Stock insuficiente para el producto: " . $producto['nombre']);
                        }
                        $this->Producto_model->actualizarStock($idProducto, $nuevoStock);
                    } else {
                        throw new Exception("Producto no encontrado en la base de datos.");
                    }
                }

                // Inserta la venta con los totales calculados
                $ventaData = [
                    'idCliente' => $idCliente,
                    'idProducto' => $idProducto,
                    'numComprobante' => $numComprobante, // Asigna el número de comprobante
                    'subTotalVenta' => $subTotal, // Asigna el subtotal
                    'totalVenta' => $totalVenta, // Asigna el total considerando descuentos
                    'descuento' => array_sum($descuentos), // Sumar todos los descuentos si es necesario
                ];
                
                $idVenta = $this->Venta_model->insertarVenta($ventaData);

                // Re-inserta los detalles de la venta ahora que tenemos el idVenta
                foreach ($productos as $index => $idProducto) {
                    $cantidad = $cantidades[$index];
                    $descuento = $descuentos[$index];
                    $producto = $this->Producto_model->obtenerProductoPorId($idProducto); // Obtener el producto nuevamente
                    $precio = $producto['precio']; // Asegúrate de obtener el precio correcto de la base de datos
                    $subtotal = $cantidad * $precio; // Recalcular el subtotal

                    $detalleVentaData = [
                        'idVenta' => $idVenta, // Asigna el idVenta aquí
                        'idProducto' => $idProducto,
                        'cantidad' => $cantidad,
                        'precio' => $precio,
                        'descuento' => $descuento,
                        'subtotal' => $subtotal, // Almacena el subtotal recalculado
                        'totalVenta' => $totalVenta
                    ];
                    $this->Venta_model->insertarDetalleVenta($detalleVentaData);
                }

                if ($this->db->trans_status() === FALSE) {
                    throw new Exception('Error en la transacción de la venta');
                }
                $this->db->trans_commit();
                $this->session->set_flashdata('success', 'Venta registrada con éxito');
                redirect('ventas');
            } catch (Exception $e) {
                $this->db->trans_rollback();
                $this->session->set_flashdata('error', 'Error: ' . $e->getMessage());
                redirect('ventas/agregar');
            }
        }

        $data['cliente'] = $this->Cliente_model->obtener_clientes_activos(); 
        $data['producto'] = $this->Producto_model->obtener_productos_con_stock(); 

        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('ventas/agregar', $data);
        $this->load->view('templates/footer');
    }

    public function imprimir($idVenta) {
        // Obtener la venta y sus detalles
        $venta = $this->Venta_model->obtener_venta_por_id($idVenta);
        $detalleVenta = $this->Venta_model->obtener_detalles_venta($idVenta);
    
        if (!$venta || !$detalleVenta) {
            $this->session->set_flashdata('error', 'Venta no encontrada.');
            redirect('ventas');
        }
    
        // Crear el PDF
        $pdf = new TCPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('Recibo de Venta');
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
                    <span style="font-size: 12px; color:#0c4b93;">Recibo N°: ' . htmlspecialchars($venta['numComprobante']) . '</span><br>
                    <span style="font-size: 11px;">Fecha: ' . htmlspecialchars($venta['fechaRegistro']) . '</span><br>
                </td>
            </tr>
        </table>
        <hr style="color: #0c4b93; border-top: 2px solid #0c4b93;">';
    
        // Información del cliente y la venta
        $html .= '
        <h1 style="color:#0c4b93; font-size:20px; text-align:center;">RECIBO</h1>
        <h3 style="color:#0c4b93; font-size:14px;">Datos del Cliente</h3>
        <table cellpadding="5" style="width:100%;">
            <tr>
                <td style="width:50%;"><strong>Nombre:</strong> ' . htmlspecialchars($venta['nombre_cliente']) . '</td>
            </tr>
        </table>
        <hr style="color: #ccc; border-top: 1px solid #ccc;">';
    
        // Detalles de la venta en una tabla profesional
        $html .= '
        <h3 style="color:#0c4b93; font-size:14px;">Detalles de la Venta</h3>
        <table border="1" cellpadding="5" style="border-collapse: collapse; width:100%;">
            <thead>
                <tr style="background-color: #f3f3f3; color: #333;">
                    <th style="width:25%; text-align:left;">Producto</th>
                    <th style="width:25%; text-align:right;">Cantidad</th>
                    <th style="width:25%; text-align:right;">Precio Unitario</th>
                    <th style="width:25%; text-align:right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>';
    
        foreach ($detalleVenta as $detalle) {
            $html .= '<tr>
                <td>' . htmlspecialchars($detalle['nombre_producto']) . '</td>
                <td style="text-align:right;">' . htmlspecialchars($detalle['cantidad']) . '</td>
                <td style="text-align:right;">' . number_format($detalle['precio'], 2) . '</td>
                <td style="text-align:right;">' . number_format($detalle['subtotal'], 2) . '</td>
            </tr>';
        }
    
        $html .= '</tbody>
        </table>';
    
        // Resumen de la venta con totales resaltados
        $html .= '
        <h3 style="color:#0c4b93; font-size:14px;">Resumen de la Venta</h3>
        <table cellpadding="5" style="width:100%;">
            <tr>
                <td style="text-align:right; width:80%;"><strong>Subtotal:</strong></td>
                <td style="text-align:right; width:20%;">' . number_format($venta['subTotalVenta'], 2) . '</td>
            </tr>
            <tr>
                <td style="text-align:right;"><strong>Descuento Unitario:</strong></td>
                <td style="text-align:right;">' . number_format($venta['descuento'], 2) . '</td>
            </tr>
            <tr style="background-color: #0c4b93; color: white;">
                <td style="text-align:right; font-size: 14px;"><strong>Total Venta:</strong></td>
                <td style="text-align:right; font-size: 14px;"><strong>' . number_format($venta['totalVenta'], 2) . '</strong></td>
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
        $pdf->Output('factura_venta_' . $venta['numComprobante'] . '.pdf', 'I');
    }
    
    public function imprimirTodas() {
        // Obtener todas las ventas
        $ventas = $this->Venta_model->obtener_ventas();
        
        if (empty($ventas)) {
            $this->session->set_flashdata('error', 'No hay ventas para mostrar.');
            redirect('ventas');
        }
    
        // Crear el PDF
        $pdf = new TCPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('Reporte de Ventas');
        
        // Configuración de encabezado y pie de página
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->AddPage();
    
        // Agregar imagen de membretado como marca de agua
        $imagePath = FCPATH . 'assets/img/pisosbol2.PNG'; // Ruta relativa
        if (file_exists($imagePath)) {
            // Obtener dimensiones de la imagen
            list($width, $height) = getimagesize($imagePath);
            
            // Calcular la posición para centrar la imagen
            $scaleFactor = 0.3; // Ajusta el factor para aumentar el tamaño
            $x = ($pdf->getPageWidth() - ($width * $scaleFactor)) / 2; 
            $y = ($pdf->getPageHeight() - ($height * $scaleFactor)) / 2; 
            
            // Establecer opacidad
            $pdf->SetAlpha(0.3);
            $pdf->Image($imagePath, $x, $y, $width * $scaleFactor, $height * $scaleFactor, 'PNG', '', '', false, 300, '', false, false, 0, false, false, false);
            $pdf->SetAlpha(1);
        } else {
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->Cell(0, 10, 'Error: No se pudo cargar la imagen de membretado.', 0, 1, 'C', 0, '', 0, false, 'T', 'M');
        }
    
        // Establecer contenido del PDF
        $html = '
        <h1 style="text-align:center; color:#0c4b93;">Reporte de Ventas</h1>
        <table border="1" cellpadding="5" style="border-collapse: collapse; width:100%;">
            <thead>
                <tr style="background-color: #0c4b93; color: white;">
                    <th style="text-align:left;">N°</th>
                    <th style="text-align:left;">Cliente</th>
                    <th style="text-align:left;">Producto</th>
                    <th style="text-align:left;">Número Comprobante</th>
                    <th style="text-align:left;">Total Venta</th>
                    <th style="text-align:left;">Estado</th>
                    <th style="text-align:left;">Fecha</th>
                </tr>
            </thead>
            <tbody>';
    
        foreach ($ventas as $item) {
            $html .= '<tr>
                        <td>' . htmlspecialchars($item['idVenta']) . '</td>
                        <td>' . htmlspecialchars($item['nombre_cliente']) . '</td>
                        <td>' . htmlspecialchars($item['nombre_producto']) . '</td>
                        <td>' . htmlspecialchars($item['numComprobante']) . '</td>
                        <td style="text-align:right;">' . htmlspecialchars($item['totalVenta']) . '</td>
                        <td style="text-align:right;">' . (($item['estado'] == 1) ? 'Activo' : 'Inactivo') . '</td>
                        <td>' . htmlspecialchars($item['fechaRegistro']) . '</td>
                    </tr>';
        }
    
        $html .= '</tbody>
                  </table>';
    
        // Agregar contenido al PDF
        $pdf->writeHTML($html, true, false, true, false, '');
    
        // Agregar información de contacto al final del contenido
        $pdf->SetY($pdf->GetY() + 10);
        $pdf->SetFont('helvetica', 'I', 10);
        $pdf->Cell(0, 10, 'C. Esteban Arze entre Ladislao Cabrera, Cochabamba, Bolivia', 0, 1, 'C');
        $pdf->Cell(0, 10, 'Contacto: +591 77950114', 0, 1, 'C');
    
        // Salida del PDF
        $pdf->Output('reporte_ventas.pdf', 'I');
    }    
}