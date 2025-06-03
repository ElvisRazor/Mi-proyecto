<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Producto_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function obtenerProductoPorId($idProducto) {
        return $this->db->get_where('producto', ['idProducto' => $idProducto])->row_array();
    }

    // Método para actualizar el stock del producto
    public function actualizarStock($idProducto, $nuevoStock) {
        if ($nuevoStock < 0) {
            throw new Exception("El stock no puede ser negativo.");
        }

        $data = ['stock' => $nuevoStock];

        // Si el stock es 0, se marca el producto como inactivo
        if ($nuevoStock == 0) {
            $data['estado'] = 0; // Producto inactivo
        }

        $this->db->where('idProducto', $idProducto);
        return $this->db->update('producto', $data);
    }

    // Obtener productos activos
    public function obtener_productos_activos() {
        $this->db->where('estado', 1); // Solo productos activos
        $query = $this->db->get('producto');
        return $query->result_array(); // Devuelve todos los resultados como un array
    }

    // Obtener productos eliminados (inactivos)
    public function obtener_productos_eliminados() {
        $this->db->where('estado', 0); // Solo clproductosientes inactivos
        $query = $this->db->get('producto');
        return $query->result_array(); // Devuelve todos los resultados como un array
    }

    // Agregar un nuevo producto
    public function agregar_producto($data) {
        if ($data['stock'] != 0) {
            throw new Exception("El producto solo puede crearse con un stock inicial de 0.");
        }
        $data['estado'] = 1; // Por defecto, el producto es activo
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
    public function obtener_categorias_activos() {
        $this->db->where('estado', 1); // Solo categorías activas
        $query = $this->db->get('categoria');
        return $query->result_array(); // Devuelve todos los resultados como un array
    }
    // Método para obtener los productos que tienen stock disponible
    public function obtener_productos_con_stock() {
        $this->db->where('stock >', 0); // Solo productos con stock mayor a 0
        $query = $this->db->get('producto'); // Consultar la tabla 'producto'
        return $query->result_array(); // Devolver los productos como un array
    }
    // Contar el número total de productos
    public function contar_productos() {
        return $this->db->count_all('producto');
    }
    // Calcular el incremento de productos en la última semana
    public function calcular_incremento_productos() {
        $this->db->where('fechaRegistro >=', date('Y-m-d H:i:s', strtotime('-1 week')));
        $nuevos_productos = $this->db->count_all_results('producto');

        $total_productos = $this->contar_productos();
        if ($total_productos > 0) {
            return ($nuevos_productos / $total_productos) * 100;
        } else {
            return 0;
        }
    }
    // Habilitar un usuario (cambiar el estado a activo)
    public function habilitar_producto($idProducto) {
        $this->db->where('idProducto', $idProducto);
        return $this->db->update('producto', ['estado' => 1]);
    }
}
?>