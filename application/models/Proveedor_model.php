<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proveedor_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    // Obtener proveedores activos
    public function obtener_proveedores_activos() {
        $this->db->where('estado', 1); // Solo proveedores activos
        $query = $this->db->get('proveedores');
        return $query->result_array(); // Devuelve todos los resultados como un array
    }

    // Obtener proveedores eliminados (inactivos)
    public function obtener_proveedores_eliminados() {
        $this->db->where('estado', 0); // Solo proveedores inactivos
        $query = $this->db->get('proveedores');
        return $query->result_array(); // Devuelve todos los resultados como un array
    }

    // Agregar un nuevo proveedor
    public function agregar_proveedor($data) {
        return $this->db->insert('proveedores', $data);
    }

    // Editar un proveedor existente
    public function editar_proveedor($idProveedor, $data) {
        $this->db->where('idProveedor', $idProveedor);
        return $this->db->update('proveedores', $data);
    }

    // Eliminar un proveedor (cambiar el estado a inactivo)
    public function eliminar_proveedor($idProveedor) {
        $this->db->where('idProveedor', $idProveedor);
        return $this->db->update('proveedores', ['estado' => 0]);
    }

    // Habilitar un proveedor (cambiar el estado a activo)
    public function habilitar_proveedor($idProveedor) {
        $this->db->where('idProveedor', $idProveedor);
        return $this->db->update('proveedores', ['estado' => 1]);
    }

    // Verificar si un correo electrónico ya existe
    public function email_exists($email) {
        $this->db->where('email', $email);
        $query = $this->db->get('proveedores');
        return $query->num_rows() > 0;
    }

    // Obtener proveedor por ID
    public function obtener_proveedor_por_id($idProveedor) {
        $this->db->where('idProveedor', $idProveedor);
        $query = $this->db->get('proveedores');
        return $query->row_array();
    }
}
?>
