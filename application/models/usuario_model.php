<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario_model extends CI_Model {

public function __construct() {
    $this->load->database();
}

public function get_user($email, $password) {
    $this->db->where('email', $email);
    $this->db->where('password', $password); // Verifica que las contraseñas estén cifradas como MD5 en la base de datos
    $query = $this->db->get('usuarios');
    return $query->row(); // Devuelve el primer resultado
}
}
