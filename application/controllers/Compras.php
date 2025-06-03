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
                $totalCompraGeneral = 0;
    
                // Obtener el último número de comprobante
                $ultimoComprobante = $this->Compra_model->obtenerUltimoComprobante();
                $numComprobante = isset($ultimoComprobante['numComprobante']) ? intval($ultimoComprobante['numComprobante']) + 1 : 1;
                $numComprobante = str_pad($numComprobante, 5, '0', STR_PAD_LEFT);
    
                // Procesar cada producto
                foreach ($productos as $index => $idProducto) {
                    $cantidad = $cantidades[$index];
    
                    $producto = $this->Producto_model->obtenerProductoPorId($idProducto);
                    if ($producto) {
                        $precioCompra = $producto['precioCompra'];
                        $totalProducto = $cantidad * $precioCompra;
                        $totalCompraGeneral += $totalProducto;
    
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
                    'totalCompra' => $totalCompraGeneral
                ];
    
                $idCompra = $this->Compra_model->insertarCompra($compraData);
    
                // Inserta los detalles de la compra con el total separado por producto
                foreach ($productos as $index => $idProducto) {
                    $cantidad = $cantidades[$index];
                    $producto = $this->Producto_model->obtenerProductoPorId($idProducto);
                    $precioCompra = $producto['precioCompra'];
                    $totalProducto = $cantidad * $precioCompra;
    
                    $detalleCompraData = [
                        'idCompra' => $idCompra,
                        'idProducto' => $idProducto,
                        'cantidad' => $cantidad,
                        'precioCompra' => $precioCompra,
                        'totalCompra' => $totalProducto // Aquí se guarda el total por producto
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

    public function editar($idCompra) {
        if ($this->input->method() == 'post') {
            $idProveedor = $this->input->post('idProveedor');
            $productos = $this->input->post('producto');
            $cantidades = $this->input->post('cantidad');
    
            if (empty($idProveedor) || empty($productos)) {
                $this->session->set_flashdata('error', 'Proveedor o productos no pueden estar vacíos.');
                redirect("compras/editar/$idCompra");
            }
    
            $this->db->trans_begin();
    
            try {
                $totalCompra = 0;
                $productosOriginales = $this->Compra_model->obtener_detalles_compra($idCompra);
    
                // Guardamos las cantidades originales para calcular diferencias
                $cantidadesOriginales = [];
                foreach ($productosOriginales as $producto) {
                    $cantidadesOriginales[$producto['idProducto']] = $producto['cantidad'];
                }
    
                // Actualizamos datos de la compra sin modificar el stock todavía
                $this->Compra_model->actualizarCompra($idCompra, ['idProveedor' => $idProveedor]);
    
                foreach ($productos as $index => $idProducto) {
                    $cantidadNueva = $cantidades[$index];
                    $producto = $this->Producto_model->obtenerProductoPorId($idProducto);
    
                    if ($producto) {
                        $precioCompra = $producto['precioCompra'];
                        $totalCompra += $cantidadNueva * $precioCompra;
    
                        // Calculamos la diferencia entre la cantidad nueva y la original
                        $cantidadOriginal = isset($cantidadesOriginales[$idProducto]) ? $cantidadesOriginales[$idProducto] : 0;
                        $diferenciaCantidad = $cantidadNueva - $cantidadOriginal;
    
                        // Actualizamos el stock del producto solo con la diferencia
                        $nuevoStock = $producto['stock'] + $diferenciaCantidad;
                        $this->Producto_model->actualizarStock($idProducto, $nuevoStock);
    
                        // Actualizamos o insertamos el detalle de la compra
                        if ($cantidadOriginal > 0) {
                            $this->Compra_model->actualizarDetalleCompra($idCompra, $idProducto, ['cantidad' => $cantidadNueva, 'precioCompra' => $precioCompra]);
                        } else {
                            $detalleCompraData = [
                                'idCompra' => $idCompra,
                                'idProducto' => $idProducto,
                                'cantidad' => $cantidadNueva,
                                'precioCompra' => $precioCompra
                            ];
                            $this->Compra_model->insertarDetalleCompra($detalleCompraData);
                        }
                    } else {
                        throw new Exception("Producto no encontrado en la base de datos.");
                    }
                }
    
                // Actualizamos el total de la compra en la tabla de Compra
                $this->Compra_model->actualizarCompra($idCompra, ['totalCompra' => $totalCompra]);
    
                // También actualizamos el total de la compra en cada detalle
                foreach ($productos as $index => $idProducto) {
                    $producto = $this->Producto_model->obtenerProductoPorId($idProducto);
                    $precioCompra = $producto['precioCompra'];
                    $detalleCompraData = [
                        'totalCompra' => $totalCompra // Se agrega el totalCompra al detalle
                    ];
                    $this->Compra_model->actualizarDetalleCompra($idCompra, $idProducto, $detalleCompraData);
                }
    
                // Eliminamos productos que ya no están en la compra
                foreach ($productosOriginales as $producto) {
                    if (!in_array($producto['idProducto'], $productos)) {
                        $this->Compra_model->eliminarDetalleCompra($idCompra, $producto['idProducto']);
                    }
                }
    
                if ($this->db->trans_status() === FALSE) {
                    throw new Exception('Error en la transacción de la compra');
                }
                $this->db->trans_commit();
                $this->session->set_flashdata('success', 'Compra actualizada con éxito');
                redirect('compras');
            } catch (Exception $e) {
                $this->db->trans_rollback();
                $this->session->set_flashdata('error', 'Error: ' . $e->getMessage());
                redirect("compras/editar/$idCompra");
            }
        }
    
        $compra = $this->Compra_model->obtener_compra_por_id($idCompra);
        $productos = $this->Compra_model->obtener_detalles_compra($idCompra);
        $proveedores = $this->Proveedor_model->obtener_proveedores_activos();
        $todosProductos = $this->Producto_model->obtener_productos_activos();
    
        $data = [
            'compra' => $compra,
            'productos' => $productos,
            'proveedores' => $proveedores,
            'todosProductos' => $todosProductos
        ];
    
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('compras/editar', $data);
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
        $imagePath = FCPATH . 'assets/img/logoTrans.PNG'; // Ruta de la imagen del logotipo
        if (file_exists($imagePath)) {
            $pdf->Image($imagePath, 120, 32, 100, '', 'PNG'); // Logotipo
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
                    <span style="font-size: 12px; color:#0c4b93;">Recibo N°: ' . htmlspecialchars($compra['numComprobante']) . '</span><br>
                    <span style="font-size: 10px;">Fecha: ' . htmlspecialchars($compra['fechaRegistro']) . '</span><br>
                </td>
            </tr>
        </table>
        <hr style="color: #0c4b93; border-top: 2px solid #0c4b93;">';
        // Información del proveedor y la compra
        $html .= '
        <h1 style="color:#0c4b93; font-size:20px; text-align:center;">RECIBO</h1>
        <h3 style="color:#0c4b93; font-size:14px;">Datos del Proveedor</h3>
        <table cellpadding="3" style="width:100%;">
            <tr>
                <td style="width:50%;"><strong>Nombre:</strong> ' . htmlspecialchars($compra['nombre_proveedor']) . '</td>
            </tr>
            <tr>
                <td style="width:50%;"><strong>Email:</strong> ' . htmlspecialchars($compra['email_proveedor']) . '</td>
            </tr>
            <tr>
                <td style="width:50%;"><strong>Teléfono:</strong> ' . htmlspecialchars($compra['telefono_proveedor']) . '</td>
            </tr>
            <tr>
                <td style="width:50%;"><strong>Dirección:</strong> ' . htmlspecialchars($compra['direccion_proveedor']) . '</td>
            </tr>
        </table>
        <hr style="color: #ccc; border-top: 1px solid #ccc;">';
    
        // Detalles de la compra en una tabla profesional
        $html .= '
        <h3 style="color:#0c4b93; font-size:14px;">Detalles de la Compra</h3>
        <table border="1" cellpadding="3" style="border-collapse: collapse; width:100%; font-size:12px;">
            <thead>
                <tr style="background-color: #f3f3f3; color: #333;">
                    <th style="width:20%; text-align:left;">Producto</th>
                    <th style="width:20%; text-align:left;">Código</th>
                    <th style="width:20%; text-align:right;">Cantidad</th>
                    <th style="width:20%; text-align:right;">Precio Unitario</th>
                    <th style="width:20%; text-align:right;">Total</th>
                </tr>
            </thead>
            <tbody>';
        
        foreach ($detalleCompra as $detalle) {
            $html .= '<tr style="font-size:11px;">
                <td>' . htmlspecialchars($detalle['nombre_producto']) . '</td>
                <td>' . htmlspecialchars($detalle['codigo_producto']) . '</td>
                <td style="text-align:right;">' . htmlspecialchars($detalle['cantidad']) . '</td>
                <td style="text-align:right;">Bs. ' . number_format($detalle['precioCompra'], 2) . '</td>
                <td style="text-align:right;">Bs. ' . number_format($detalle['totalCompra'], 2) . '</td>
            </tr>';
        }

        $html .= '</tbody>
        </table>';
    
        // Resumen de la compra con total resaltado
        $html .= '
        <h3 style="color:#0c4b93; font-size:14px;">Resumen de la Compra</h3>
        <table cellpadding="5" style="width:100%;">
            <tr style="background-color: #0c4b93; color: white;">
                <td style="text-align:right; width:80%;"><strong>Total Compra:</strong></td>
                <td style="text-align:right; width:20%;">Bs. ' . number_format($compra['totalCompra'], 2) . '</td>
            </tr>
        </table>';
    
        // Pie de página
        $html .= '
        <hr style="color: #ccc; border-top: 1px solid #ccc;">
        <p style="text-align:center; font-size: 10px; color: #333;">
            "Este recibo contribuye al registro y control de las transacciones realizadas." <br>
            Ley N° 453: El proveedor debe exhibir y entregar el recibo correspondiente a las transacciones realizadas con los clientes.
        </p>
        <p style="text-align:center; font-size: 10px; font-style: italic;"> Cochabamba, Bolivia | Contacto: +591 67476946</p>';
    
        // Agregar el contenido al PDF
        $pdf->writeHTML($html, true, false, true, false, '');
    
        // Salida del PDF
        $pdf->Output('recibo_compra_' . $compra['numComprobante'] . '.pdf', 'I');
    }        

    public function imprimir_todas(){
    // Obtener todas las compras
    $compras = $this->Compra_model->obtener_compras();

    if (empty($compras)) {
        $this->session->set_flashdata('error', 'No se encontraron compras para imprimir.');
        redirect('compras');
        return;
    }

    // Crear el PDF
    $pdf = new TCPDF('L', 'mm', 'A4');
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Dukel');
    $pdf->SetTitle('Resumen de Compras');
    $pdf->SetSubject('Listado completo de compras');
    $pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
    $pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);
    $pdf->SetMargins(15, 20, 15); // Márgenes más definidos
    $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
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

    // Agregar imagen de marca de agua
    $imagePath = FCPATH . 'assets/img/logoTrans.PNG';
    if (file_exists($imagePath)) {
        list($width, $height) = getimagesize($imagePath);
        $scaleFactor = 0.1;
        $x = ($pdf->getPageWidth() - ($width * $scaleFactor)) / 2;
        $y = ($pdf->getPageHeight() - ($height * $scaleFactor)) / 2;
        $pdf->SetAlpha(0.2); // Transparencia ligera
        $pdf->Image($imagePath, $x, $y, $width * $scaleFactor, $height * $scaleFactor, 'PNG');
        $pdf->SetAlpha(1); // Restablecer opacidad
    } else {
        $this->session->set_flashdata('error', 'No se pudo cargar la imagen de fondo.');
        redirect('compras');
        return;
    }

    $pdf->Ln(6);
    // Contenido del PDF
    $html = '
    <h1 style="text-align:center; color:#0c4b93; margin-bottom: 20px;">Resumen de Compras</h1>
    <table border="1" cellpadding="5" style="border-collapse: collapse; width:100%; font-size: 10px;">
        <thead>
            <tr style="background-color: #0c4b93; color: white; text-align: center;">
                <th style="width: 5%;">N°</th>
                <th style="width: 20%;">Número de Comprobante</th>
                <th style="width: 18%;">Producto</th>
                <th style="width: 22%;">Proveedor</th>
                <th style="width: 20%;">Total Compra</th>
                <th style="width: 17%;">Fecha</th>
            </tr>
        </thead>
        <tbody>';

    foreach ($compras as $item) {
        $html .= '<tr>
                    <td style="text-align: center; width: 5%;">' . htmlspecialchars($item['idCompra_compra']) . '</td>
                    <td style="text-align: center; width: 20%;">' . htmlspecialchars($item['numComprobante']) . '</td>
                    <td style="text-align: center; width: 18%;">' . htmlspecialchars($item['nombre_producto']) . '</td>
                    <td style="text-align: center; width: 22%">' . htmlspecialchars($item['nombre_proveedor']) . '</td>
                    <td style="text-align: center; width: 20%">Bs. ' . number_format($item['totalCompra'], 2) . '</td>
                    <td style="text-align: center; width: 17%">' . htmlspecialchars($item['fechaRegistro']) . '</td>
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
