<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    // Contar el número total de usuarios
    public function contar_usuarios() {
        return $this->db->count_all('usuario');
    }

    // Calcular el incremento de usuarios en la última semana
    public function calcular_incremento_usuarios() {
        $this->db->where('fechaRegistro >=', date('Y-m-d H:i:s', strtotime('-1 week')));
        $nuevos_usuarios = $this->db->count_all_results('usuario');

        $total_usuarios = $this->contar_usuarios();
        if ($total_usuarios > 0) {
            return ($nuevos_usuarios / $total_usuarios) * 100;
        } else {
            return 0;
        }
    }

    // Obtener usuarios activos
    public function obtener_usuarios_activos() {
        $this->db->where('estado', 1); // Solo usuarios activos
        $query = $this->db->get('usuario');
        return $query->result_array(); // Devuelve todos los resultados como un array
    }

    // Obtener usuarios eliminados (inactivos)
    public function obtener_usuarios_eliminados() {
        $this->db->where('estado', 0); // Solo usuarios inactivos
        $query = $this->db->get('usuario');
        return $query->result_array(); // Devuelve todos los resultados como un array
    }

    // Agregar un nuevo usuario
    public function agregar_usuario($data) {
        return $this->db->insert('usuario', $data);
    }

    // Editar un usuario existente
    public function editar_usuario($idUsuario, $data) {
        $this->db->where('idUsuario', $idUsuario);
        return $this->db->update('usuario', $data);
    }

    // Eliminar un usuario (cambiar el estado a inactivo)
    public function eliminar_usuario($idUsuario) {
        $this->db->where('idUsuario', $idUsuario);
        return $this->db->update('usuario', ['estado' => 0]);
    }

    // Habilitar un usuario (cambiar el estado a activo)
    public function habilitar_usuario($idUsuario) {
        $this->db->where('idUsuario', $idUsuario);
        return $this->db->update('usuario', ['estado' => 1]);
    }

    // Verificar si un correo electrónico ya existe
    public function email_exists($email) {
        $this->db->where('email', $email);
        $query = $this->db->get('usuario');
        return $query->num_rows() > 0;
    }

    // Obtener usuario por ID
    public function obtener_usuario_por_id($idUsuario) {
        $this->db->where('idUsuario', $idUsuario);
        $query = $this->db->get('usuario');
        return $query->row_array();
    }
}
?>
