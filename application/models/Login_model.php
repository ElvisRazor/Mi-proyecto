<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function login($email, $password) {
        $this->db->where("email", $email);
        $this->db->where("password", md5($password)); // Asegúrate de que esta columna coincide con la de la base de datos
        $query = $this->db->get("Usuarios"); // Asegúrate de que el nombre de la tabla es correcto

        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return false;
        }
    }
}
