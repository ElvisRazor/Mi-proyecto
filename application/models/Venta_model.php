<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Venta_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }
    // Obtener todas las compras activas
    public function obtener_ventas() {
        $this->db->where('estado', 1); // Solo compras activas
        $query = $this->db->get('venta');
        return $query->result_array(); // Devuelve todos los resultados como un array
    }

    // Iniciar una nueva transacción de venta
    public function iniciar_transaccion() {
        $this->db->trans_begin();  // Inicia una transacción
    }

    public function finalizar_transaccion() {
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();  // Revertir si hay error
            return FALSE;
        } else {
            $this->db->trans_commit();  // Confirmar si todo va bien
            return TRUE;
        }
    }

    public function agregar_venta($data, $detalles) {
        // Insertar datos de la venta
        $this->db->insert('venta', $data);
        $idVenta = $this->db->insert_id();

        // Insertar los detalles de los productos vendidos
        foreach ($detalles as $detalle) {
            $detalle['idVenta'] = $idVenta;
            $this->db->insert('detalleventa', $detalle);

            // Actualizar el stock del producto
            $this->db->set('stock', 'stock - ' . (int)$detalle['cantidad'], FALSE);
            $this->db->where('idProducto', $detalle['idProducto']);
            $this->db->update('producto');
        }

        return $this->finalizar_transaccion();
    }

    // Obtener los productos con stock
    public function obtener_productos_con_stock() {
        $this->db->where('stock >', 0);
        $query = $this->db->get('producto');
        return $query->result_array();
    }

    // Obtener producto por ID
    public function obtener_producto_por_id($idProducto) {
        $this->db->where('idProducto', $idProducto);
        $query = $this->db->get('producto');
        return $query->row_array();
    }

    // Obtener cliente por ID
    public function obtener_cliente_por_id($idCliente) {
        $this->db->where('idCliente', $idCliente);
        $query = $this->db->get('cliente');
        return $query->row_array();
    }

    public function obtener_clientes() {
        $this->db->where('estado', 1); // Solo clientes activos
        $query = $this->db->get('cliente');
        return $query->result_array(); // Devuelve todos los resultados como un array
    }
    
}
