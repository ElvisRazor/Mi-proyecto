<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Venta_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function obtenerUltimoComprobante() {
        $this->db->select('numComprobante');
        $this->db->order_by('idVenta', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('venta');

        return $query->row_array();
    }

    // Agregar un nuevo cliente
    public function agregar_cliente($data) {
        return $this->db->insert('cliente', $data);
    }

    public function insertarVenta($data) {
        // Asegúrate de incluir todos los campos que desees insertar en la base de datos
        $this->db->insert('venta', $data);
        return $this->db->insert_id(); // Devuelve el ID de la venta insertada
    }    

    public function insertarDetalleVenta($data) {
        $this->db->insert('detalleventa', $data);
    }

    // Obtener todas las ventas activas
    public function obtener_ventas() {
        $this->db->select('v.*, c.nombre AS nombre_cliente, p.nombre AS nombre_producto');
        $this->db->from('venta v');
        $this->db->join('cliente c', 'v.idCliente = c.idCliente');
        $this->db->join('venta dv', 'v.idVenta = dv.idVenta');
        $this->db->join('producto p', 'dv.idProducto = p.idProducto');
        
        // Ordenar por fechaRegistro de manera ascendente
        $this->db->order_by('v.fechaRegistro', 'ASC');
        
        $query = $this->db->get();
        return $query->result_array();
    }
    

    // Obtener la venta por ID, incluyendo los datos adicionales del cliente
    public function obtener_venta_por_id($idVenta) {
        $this->db->select('
            v.*, 
            c.nombre AS nombre_cliente,
            c.tipoDocumento AS tipo_documento_cliente, 
            c.numDocumento AS num_documento_cliente,
            c.email AS email_cliente,
            c.telefono AS telefono_cliente,
            c.direccion AS direccion_cliente'
        );
        $this->db->from('venta v');
        $this->db->join('cliente c', 'v.idCliente = c.idCliente');
        $this->db->where('v.idVenta', $idVenta);
        $query = $this->db->get();
        return $query->row_array();
    }

    // Obtener los detalles de la venta, incluyendo código de producto y descuento unitario
    public function obtener_detalles_venta($idVenta) {
        $this->db->select('
            dv.*, 
            p.nombre AS nombre_producto, 
            p.codigo AS codigo_producto, 
            dv.descuento'
        );
        $this->db->from('detalleventa dv');
        $this->db->join('producto p', 'dv.idProducto = p.idProducto');
        $this->db->where('dv.idVenta', $idVenta);
        $query = $this->db->get();
        return $query->result_array();
    }

    // Actualizar el stock del producto
    public function actualizarStock($idProducto, $nuevoStock) {
        $this->db->set('stock', $nuevoStock);
        $this->db->where('idProducto', $idProducto);
        $this->db->update('producto');
    }

    // Obtener producto por ID
    public function obtenerProductoPorId($idProducto) {
        $this->db->where('idProducto', $idProducto);
        $query = $this->db->get('producto');
        return $query->row_array();
    }
    // Obtener cliente por ID
    public function obtenerClientePorId($idCliente) {
        $this->db->where('idCliente', $idCliente);
        $query = $this->db->get('cliente');
        return $query->row_array();
    }
    // Obtener todos los clientes activos
    public function obtener_clientes() {
        $this->db->where('estado', 1); // Solo clientes activos
        $query = $this->db->get('cliente');
        return $query->result_array(); // Devuelve todos los resultados como un array
    }
    //#################
    // Obtener las ventas totales por mes (para gráfico)
    public function ventas_por_mes($anio) {
        $this->db->select('MONTH(v.fechaRegistro) AS mes, SUM(dv.cantidad * dv.precioVenta) AS total_venta');
        $this->db->from('venta v');
        $this->db->join('detalleVenta dv', 'dv.idVenta = v.idVenta');
        $this->db->where('YEAR(v.fechaRegistro)', $anio);
        $this->db->group_by('MONTH(v.fechaRegistro)');
        $this->db->order_by('MONTH(v.fechaRegistro)', 'ASC');
        return $this->db->get()->result();
    }
    // Obtener las ventas totales (para gráfico de barras)
    public function ventas_totales() {
        $this->db->select('SUM(dv.cantidad * dv.precioVenta) AS total_venta');
        $this->db->from('venta v');
        $this->db->join('detalleVenta dv', 'dv.idVenta = v.idVenta');
        return $this->db->get()->row();
    }
    // Obtener las ventas por categoría de producto
    public function ventas_por_categoria() {
        $this->db->select('p.nombre AS categoria, SUM(dv.cantidad * dv.precioVenta) AS total_venta');
        $this->db->from('detalleVenta dv');
        $this->db->join('producto p', 'p.idProducto = dv.idProducto');
        $this->db->group_by('p.nombre');
        return $this->db->get()->result();
    }
    ########
    public function eliminarVenta($idVenta) {
        // Iniciar una transacción para asegurar la consistencia
        $this->db->trans_begin();
        
        // Obtener los detalles de la venta para restaurar el stock
        $this->db->select('dv.idProducto, dv.cantidad');
        $this->db->from('detalleventa dv');
        $this->db->where('dv.idVenta', $idVenta);
        $detalleVenta = $this->db->get()->result_array();
    
        // Restaurar el stock de los productos involucrados en la venta
        foreach ($detalleVenta as $detalle) {
            // Obtener el producto
            $producto = $this->obtenerProductoPorId($detalle['idProducto']);
            
            // Calcular el nuevo stock
            $nuevoStock = $producto['stock'] + $detalle['cantidad'];
            
            // Actualizar el stock del producto
            $this->actualizarStock($detalle['idProducto'], $nuevoStock);
        }
    
        // Cambiar el estado de la venta a 0 (eliminada o cancelada)
        $this->db->set('estado', 0);
        $this->db->where('idVenta', $idVenta);
        $this->db->update('venta');
        
        // Verificar si la transacción fue exitosa
        if ($this->db->trans_status() === FALSE) {
            // Si algo falló, revertir los cambios
            $this->db->trans_rollback();
            return FALSE;
        } else {
            // Si todo salió bien, confirmar la transacción
            $this->db->trans_commit();
            return TRUE;
        }
    }
    
    public function obtener_ventas_eliminadas() {
        $this->db->select('v.*, c.nombre AS nombre_cliente, p.nombre AS nombre_producto');
        $this->db->from('venta v');
        $this->db->join('cliente c', 'v.idCliente = c.idCliente');
        $this->db->join('detalleventa dv', 'v.idVenta = dv.idVenta');
        $this->db->join('producto p', 'dv.idProducto = p.idProducto');
        
        // Solo ventas eliminadas (estado 0)
        $this->db->where('v.estado', 0);
        
        $this->db->order_by('v.fechaRegistro', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
}
