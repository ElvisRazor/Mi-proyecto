<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Producto_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function obtenerProductoPorId($idProducto) {
        return $this->db->get_where('producto', ['idProducto' => $idProducto])->row_array();
    }

    public function actualizarStock($idProducto, $nuevoStock) {
        $this->db->where('idProducto', $idProducto);
        $this->db->update('producto', ['stock' => $nuevoStock]);
    }

    // Obtener productos activos
    public function obtener_productos_activos() {
        $this->db->where('estado', 1); // Solo productos activos
        $query = $this->db->get('producto');
        return $query->result_array(); // Devuelve todos los resultados como un array
    }

    // Agregar un nuevo producto
    public function agregar_producto($data) {
        return $this->db->insert('producto', $data);
    }

    // Editar un producto existente
    public function editar_producto($idProducto, $data) {
        $this->db->where('idProducto', $idProducto);
        return $this->db->update('producto', $data);
    }

    // Eliminar un producto (cambiar el estado a inactivo)
    public function eliminar_producto($idProducto) {
        $this->db->where('idProducto', $idProducto);
        return $this->db->update('producto', ['estado' => 0]);
    }

    // Obtener producto por ID
    public function obtener_producto_por_id($idProducto) {
        $this->db->where('idProducto', $idProducto);
        $query = $this->db->get('producto');
        return $query->row_array();
    }

    // Obtener categorías (asumiendo que existe una tabla categorías)
    public function obtener_categorias() {
        $query = $this->db->get('categoria');
        return $query->result_array();
    }
    // Método para obtener los productos que tienen stock disponible
    public function obtener_productos_con_stock() {
        $this->db->where('stock >', 0); // Solo productos con stock mayor a 0
        $query = $this->db->get('producto'); // Consultar la tabla 'producto'
        return $query->result_array(); // Devolver los productos como un array
    }
}
?>
