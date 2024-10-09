<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ventas extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Venta_model');
        $this->load->helper('url');
        $this->load->library(['session', 'form_validation']);
    }

    public function index() {
        $data['venta'] = $this->Venta_model->obtener_ventas();
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('ventas/index', $data);
        $this->load->view('templates/footer');
    }

    public function agregar() {
        if ($this->input->post()) {
            // Validar formulario
            $this->form_validation->set_rules('idCliente', 'Cliente', 'required');
            $this->form_validation->set_rules('fechaRegistro', 'Fecha', 'required');
            $this->form_validation->set_rules('totalVenta', 'Total Venta', 'required|numeric');
    
            if ($this->form_validation->run() === TRUE) {
                $this->Venta_model->iniciar_transaccion();  // Iniciar transacción
    
                try {
                    // Datos generales de la venta
                    $data = [
                        'idCliente' => $this->input->post('idCliente'),
                        'fechaRegistro' => $this->input->post('fechaRegistro'),
                        'tipoComprobante' => $this->input->post('tipoComprobante'),
                        'numComprobante' => $this->input->post('numComprobante'),
                        'totalVenta' => $this->input->post('totalVenta'),
                        'estado' => 1 // Activo
                    ];
    
                    // Detalles de los productos
                    $detalles = [];
                    $productos = $this->input->post('producto');
                    $cantidades = $this->input->post('cantidad');
                    $precios = $this->input->post('precio');
    
                    for ($i = 0; $i < count($productos); $i++) {
                        // Validar si el producto existe y tiene stock
                        $producto = $this->Venta_model->obtener_producto_por_id($productos[$i]);
                        if ($producto && $producto['stock'] >= $cantidades[$i]) {
                            $detalles[] = [
                                'idProducto' => $productos[$i],
                                'cantidad' => $cantidades[$i],
                                'precioVenta' => $precios[$i],
                                'subtotal' => $precios[$i] * $cantidades[$i]
                            ];
                        } else {
                            throw new Exception("Producto no válido o sin stock suficiente");
                        }
                    }
    
                    // Insertar venta y detalles
                    if (!$this->Venta_model->agregar_venta($data, $detalles)) {
                        throw new Exception("Error al agregar la venta");
                    }
    
                    $this->session->set_flashdata('mensaje', 'Venta agregada correctamente.');
                    redirect('ventas');
    
                } catch (Exception $e) {
                    // Manejar errores
                    $this->Venta_model->finalizar_transaccion();  // Revertir transacción
                    $this->session->set_flashdata('error', $e->getMessage());
                }
            } else {
                $this->session->set_flashdata('error', validation_errors());
            }
        }
    
        // Cargar productos disponibles
        $data['producto'] = $this->Venta_model->obtener_productos_con_stock();
    
        // Cargar clientes
        $data['cliente'] = $this->Venta_model->obtener_clientes(); // Aquí agregamos la carga de clientes
    
        // Cargar la vista
        $this->load->view('templates/header');
        $this->load->view('templates/navbar');
        $this->load->view('templates/sidebar');
        $this->load->view('ventas/agregar', $data);
        $this->load->view('templates/footer');
    }    
}
