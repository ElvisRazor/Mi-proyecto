<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reporte extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Reporte_model');
        $this->load->helper('url');
        $this->load->library(['session', 'form_validation']);
        require_once(APPPATH . 'libraries/tcpdf/tcpdf.php'); // Para el uso de TCPDF
    }

    public function ventasPorFechas() {
        $fechaInicio = $this->input->get('fecha_inicio');
        $fechaFin = $this->input->get('fecha_fin');
        $data['ventas'] = [];
        $data['total_general'] = 0;
    
        if (!empty($fechaInicio) && !empty($fechaFin)) {
            $resultado = $this->Reporte_model->getVentasPorFechas($fechaInicio, $fechaFin);
            $data['ventas'] = $resultado['ventas'];
            $data['total_general'] = $resultado['total_general'];
            $data['fecha_inicio'] = $fechaInicio;
            $data['fecha_fin'] = $fechaFin;
        }
    
        // Cargar la vista
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('reporte/ventasPorFechas', $data);
        $this->load->view('templates/footer');
    }    
    
    public function comprasPorFechas() {
        $fechaInicio = $this->input->get('fecha_inicio');
        $fechaFin = $this->input->get('fecha_fin');
        $data['compras'] = [];
        $data['total_general'] = 0;
    
        if (!empty($fechaInicio) && !empty($fechaFin)) {
            $resultado = $this->Reporte_model->getComprasPorFechas($fechaInicio, $fechaFin);
            $data['compras'] = $resultado['compras'];
            $data['total_general'] = $resultado['total_general'];
            $data['fecha_inicio'] = $fechaInicio;
            $data['fecha_fin'] = $fechaFin;
        }
    
        // Cargar la vista
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('reporte/comprasPorFechas', $data);
        $this->load->view('templates/footer');
    }
    
    public function clientesFieles() {
        $fechaInicio = $this->input->get('fecha_inicio');
        $fechaFin = $this->input->get('fecha_fin');
        $data['clientes'] = [];

        // Verificar si se proporcionan fechas
        if (!empty($fechaInicio) && !empty($fechaFin)) {
            $data['clientes'] = $this->Reporte_model->getClientesClasificados($fechaInicio, $fechaFin);
        }

        // Cargar la vista
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('reporte/clientesFieles', $data);
        $this->load->view('templates/footer');
    }

    public function imprimir_pdf() {
        // Cargar la librería TCPDF
        $this->load->library('tcpdf');
    
        // Crear nuevo PDF
        $pdf = new TCPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Pisosbol');
        $pdf->SetTitle('Reporte de Clientes Clasificados');
        $pdf->SetSubject('Reporte');
        $pdf->SetKeywords('Reporte, PDF, Clientes, Pisosbol');
    
        // Configuración de márgenes
        $pdf->SetMargins(15, 20, 15);
        $pdf->SetHeaderMargin(10);
        $pdf->SetFooterMargin(10);
    
        // Configurar encabezado y pie de página
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
    
        // Agregar página
        $pdf->AddPage();
    
        // Obtener datos enviados
        $input = json_decode(file_get_contents('php://input'), true);
        $clientes = $input['clientes'];
        $imagen = $input['imagen'];
    
        // Estilo del título
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->SetTextColor(0, 51, 102); // Azul oscuro
        $pdf->Cell(0, 10, 'Reporte de Clientes Clasificados', 0, 1, 'C');
    
        // Línea divisoria
        $pdf->SetDrawColor(0, 51, 102); // Azul oscuro
        $pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
        $pdf->Ln(10);
    
        // Estilo de tabla
        $pdf->SetFont('helvetica', '', 10);
        $html = '<table border="1" cellpadding="6" cellspacing="0" style="border-collapse: collapse; width: 100%; text-align: center;">
            <thead>
                <tr style="background-color: #003366; color: #ffffff;">
                    <th style="width: 10%; text-align: center;">N°</th>
                    <th style="width: 35%; text-align: left;">Cliente</th>
                    <th style="width: 15%; text-align: center;">Total Compras</th>
                    <th style="width: 20%; text-align: right;">Total Gasto (Bs)</th>
                    <th style="width: 20%; text-align: center;">Clasificación</th>
                </tr>
            </thead>
            <tbody>';
        $number = 1;
        foreach ($clientes as $cliente) {
            $html .= '<tr>
                <td style="text-align: center; width: 10%;">' . $number++ . '</td>
                <td style="text-align: center; width: 35%;">' . htmlspecialchars($cliente['nombre']) . '</td>
                <td style="text-align: center; width: 15%;">' . htmlspecialchars($cliente['total_compras']) . '</td>
                <td style="text-align: center; width: 20%;">Bs. ' . number_format($cliente['total_gasto'], 2) . '</td>
                <td style="text-align: center; width: 20%;">' . htmlspecialchars($cliente['clasificacion']) . '</td>
            </tr>';
        }
        $html .= '</tbody></table>';
        $pdf->writeHTML($html, true, false, true, false, '');
    
        // Espacio para el gráfico
        $pdf->Ln(10);
    
        // Agregar gráfico con tamaño ajustado
        $pdf->SetTextColor(0, 51, 102); // Azul oscuro
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Distribución de Clientes por Clasificación', 0, 1, 'C');
        $pdf->Image('@' . base64_decode(str_replace('data:image/png;base64,', '', $imagen)), 60, $pdf->GetY(), 90, 60, 'PNG');
    
        // Enviar PDF al navegador
        $pdf->Output('reporte_clientes.pdf', 'I');
    }

    public function imprimirConsultaVentas(){
    // Cargar la librería TCPDF
    $this->load->library('tcpdf');

    // Crear nuevo PDF
    $pdf = new TCPDF();
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Pisosbol');
    $pdf->SetTitle('Consulta de Ventas');
    $pdf->SetSubject('Reporte de Ventas');
    $pdf->SetKeywords('Ventas, PDF, Reporte, Pisosbol');

    // Configuración de márgenes
    $pdf->SetMargins(15, 20, 15);
    $pdf->SetHeaderMargin(10);
    $pdf->SetFooterMargin(10);

    // Configurar encabezado y pie de página
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // Agregar página
    $pdf->AddPage();

    // Obtener datos enviados desde el frontend
    $input = json_decode(file_get_contents('php://input'), true);
    $ventas = $input['ventas'];
    $grafico = $input['grafico'];
    $fecha_inicio = htmlspecialchars($input['fecha_inicio']);
    $fecha_fin = htmlspecialchars($input['fecha_fin']);
    $total_general = number_format($input['total_general'], 2);

    // Estilo del título
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->SetTextColor(0, 51, 102); // Azul oscuro
    $pdf->Cell(0, 10, 'Consulta de Ventas', 0, 1, 'C');

    // Línea divisoria
    $pdf->SetDrawColor(0, 51, 102); // Azul oscuro
    $pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
    $pdf->Ln(5);

    // Mostrar fechas y total general
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(0, 10, "Desde: $fecha_inicio  Hasta: $fecha_fin", 0, 1);
    $pdf->Cell(0, 10, "Total General: Bs. $total_general", 0, 1);
    $pdf->Ln(5);

    // Crear tabla de ventas
    $pdf->SetFont('helvetica', '', 10);
    $html = '<table border="1" cellpadding="6" cellspacing="0" style="border-collapse: collapse; width: 100%; text-align: center;">
        <thead>
            <tr style="background-color: #003366; color: #ffffff;">
                <th style="width: 10%; text-align: center;">N°</th>
                <th style="width: 30%; text-align: left;">Cliente</th>
                <th style="width: 15%; text-align: center;">Comprobante</th>
                <th style="width: 20%; text-align: center;">Fecha</th>
                <th style="width: 25%; text-align: right;">Total Venta (Bs)</th>
            </tr>
        </thead>
        <tbody>';
    $number = 1;
    foreach ($ventas as $venta) {
        $html .= '<tr>
            <td style="text-align: center; width: 10%;">' . $number++ . '</td>
            <td style="text-align: left; width: 30%;">' . htmlspecialchars($venta['cliente']) . '</td>
            <td style="text-align: center; width: 15%;">' . htmlspecialchars($venta['numComprobante']) . '</td>
            <td style="text-align: center; width: 20%;">' . htmlspecialchars($venta['fecha']) . '</td>
            <td style="text-align: right; width: 25%;">Bs. ' . number_format($venta['total_venta'], 2) . '</td>
        </tr>';
    }
    $html .= '</tbody></table>';
    $pdf->writeHTML($html, true, false, true, false, '');

    // Espacio para el gráfico
    $pdf->Ln(5);

    // Agregar gráfico
    $pdf->SetTextColor(0, 51, 102); // Azul oscuro
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, 'Distribución de Ventas por Cliente', 0, 1, 'C');
    $pdf->Image('@' . base64_decode(str_replace('data:image/png;base64,', '', $grafico)), 30, $pdf->GetY(), 150, 80, 'PNG');

    // Enviar PDF al navegador
    $pdf->Output('consulta_ventas.pdf', 'I');
    }

    public function imprimirConsultaCompras(){
        // Cargar la librería TCPDF
        $this->load->library('tcpdf');
    
        // Crear nuevo PDF
        $pdf = new TCPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Pisosbol');
        $pdf->SetTitle('Consulta de Compras');
        $pdf->SetSubject('Reporte de Compras');
        $pdf->SetKeywords('Compras, PDF, Reporte, Pisosbol');
    
        // Configuración de márgenes
        $pdf->SetMargins(15, 20, 15);
        $pdf->SetHeaderMargin(10);
        $pdf->SetFooterMargin(10);
    
        // Configurar encabezado y pie de página
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
    
        // Agregar página
        $pdf->AddPage();
    
        // Obtener datos enviados desde el frontend
        $input = json_decode(file_get_contents('php://input'), true);
        $compras = $input['compras'];
        $grafico = $input['grafico'];
        $fecha_inicio = htmlspecialchars($input['fecha_inicio']);
        $fecha_fin = htmlspecialchars($input['fecha_fin']);
        $total_general = number_format($input['total_general'], 2);
    
        // Estilo del título
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->SetTextColor(0, 51, 102); // Azul oscuro
        $pdf->Cell(0, 10, 'Consulta de Compras', 0, 1, 'C');
    
        // Línea divisoria
        $pdf->SetDrawColor(0, 51, 102); // Azul oscuro
        $pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
        $pdf->Ln(5);
    
        // Mostrar fechas y total general
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 10, "Desde: $fecha_inicio  Hasta: $fecha_fin", 0, 1);
        $pdf->Cell(0, 10, "Total General: Bs. $total_general", 0, 1);
        $pdf->Ln(5);
    
        // Crear tabla de compras
        $pdf->SetFont('helvetica', '', 10);
        $html = '<table border="1" cellpadding="6" cellspacing="0" style="border-collapse: collapse; width: 100%; text-align: center;">
            <thead>
                <tr style="background-color: #003366; color: #ffffff;">
                    <th style="width: 10%; text-align: center;">N°</th>
                    <th style="width: 30%; text-align: left;">Proveedor</th>
                    <th style="width: 15%; text-align: center;">Comprobante</th>
                    <th style="width: 20%; text-align: center;">Fecha</th>
                    <th style="width: 25%; text-align: right;">Total Compra (Bs)</th>
                </tr>
            </thead>
            <tbody>';
        $number = 1;
        foreach ($compras as $compra) {
            $html .= '<tr>
                <td style="text-align: center; width: 10%;">' . $number++ . '</td>
                <td style="text-align: left; width: 30%;">' . htmlspecialchars($compra['proveedor']) . '</td>
                <td style="text-align: center; width: 15%;">' . htmlspecialchars($compra['numComprobante']) . '</td>
                <td style="text-align: center; width: 20%;">' . htmlspecialchars($compra['fecha']) . '</td>
                <td style="text-align: right; width: 25%;">Bs. ' . number_format($compra['total_compra'], 2) . '</td>
            </tr>';
        }
        $html .= '</tbody></table>';
        $pdf->writeHTML($html, true, false, true, false, '');
    
        // Espacio para el gráfico
        $pdf->Ln(5);
    
        // Agregar gráfico
        $pdf->SetTextColor(0, 51, 102); // Azul oscuro
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Distribución de Compras por Proveedor', 0, 1, 'C');
        $pdf->Image('@' . base64_decode(str_replace('data:image/png;base64,', '', $grafico)), 30, $pdf->GetY(), 150, 80, 'PNG');
    
        // Enviar PDF al navegador
        $pdf->Output('consulta_compras.pdf', 'I');
    }    
}