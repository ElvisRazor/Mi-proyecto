<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cliente_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    // Obtener clientes activos
    public function obtener_clientes_activos() {
        $this->db->where('estado', 1); // Solo clientes activos
        $query = $this->db->get('clientes');
        return $query->result_array(); // Devuelve todos los resultados como un array
    }

    // Obtener clientes eliminados (inactivos)
    public function obtener_clientes_eliminados() {
        $this->db->where('estado', 0); // Solo clientes inactivos
        $query = $this->db->get('clientes');
        return $query->result_array(); // Devuelve todos los resultados como un array
    }

    // Agregar un nuevo cliente
    public function agregar_cliente($data) {
        return $this->db->insert('clientes', $data);
    }

    // Editar un cliente existente
    public function editar_cliente($idCliente, $data) {
        $this->db->where('idCliente', $idCliente);
        return $this->db->update('clientes', $data);
    }

    // Eliminar un cliente (cambiar el estado a inactivo)
    public function eliminar_cliente($idCliente) {
        $this->db->where('idCliente', $idCliente);
        return $this->db->update('clientes', ['estado' => 0]);
    }

    // Habilitar un cliente (cambiar el estado a activo)
    public function habilitar_cliente($idCliente) {
        $this->db->where('idCliente', $idCliente);
        return $this->db->update('clientes', ['estado' => 1]);
    }

    // Verificar si un correo electrónico ya existe
    public function email_exists($email) {
        $this->db->where('email', $email);
        $query = $this->db->get('clientes');
        return $query->num_rows() > 0;
    }

    // Obtener cliente por ID
    public function obtener_cliente_por_id($idCliente) {
        $this->db->where('idCliente', $idCliente);
        $query = $this->db->get('clientes');
        return $query->row_array();
    }
}
?>
