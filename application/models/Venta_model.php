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
        $this->db->join('detalleventa dv', 'v.idVenta = dv.idVenta');
        $this->db->join('producto p', 'dv.idProducto = p.idProducto');
        $query = $this->db->get();
        return $query->result_array();
    }

    // Obtener la venta por ID
    public function obtener_venta_por_id($idVenta) {
        $this->db->select('v.*, c.nombre AS nombre_cliente');
        $this->db->from('venta v');
        $this->db->join('cliente c', 'v.idCliente = c.idCliente');
        $this->db->where('v.idVenta', $idVenta);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function obtener_detalles_venta($idVenta) {
        $this->db->select('dv.*, p.nombre AS nombre_producto');
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
    //#################3
    // Obtener ventas de la semana actual
    public function obtener_ventas_semanales() {
        $this->db->where('fechaRegistro >=', date('Y-m-d H:i:s', strtotime('-1 week')));
        return $this->db->count_all_results('venta');
    }

    // Obtener ventas del mes actual
    public function obtener_ventas_mensuales() {
        $this->db->where('fechaRegistro >=', date('Y-m-d H:i:s', strtotime('-1 month')));
        return $this->db->count_all_results('venta');
    }

    // Obtener ventas de la semana anterior
    public function obtener_ventas_semana_anterior() {
        $this->db->where('fechaRegistro >=', date('Y-m-d H:i:s', strtotime('-2 weeks')));
        $this->db->where('fechaRegistro <', date('Y-m-d H:i:s', strtotime('-1 week')));
        return $this->db->count_all_results('venta');
    }

    // Obtener ventas del mes anterior
    public function obtener_ventas_mes_anterior() {
        $this->db->where('fechaRegistro >=', date('Y-m-d H:i:s', strtotime('-2 months')));
        $this->db->where('fechaRegistro <', date('Y-m-d H:i:s', strtotime('-1 month')));
        return $this->db->count_all_results('venta');
    }
    // Obtener ventas de hoy
    public function obtener_ventas_hoy() {
        $this->db->where('DATE(fechaRegistro)', date('Y-m-d'));
        return $this->db->count_all_results('venta');
    }
}
