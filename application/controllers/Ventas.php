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
        // Verifica si el método de solicitud es POST
        if ($this->input->method() == 'post') {
            // Obtiene los datos enviados desde el formulario
            $idCliente = $this->input->post('idCliente');
            $productos = $this->input->post('producto');
            $cantidades = $this->input->post('cantidad');
            $descuentos = $this->input->post('descuento');
    
            // Validación de los datos recibidos
            if (empty($idCliente) || empty($productos)) {
                // Si faltan datos, establece un mensaje de error y redirige a la página de agregar venta
                $this->session->set_flashdata('error', 'Cliente o productos no pueden estar vacíos.');
                redirect('ventas/agregar');
            }
    
            // Inicia la transacción en la base de datos
            $this->db->trans_begin();
    
            try {
                $subTotal = 0; // Inicializa el subtotal
                $totalVenta = 0; // Inicializa el total de la venta
                // Obtiene el último número de comprobante registrado
                $ultimoComprobante = $this->Venta_model->obtenerUltimoComprobante();
                $numComprobante = isset($ultimoComprobante['numComprobante']) ? intval($ultimoComprobante['numComprobante']) + 1 : 1;
                $numComprobante = str_pad($numComprobante, 5, '0', STR_PAD_LEFT); // Formatea el número a 5 dígitos
                // Recorre cada producto en la venta
                foreach ($productos as $index => $idProducto) {
                    $cantidad = $cantidades[$index];
                    $descuento = $descuentos[$index];
                    // Obtiene el producto de la base de datos
                    $producto = $this->Producto_model->obtenerProductoPorId($idProducto);
                    if ($producto) {
                        $precioVenta = $producto['precioVenta'];
                        // Validación del descuento
                        $descuentoMaximo = $precioVenta * 0.10; // 10% del precio del producto
                        if ($descuento > $descuentoMaximo) {
                            throw new Exception("El descuento para el producto '{$producto['nombre']}' no puede superar el 10% del precio (Máximo: " . number_format($descuentoMaximo, 2) . ").");
                        }
                        $subtotal = $cantidad * $precioVenta; // Calcula el subtotal sin descuento
                        $subTotal += $subtotal; // Acumula el subtotal
    
                        // Calcula el total de la venta aplicando el descuento
                        $totalVenta += ($subtotal - ($cantidad * $descuento));
    
                        // Calcula el nuevo stock después de la venta
                        $nuevoStock = $producto['stock'] - $cantidad;
                        if ($nuevoStock < 0) {
                            throw new Exception("Stock insuficiente para el producto: " . $producto['nombre']);
                        }
                        // Actualiza el stock del producto en la base de datos
                        $this->Producto_model->actualizarStock($idProducto, $nuevoStock);
                    } else {
                        // Si el producto no existe, lanza una excepción
                        throw new Exception("Producto no encontrado en la base de datos.");
                    }
                }
    
                // Inserta la venta en la base de datos
                $ventaData = [
                    'idCliente' => $idCliente,
                    'idProducto' => $idProducto,
                    'numComprobante' => $numComprobante, // Número de comprobante
                    'subTotalVenta' => $subTotal, // Subtotal de la venta
                    'totalVenta' => $totalVenta, // Total de la venta con descuento
                    'descuento' => array_sum($descuentos), // Suma de los descuentos aplicados
                ];
    
                // Inserta la venta y obtiene su ID
                $idVenta = $this->Venta_model->insertarVenta($ventaData);
    
                // Inserta los detalles de cada producto vendido
                foreach ($productos as $index => $idProducto) {
                    $cantidad = $cantidades[$index];
                    $descuento = $descuentos[$index];
                    $producto = $this->Producto_model->obtenerProductoPorId($idProducto); // Re-obtiene el producto
                    $precioVenta = $producto['precioVenta'];
                    $subtotal = $cantidad * $precioVenta; // Calcula el subtotal para el detalle
    
                    $detalleVentaData = [
                        'idVenta' => $idVenta, // ID de la venta asociada
                        'idProducto' => $idProducto,
                        'cantidad' => $cantidad,
                        'precioVenta' => $precioVenta,
                        'descuento' => $descuento,
                        'subtotal' => $subtotal, // Subtotal del detalle
                        'totalVenta' => $totalVenta // Total de la venta en el detalle
                    ];
                    // Inserta el detalle de la venta en la base de datos
                    $this->Venta_model->insertarDetalleVenta($detalleVentaData);
                }
    
                // Verifica el estado de la transacción
                if ($this->db->trans_status() === FALSE) {
                    throw new Exception('Error en la transacción de la venta');
                }
    
                // Si todo fue correcto, confirma la transacción
                $this->db->trans_commit();
                $this->session->set_flashdata('success', 'Venta registrada con éxito');
                redirect('ventas/imprimir/' . $idVenta);
            } catch (Exception $e) {
                // En caso de error, revierte la transacción
                $this->db->trans_rollback();
                $this->session->set_flashdata('error', 'Error: ' . $e->getMessage());
                redirect('ventas/agregar');
            }
        }
    
        // Carga los clientes y productos disponibles para la vista de agregar venta
        $data['cliente'] = $this->Cliente_model->obtener_clientes_activos();
        $data['producto'] = $this->Producto_model->obtener_productos_con_stock();
    
        // Carga las vistas de la interfaz de agregar venta
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
        $imagePath = FCPATH . 'assets/img/logoTrans.PNG'; // Ruta de la imagen del logotipo
        if (file_exists($imagePath)) {
            $pdf->Image($imagePath, 120, 32, 100, '', 'PNG'); // Logotipo a la izquierda
        }
    
        $html = '
        <table cellpadding="3" style="width:100%;">
            <tr>
                <td style="width:60%;">
                    <span style="font-size: 12px; color: #0c4b93;">IMPORTADORA DUKEL</span><br>
                    <span style="font-size: 10px;">Pukara Grande Zona sud B-Olmedo #CV237 CBBA</span><br>
                    <span style="font-size: 10px;">Teléfono: 67476946</span>
                </td>
                <td style="text-align:right; width:40%;">
                    <span style="font-size: 12px; color:#0c4b93;">Recibo N°: ' . htmlspecialchars($venta['numComprobante']) . '</span><br>
                    <span style="font-size: 10px;">Fecha: ' . htmlspecialchars($venta['fechaRegistro']) . '</span><br>
                </td>
            </tr>
        </table>
        <hr style="color: #0c4b93; border-top: 2px solid #0c4b93;">';
    
        // Información del cliente y la venta
        $html .= '
        <h1 style="color:#0c4b93; font-size:20px; text-align:center;">RECIBO</h1>
        <h3 style="color:#0c4b93; font-size:14px;">Datos del Cliente</h3>
        <table cellpadding="3" style="width:100%;">
            <tr>
                <td style="width:50%;"><strong>Nombre:</strong> ' . htmlspecialchars($venta['nombre_cliente']) . '</td>
            </tr>
            <tr>
                <td style="width:50%;"><strong>Tipo Documento:</strong> ' . htmlspecialchars($venta['tipo_documento_cliente']) . '</td>
            </tr>
            <tr>
                <td style="width:50%;"><strong>Número de Documento:</strong> ' . htmlspecialchars($venta['num_documento_cliente']) . '</td>
            </tr>
            <tr>
                <td style="width:50%;"><strong>Correo:</strong> ' . htmlspecialchars($venta['email_cliente']) . '</td>
            </tr>
            <tr>
                <td style="width:50%;"><strong>Teléfono:</strong> ' . htmlspecialchars($venta['telefono_cliente']) . '</td>
            </tr>
        </table>
        <hr style="color: #ccc; border-top: 1px solid #ccc;">';
    
        // Detalles de la venta en una tabla profesional
        $html .= '
        <h3 style="color:#0c4b93; font-size:14px;">Detalles de la Venta</h3>
        <table border="1" cellpadding="3" style="border-collapse: collapse; width:100%;">
            <thead>
                <tr style="background-color: #f3f3f3; color: #333; font-size:12px;">
                    <th style="width:16.6%; text-align:left;">Producto</th>
                    <th style="width:16.7%; text-align:left;">Código</th>
                    <th style="width:16.7%; text-align:right;">Cantidad</th>
                    <th style="width:16.7%; text-align:right;">Precio Unitario</th>
                    <th style="width:16.6%; text-align:right;">Descuento Unitario</th>
                    <th style="width:16.6%; text-align:right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>';
    
        foreach ($detalleVenta as $detalle) {
            $html .= '<tr style="font-size:11px;">
                <td>' . htmlspecialchars($detalle['nombre_producto']) . '</td>
                <td>' . htmlspecialchars($detalle['codigo_producto']) . '</td>
                <td style="text-align:right;">' . htmlspecialchars($detalle['cantidad']) . '</td>
                <td style="text-align:right;">Bs. ' . number_format($detalle['precioVenta'], 2) . '</td>
                <td style="text-align:right;">Bs. ' . number_format($detalle['descuento'], 2) . '</td>
                <td style="text-align:right;">Bs. ' . number_format($detalle['subtotal'], 2) . '</td>
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
                <td style="text-align:right; width:20%;">Bs. ' . number_format($venta['subTotalVenta'], 2) . '</td>
            </tr>
            <tr>
                <td style="text-align:right;"><strong>Descuento Total:</strong></td>
                <td style="text-align:right;">Bs. ' . number_format($venta['descuento'], 2) . '</td>
            </tr>
            <tr style="background-color: #0c4b93; color: white;">
                <td style="text-align:right; font-size: 14px;"><strong>Total Venta:</strong></td>
                <td style="text-align:right; font-size: 14px;"><strong>Bs. ' . number_format($venta['totalVenta'], 2) . '</strong></td>
            </tr>
        </table>';
    
        // Pie de página
        $html .= '
        <hr style="color: #ccc; border-top: 1px solid #ccc;">
        <p style="text-align:center; font-size: 10px; color: #333;">
            "Este recibo contribuye al registro y control de las transacciones realizadas." <br>
            Ley N° 453: El proveedor debe exhibir y entregar el recibo correspondiente a las transacciones realizadas con los clientes.
        </p>
        <p style="text-align:center; font-size: 10px; color: #0c4b93;">
            ¡Gracias por su compra en DUKEL!
        </p>';
    
        // Generar el PDF
        // Establecer los encabezados para forzar la descarga en una nueva ventana
        //ob_end_clean(); // Limpia cualquier contenido previo en el buffer
        $pdf->writeHTML($html);
        $pdf->Output('recibo_' . $venta['numComprobante'] . '.pdf', 'I');
    }    
    
    public function imprimirTodas() {
        // Obtener todas las ventas
        $ventas = $this->Venta_model->obtener_ventas();
        
        if (empty($ventas)) {
            $this->session->set_flashdata('error', 'No hay ventas para mostrar.');
            redirect('ventas');
        }
    
        // Crear el PDF
        $pdf = new TCPDF('L', 'mm', 'A4');
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Dukel');
        $pdf->SetTitle('Resumen de Ventas');
        $pdf->SetSubject('Listado completo de ventas');
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetMargins(15, 20, 15); // Márgenes más definidos
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(true);
        $pdf->AddPage();
        // Agregar el logo en la esquina superior izquierda
    $logoPath = FCPATH . 'assets/img/logoTrans.PNG';
    if (file_exists($logoPath)) {
        $pdf->Image($logoPath, 6, -7, 80, 52, 'PNG'); // Ancho=50mm, Alto=30mm
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
        // Agregar imagen de membretado como marca de agua
        $imagePath = FCPATH . 'assets/img/logoTrans.PNG'; // Ruta relativa
        if (file_exists($imagePath)) {
            // Obtener dimensiones de la imagen
            list($width, $height) = getimagesize($imagePath);
            // Calcular la posición para centrar la imagen
            $scaleFactor = 0.1; // Ajusta el factor para aumentar el tamaño
            $x = ($pdf->getPageWidth() - ($width * $scaleFactor)) / 2; 
            $y = ($pdf->getPageHeight() - ($height * $scaleFactor)) / 2; 
            $pdf->SetAlpha(0.2);
            $pdf->Image($imagePath, $x, $y, $width * $scaleFactor, $height * $scaleFactor, 'PNG', '', '', false, 300, '', false, false, 0, false, false, false);
            $pdf->SetAlpha(1);
        } else {
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->Cell(0, 10, 'Error: No se pudo cargar la imagen de membretado.', 0, 1, 'C', 0, '', 0, false, 'T', 'M');
        }
        $pdf->Ln(7);
        // Establecer contenido del PDF
        $html = '
        <h1 style="text-align:center; color:#0c4b93;">Resumen de Ventas</h1>
        <table border="1" cellpadding="5" style="border-collapse: collapse; width:100%;">
            <thead>
                <tr style="background-color: #0c4b93; color: white;">
                    <th style="text-align:center; width: 6%;">N°</th>
                    <th style="text-align:center;">Cliente</th>
                    <th style="text-align:center; width: 18%;">Producto</th>
                    <th style="text-align:center; width: 17%;">Número Comprobante</th>
                    <th style="text-align:center; width: 16%;">Total Venta</th>
                    <th style="text-align:center; width: 12%;">Estado</th>
                    <th style="text-align:center;">Fecha</th>
                </tr>
            </thead>
            <tbody>';
    
        foreach ($ventas as $item) {
            // Formatear la fecha para que solo muestre la fecha (sin la hora)
            $fecha = date('d-m-Y', strtotime($item['fechaRegistro']));

            $html .= '<tr>
                        <td style="width: 6%; text-align: center;">' . htmlspecialchars($item['idVenta']) . '</td>
                        <td style="text-align: center;">' . htmlspecialchars($item['nombre_cliente']) . '</td>
                        <td style="width: 18%; text-align: center;">' . htmlspecialchars($item['nombre_producto']) . '</td>
                        <td style="width: 17%; text-align: center;">' . htmlspecialchars($item['numComprobante']) . '</td>
                        <td style="text-align:right; width: 16%; text-align: center;">Bs. ' . htmlspecialchars($item['totalVenta']) . '</td>
                        <td style="text-align:right; width: 12%; text-align: center;">' . (($item['estado'] == 1) ? 'Activo' : 'Inactivo') . '</td>
                        <td style="text-align: center;">' . $fecha . '</td>
                    </tr>';
        }
    
        $html .= '</tbody>
                  </table>';
    
        // Agregar contenido al PDF
        $pdf->writeHTML($html, true, false, true, false, '');

        // Salida del PDF
        $pdf->Output('reporte_ventas.pdf', 'I');
    }
    // Función para eliminar una venta
    public function eliminar($idVenta) {
        // Llamamos al método de eliminar venta en el modelo
        $resultado = $this->Venta_model->eliminarVenta($idVenta);
        
        if ($resultado) {
            // Si la venta fue eliminada correctamente, redirigimos o mostramos un mensaje de éxito
            $this->session->set_flashdata('success', 'Venta eliminada correctamente');
            redirect('ventas');  // Asegúrate de que esta ruta existe
        } else {
            // Si hubo un error, mostramos un mensaje de error
            $this->session->set_flashdata('error', 'Error al eliminar la venta');
            redirect('ventas');  // Asegúrate de que esta ruta existe
        }
    }
    // Función para obtener las ventas eliminadas
    public function eliminados() {
        // Obtenemos las ventas eliminadas desde el modelo
        $data['ventas'] = $this->Venta_model->obtener_ventas_eliminadas();
        
        // Cargamos la vista de ventas eliminadas
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('ventas/eliminados', $data);
        $this->load->view('templates/footer');
    }
}