<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Venta_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    // Obtener todas las ventas activas
    public function obtener_ventas() {
        $this->db->where('estado', 1); // Solo ventas activas
        $query = $this->db->get('ventas');
        return $query->result_array(); // Devuelve todos los resultados como un array
    }

    // Agregar una nueva venta
    public function agregar_venta($data) {
        return $this->db->insert('ventas', $data);
    }

    // Editar una venta existente
    public function editar_venta($idVenta, $data) {
        $this->db->where('idVenta', $idVenta);
        return $this->db->update('ventas', $data);
    }

    // Obtener una venta por ID
    public function obtener_venta_por_id($idVenta) {
        $this->db->where('idVenta', $idVenta);
        $query = $this->db->get('ventas');
        return $query->row_array();
    }

    // Eliminar una venta (cambiar el estado a inactivo)
    public function eliminar_venta($idVenta) {
        $this->db->where('idVenta', $idVenta);
        return $this->db->update('ventas', ['estado' => 0]);
    }
}
?>
