<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categoria_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    // Obtener categorías activas
    public function obtener_categorias_activos() {
        $this->db->where('estado', 1); // Solo categorías activas
        $query = $this->db->get('categoria');
        return $query->result_array(); // Devuelve todos los resultados como un array
    }

    // Obtener categorías inactivas
    public function obtener_categorias_inactivos() {
        $this->db->where('estado', 0); // Solo categorías inactivas
        $query = $this->db->get('categoria');
        return $query->result_array(); // Devuelve todos los resultados como un array
    }

    // Agregar una nueva categoría
    public function agregar_categoria($data) {
        return $this->db->insert('categoria', $data);
    }

    // Editar una categoría existente
    public function editar_categoria($idCategoria, $data) {
        $this->db->where('idCategoria', $idCategoria);
        return $this->db->update('categoria', $data);
    }

    // Eliminar una categoría (cambiar el estado a inactivo)
    public function eliminar_categoria($idCategoria) {
        $this->db->where('idCategoria', $idCategoria);
        return $this->db->update('categoria', ['estado' => 0]);
    }

    // Habilitar una categoría (cambiar el estado a activo)
    public function habilitar_categoria($idCategoria) {
        $this->db->where('idCategoria', $idCategoria);
        return $this->db->update('categoria', ['estado' => 1]);
    }

    // Obtener categoría por ID
    public function obtener_categoria_por_id($idCategoria) {
        $this->db->where('idCategoria', $idCategoria);
        $query = $this->db->get('categoria');
        return $query->row_array();
    }
}
?>
