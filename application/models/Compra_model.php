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
        $this->db->select('c.*, p.nombre AS nombre_proveedor, pr.nombre AS nombre_producto');
        $this->db->from('compra c');
        $this->db->join('proveedor p', 'c.idProveedor = p.idProveedor');
        $this->db->join('detallecompra dc', 'c.idCompra = dc.idCompra');
        $this->db->join('producto pr', 'dc.idProducto = pr.idProducto');
        $query = $this->db->get();
        return $query->result_array();
    }

    // Obtener una compra por ID
    public function obtener_compra_por_id($idCompra) {
        $this->db->select('c.*, p.nombre AS nombre_proveedor');
        $this->db->from('compra c');
        $this->db->join('proveedor p', 'c.idProveedor = p.idProveedor');
        $this->db->where('c.idCompra', $idCompra);
        $query = $this->db->get();
        return $query->row_array();
    }

    // Obtener los detalles de una compra por ID
    public function obtener_detalles_compra($idCompra) {
        $this->db->select('dc.*, pr.nombre AS nombre_producto');
        $this->db->from('detallecompra dc');
        $this->db->join('producto pr', 'dc.idProducto = pr.idProducto');
        $this->db->where('dc.idCompra', $idCompra);
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

    // #################
    // Obtener compras de la semana actual
    public function obtener_compras_semanales() {
        $this->db->where('fechaRegistro >=', date('Y-m-d H:i:s', strtotime('-1 week')));
        return $this->db->count_all_results('compra');
    }

    // Obtener compras del mes actual
    public function obtener_compras_mensuales() {
        $this->db->where('fechaRegistro >=', date('Y-m-d H:i:s', strtotime('-1 month')));
        return $this->db->count_all_results('compra');
    }

    // Obtener compras de la semana anterior
    public function obtener_compras_semana_anterior() {
        $this->db->where('fechaRegistro >=', date('Y-m-d H:i:s', strtotime('-2 weeks')));
        $this->db->where('fechaRegistro <', date('Y-m-d H:i:s', strtotime('-1 week')));
        return $this->db->count_all_results('compra');
    }

    // Obtener compras del mes anterior
    public function obtener_compras_mes_anterior() {
        $this->db->where('fechaRegistro >=', date('Y-m-d H:i:s', strtotime('-2 months')));
        $this->db->where('fechaRegistro <', date('Y-m-d H:i:s', strtotime('-1 month')));
        return $this->db->count_all_results('compra');
    }

    // Obtener compras de hoy
    public function obtener_compras_hoy() {
        $this->db->where('DATE(fechaRegistro)', date('Y-m-d'));
        return $this->db->count_all_results('compra');
    }
}
