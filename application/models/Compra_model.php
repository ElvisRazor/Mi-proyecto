<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Compra_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    // Obtener todas las compras activas
    public function obtener_compras() {
        $this->db->where('estado', 1); // Solo compras activas
        $query = $this->db->get('compras');
        return $query->result_array(); // Devuelve todos los resultados como un array
    }

    // Agregar una nueva compra
    public function agregar_compra($data) {
        return $this->db->insert('compras', $data);
    }

    // Editar una compra existente
    public function editar_compra($idCompra, $data) {
        $this->db->where('idCompra', $idCompra);
        return $this->db->update('compras', $data);
    }

    // Obtener una compra por ID
    public function obtener_compra_por_id($idCompra) {
        $this->db->where('idCompra', $idCompra);
        $query = $this->db->get('compras');
        return $query->row_array();
    }

    // Eliminar una compra (cambiar el estado a inactivo)
    public function eliminar_compra($idCompra) {
        $this->db->where('idCompra', $idCompra);
        return $this->db->update('compras', ['estado' => 0]);
    }
}
?>
