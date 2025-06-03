<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Compra_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    // Obtener el último comprobante de compra
    public function obtenerUltimoComprobante() {
        $this->db->select('numComprobante');
        $this->db->order_by('idCompra', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('compra');
        return $query->row_array();
    }

    // Insertar una compra en la base de datos
    public function insertarCompra($data) {
        $this->db->insert('compra', $data);
        return $this->db->insert_id(); // Devuelve el ID de la compra insertada
    }

    // Insertar un detalle de compra
    public function insertarDetalleCompra($data) {
        $this->db->insert('detallecompra', $data);
    }

    // Obtener todas las compras activas
    public function obtener_compras() {
        $this->db->select('c.*, p.nombre AS nombre_proveedor, pr.nombre AS nombre_producto, c.idCompra AS idCompra_compra');
        $this->db->from('compra c');
        $this->db->join('proveedor p', 'c.idProveedor = p.idProveedor');
        $this->db->join('detallecompra dc', 'dc.idCompra = c.idCompra');
        $this->db->join('producto pr', 'dc.idProducto = pr.idProducto');
        // Ordenar por fechaRegistro de manera ascendente
        $this->db->order_by('c.fechaRegistro', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    // Obtener una compra por ID
    public function obtener_compra_por_id($idCompra) {
        $this->db->select('c.*, p.nombre AS nombre_proveedor, p.email AS email_proveedor, p.telefono AS telefono_proveedor, p.direccion AS direccion_proveedor');
        $this->db->from('compra c');
        $this->db->join('proveedor p', 'c.idProveedor = p.idProveedor');
        $this->db->where('c.idCompra', $idCompra);
        $query = $this->db->get();
        return $query->row_array();
    }

    // Obtener los detalles de una compra por ID
    public function obtener_detalles_compra($idCompra, $idProducto = null) {
        $this->db->select('dc.*, pr.nombre AS nombre_producto, pr.codigo AS codigo_producto');
        $this->db->from('detallecompra dc');
        $this->db->join('producto pr', 'dc.idProducto = pr.idProducto');
        $this->db->where('dc.idCompra', $idCompra);
        
        // Filtro opcional por idProducto si se proporciona
        if ($idProducto !== null) {
            $this->db->where('dc.idProducto', $idProducto);
        }
    
        $query = $this->db->get();
        
        // Devolver un solo detalle si se especifica idProducto, o todos si no se especifica
        return ($idProducto !== null) ? $query->row_array() : $query->result_array();
    }    
    
    // Actualizar el stock del producto
    public function actualizarStock($idProducto, $nuevoStock) {
        $this->db->set('stock', $nuevoStock);
        $this->db->where('idProducto', $idProducto);
        $this->db->update('producto');
    }

    // Obtener producto por ID
    public function obtenerProductoPorId($idProducto) {
        $this->db->select('idProducto, nombre, precioCompra, stock');
        $this->db->where('idProducto', $idProducto);
        $query = $this->db->get('producto');
        return $query->row_array();
    }

    // Obtener proveedor por ID
    public function obtenerProveedorPorId($idProveedor) {
        $this->db->where('idProveedor', $idProveedor);
        $query = $this->db->get('proveedor');
        return $query->row_array();
    }

    // Obtener todos los proveedores activos
    public function obtener_proveedores_activos() {
        $this->db->where('estado', 1); // Solo proveedores activos
        $query = $this->db->get('proveedor');
        return $query->result_array(); // Devuelve todos los resultados como un array
    }

    // #############
    public function actualizarCompra($idCompra, $data) {
        $this->db->where('idCompra', $idCompra);
        $this->db->update('compra', $data);
    }

    public function actualizarDetalleCompra($idCompra, $idProducto, $data) {
        $this->db->where('idCompra', $idCompra);
        $this->db->where('idProducto', $idProducto);
        $this->db->update('detallecompra', $data);
    }

    public function eliminarDetalleCompra($idCompra, $idProducto) {
        $this->db->where('idCompra', $idCompra);
        $this->db->where('idProducto', $idProducto);
        $this->db->delete('detallecompra');
    }
    // #################
    public function obtener_compras_por_fechas($fechaInicio = null, $fechaFin = null) {
        $this->db->select('c.idCompra, c.fechaRegistro, p.nombre AS proveedor, SUM(dc.cantidad) AS cantidad_productos, SUM(dc.cantidad * dc.precioCompra) AS total_compra');
        $this->db->from('compra c');
        $this->db->join('detalleCompra dc', 'dc.idCompra = c.idCompra');
        $this->db->join('proveedor p', 'p.idProveedor = c.idProveedor');
    
        if (!empty($fechaInicio) && !empty($fechaFin)) {
            $this->db->where('c.fechaRegistro >=', date('Y-m-d', strtotime($fechaInicio)));
            $this->db->where('c.fechaRegistro <=', date('Y-m-d', strtotime($fechaFin)));
        }
    
        $this->db->where('c.estado', 1);
        $this->db->group_by('c.idCompra, c.fechaRegistro, p.nombre');
        $this->db->order_by('c.fechaRegistro', 'ASC');
    
        return $this->db->get()->result_array();
    }
}
