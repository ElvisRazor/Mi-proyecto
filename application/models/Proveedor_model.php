<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proveedor_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    // Obtener proveedores activos
    public function obtener_proveedores_activos() {
        $this->db->where('estado', 1); // Solo proveedores activos
        $query = $this->db->get('proveedor');
        return $query->result_array(); // Devuelve todos los resultados como un array
    }

    // Obtener proveedores eliminados (inactivos)
    public function obtener_proveedores_eliminados() {
        $this->db->where('estado', 0); // Solo proveedores inactivos
        $query = $this->db->get('proveedor');
        return $query->result_array(); // Devuelve todos los resultados como un array
    }

    // Agregar un nuevo proveedor
    public function agregar_proveedor($data) {
        return $this->db->insert('proveedor', $data);
    }

    // Editar un proveedor existente
    public function editar_proveedor($idProveedor, $data) {
        $this->db->where('idProveedor', $idProveedor);
        return $this->db->update('proveedor', $data);
    }

    // Eliminar un proveedor (cambiar el estado a inactivo)
    public function eliminar_proveedor($idProveedor) {
        $this->db->where('idProveedor', $idProveedor);
        return $this->db->update('proveedor', ['estado' => 0]);
    }

    // Habilitar un proveedor (cambiar el estado a activo)
    public function habilitar_proveedor($idProveedor) {
        $this->db->where('idProveedor', $idProveedor);
        return $this->db->update('proveedor', ['estado' => 1]);
    }

    // Verificar si un correo electrónico ya existe
    public function email_exists($email) {
        $this->db->where('email', $email);
        $query = $this->db->get('proveedor');
        return $query->num_rows() > 0;
    }

    // Obtener proveedor por ID
    public function obtener_proveedor_por_id($idProveedor) {
        $this->db->where('idProveedor', $idProveedor);
        $query = $this->db->get('proveedor');
        return $query->row_array();
    }
    // Contar el número total de productos
    public function contar_proveedores() {
        return $this->db->count_all('proveedor');
    }
    // Calcular el incremento de productos en la última semana
    public function calcular_incremento_proveedores() {
        $this->db->where('fechaRegistro >=', date('Y-m-d H:i:s', strtotime('-1 week')));
        $nuevos_proveedores = $this->db->count_all_results('proveedor');

        $total_proveedores = $this->contar_proveedores();
        if ($total_proveedores > 0) {
            return ($nuevos_proveedores / $total_proveedores) * 100;
        } else {
            return 0;
        }
    }
}
?>
