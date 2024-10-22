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
        <h1 style="text-align:center; color:#0c4b93;">Recibo de Compra</h1>
        <h3 style="color:#0c4b93;">Información de la Compra</h3>
        <p><strong>Número de Comprobante:</strong> ' . htmlspecialchars($compra['numComprobante']) . '</p>
        <p><strong>Fecha:</strong> ' . htmlspecialchars($compra['fechaRegistro']) . '</p>
        <p><strong>Proveedor:</strong> ' . htmlspecialchars($compra['nombre_proveedor']) . '</p>';

        $html .= '<h3 style="color:#0c4b93;">Detalles de la Compra</h3>
                  <table border="1" cellpadding="5" style="border-collapse: collapse; width:100%;">
                      <thead>
                          <tr style="background-color: #0c4b93; color: white;">
                              <th style="text-align:left;">Producto</th>
                              <th style="text-align:right;">Cantidad</th>
                              <th style="text-align:right;">Precio Unitario</th>
                          </tr>
                      </thead>
                      <tbody>';

        foreach ($detalleCompra as $detalle) {
            $html .= '<tr>
                        <td>' . htmlspecialchars($detalle['nombre_producto']) . '</td>
                        <td style="text-align:right;">' . htmlspecialchars($detalle['cantidad']) . '</td>
                        <td style="text-align:right;">' . htmlspecialchars($detalle['precio']) . '</td>
                    </tr>';
        }

        $html .= '</tbody>
                  </table>';

        // Totales
        $html .= '
        <h3 style="color:#0c4b93;">Resumen de la Compra</h3>
        <p><strong>Total Compra:</strong> ' . number_format($compra['totalCompra'], 2) . '</p>';

        // Agregar el contenido al PDF
        $pdf->writeHTML($html, true, false, true, false, '');

        // Salida del PDF
        $pdf->Output('recibo_compra_' . $compra['numComprobante'] . '.pdf', 'I');
    }
}
