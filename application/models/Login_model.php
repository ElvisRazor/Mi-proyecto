<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function login($email, $password) {
        $this->db->where("email", $email);
        $this->db->where("password", $password); // Asegúrate de que esta columna coincide con la de la base de datos
        $this->db->where("estado", 1); // Solo usuarios activos (estado = 1)
        $query = $this->db->get("Usuario"); // Asegúrate de que el nombre de la tabla es correcto

        if ($query->num_rows() == 1) {
            return $query->row();
            var_dump($user); // Esto imprimirá todos los valores del usuario
            return $user;
        } else {
            return false;
        }
    }
}
