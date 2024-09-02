<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cliente_model extends CI_Model {

    public function obtener_clientes_activos() {
        $this->db->where('estado', 1);
        $query = $this->db->get('Clientes');
        return $query->result_array();
    }

    public function obtener_cliente_por_id($cliente_id) {
        $query = $this->db->get_where('Clientes', ['cliente_id' => $cliente_id]);
        return $query->row_array();
    }

    public function agregar_cliente($data) {
        return $this->db->insert('Clientes', $data);
    }

    public function actualizar_cliente($cliente_id, $data) {
        $this->db->where('cliente_id', $cliente_id);
        return $this->db->update('Clientes', $data);
    }

    public function eliminar_cliente_logicamente($cliente_id) {
        $this->db->where('cliente_id', $cliente_id);
        return $this->db->update('Clientes', ['estado' => 0]);
    }

    public function obtener_clientes_inactivos() {
        $this->db->where('estado', 0);
        $query = $this->db->get('Clientes');
        return $query->result_array();
    }
}
?>
